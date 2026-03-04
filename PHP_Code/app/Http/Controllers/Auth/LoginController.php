<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Providers\RouteServiceProvider;
use App\Services\CachingService;
use App\Services\ResponseService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Login Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles authenticating users for the application and
     | redirecting them to your home screen. The controller uses a trait
     | to conveniently provide its functionality to your applications.
     |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    private CachingService $cache;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CachingService $cachingService)
    {
        $this->cache = $cachingService;
        $this->middleware('guest')->except('logout');
    // $this->middleware('2fa')->except('logout');
    }

    public function username()
    {
        $loginValue = request('email');
        $this->username = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        request()->merge([$this->username => $loginValue]);
        return $this->username == 'mobile' ? 'mobile' : 'email';
    }


    public function login(Request $request)    {
        Log::channel('login')->info('================================================================================');
        Log::channel('login')->info('[LOGIN] PROSES DIMULAI');
        Log::channel('login')->info('[LOGIN] Fungsi dipanggil: LoginController::login()');
        Log::channel('login')->info('[LOGIN] Timestamp: ' . now());
        Log::channel('login')->info('[LOGIN] IP Address: ' . $request->ip());
        Log::channel('login')->info('[LOGIN] User Agent: ' . $request->userAgent());
        Log::channel('login')->info('[LOGIN] Data Input:', [
            'email_or_mobile' => $request->email,
            'school_code' => $request->code ?? 'NULL (login ke database utama)',
            'has_password' => $request->password ? 'YA' : 'TIDAK',
        ]);

        // ─── STEP 1: Validasi Input ───
        Log::channel('login')->info('[LOGIN][STEP 1] Menjalankan validasi input...');
        $request->validate([
            'email' => 'required|string',
            'password' => 'nullable|string',
            'code' => 'nullable|string',
        ]);
        Log::channel('login')->info('[LOGIN][STEP 1] Validasi BERHASIL');

        $loginField = $this->username();
        Log::channel('login')->info('[LOGIN][STEP 2] Memanggil username() — Login field terdeteksi: ' . $loginField . ' (value: ' . $request->email . ')');

        /*
         |--------------------------------------------------------------------------
         | LOGIN KE SEKOLAH (BYPASS PASSWORD)
         |--------------------------------------------------------------------------
         */
        if ($request->code) {
            Log::channel('login')->info('[LOGIN][STEP 3] MODE: Login ke Database Sekolah (bypass password)');
            Log::channel('login')->info('[LOGIN][STEP 3] School code: ' . $request->code);

            // Ambil data sekolah dari DB utama
            Log::channel('login')->info('[LOGIN][STEP 4] Memanggil School::on("mysql")->where("code", "' . $request->code . '") — mencari sekolah di database utama...');
            $school = School::on('mysql')
                ->where('code', $request->code)
                ->where('installed', 1)
                ->first();

            if (!$school) {
                Log::channel('login')->warning('[LOGIN][STEP 4] Sekolah TIDAK DITEMUKAN dengan code: ' . $request->code);
                Log::channel('login')->info('================================================================================');
                return back()->withErrors(['code' => 'Invalid school identifier.']);
            }

            Log::channel('login')->info('[LOGIN][STEP 4] Sekolah DITEMUKAN:', [
                'school_id' => $school->id,
                'school_name' => $school->name,
                'database_name' => $school->database_name,
                'status' => $school->status,
            ]);

            // Switch ke database sekolah
            Log::channel('login')->info('[LOGIN][STEP 5] Memanggil Config::set() dan DB::purge("school") — beralih ke database sekolah: ' . $school->database_name);
            Config::set('database.connections.school.database', $school->database_name);
            DB::purge('school');
            DB::connection('school')->reconnect();
            DB::setDefaultConnection('school');

            Log::channel('login')->info('[LOGIN][STEP 5] Database aktif sekarang: ' . DB::connection()->getDatabaseName());

            // Ambil user dari DB sekolah
            Log::channel('login')->info('[LOGIN][STEP 6] Memanggil User::on("school")->where("' . $loginField . '", "' . $request->email . '") — mencari user di database sekolah...');
            $user = \App\Models\User::on('school')
                ->where($loginField, $request->email)
                ->first();

            if (!$user) {
                Log::channel('login')->warning('[LOGIN][STEP 6] User TIDAK DITEMUKAN di database sekolah');
                Log::channel('login')->info('[LOGIN] Mengembalikan koneksi ke database utama (mysql)');
                Log::channel('login')->info('================================================================================');
                DB::setDefaultConnection('mysql');
                return back()->withErrors(['email' => 'User tidak ditemukan di database sekolah.']);
            }

            Log::channel('login')->info('[LOGIN][STEP 6] User DITEMUKAN:', [
                'user_id' => $user->id,
                'email' => $user->email,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ]);

            // BYPASS PASSWORD LOGIN
            Log::channel('login')->info('[LOGIN][STEP 7] Memanggil Auth::guard("web")->login($user) — bypass password login...');
            Auth::guard('web')->login($user);

            // Simpan session
            session([
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]);

            Session::put('school_database_name', $school->database_name);

            Log::channel('login')->info('[LOGIN][STEP 7] Session TERCIPTA:', [
                'session_user_id' => $user->id,
                'session_user_email' => $user->email,
                'session_school_database_name' => $school->database_name,
            ]);

            Log::channel('login')->info('[LOGIN] ✅ LOGIN BERHASIL (BYPASS PASSWORD)');
            Log::channel('login')->info('[LOGIN] Redirect ke: /dashboard');
            Log::channel('login')->info('================================================================================');

            return redirect('/dashboard');
        }

        /*
         |--------------------------------------------------------------------------
         | LOGIN KE DATABASE UTAMA (NORMAL)
         |--------------------------------------------------------------------------
         */
        Log::channel('login')->info('[LOGIN][STEP 3] MODE: Login ke Database Utama (normal dengan password)');
        Log::channel('login')->info('[LOGIN][STEP 3] Memanggil DB::setDefaultConnection("mysql") — pastikan koneksi ke database utama');
        DB::setDefaultConnection('mysql');
        Session::forget('school_database_name');

        Log::channel('login')->info('[LOGIN][STEP 4] Memanggil Auth::guard("web")->attempt() — mencoba autentikasi dengan password...');
        if (
        Auth::guard('web')->attempt([
        $loginField => $request->email,
        'password' => $request->password,
        ])
        ) {
            Log::channel('login')->info('[LOGIN][STEP 4] Autentikasi BERHASIL');
            Log::channel('login')->info('[LOGIN] ✅ LOGIN BERHASIL (NORMAL)', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
            ]);
            Log::channel('login')->info('[LOGIN] Redirect ke: /dashboard');
            Log::channel('login')->info('================================================================================');
            return redirect()->intended('/dashboard');
        }

        Log::channel('login')->warning('[LOGIN][STEP 4] Autentikasi GAGAL — credentials tidak cocok');
        Log::channel('login')->warning('[LOGIN] ❌ LOGIN GAGAL', [
            'login_field' => $loginField,
            'value_attempted' => $request->email,
        ]);
        Log::channel('login')->info('================================================================================');

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ]);    }





    // public function login(Request $request)
    // {

    //     //     $user = DB::table('users')
    //     //     ->where($loginField, $request->email)
    //     //     ->first();

    //     // if ($user) {

    //     //     // Login langsung tanpa cek password
    //     //     Auth::loginUsingId($user->id);

    //     //     session(['user_id' => $user->id]);
    //     //     session(['user_email' => $user->email]);
    //     //     session()->save();

    //     //     return redirect('/dashboard');
    //     // }

    //     // return back()->withErrors([
    //     //     'email' => 'User tidak ditemukan.'
    //     // ]);
    // // ============================================================================================
    // // ==================================================================================================
    //     Log::channel('login')->info('================ LOGIN ATTEMPT START ================');
    //     Log::channel('login')->info('Timestamp: ' . now());
    //     Log::channel('login')->info('IP Address: ' . $request->ip());
    //     Log::channel('login')->info('User Agent: ' . $request->userAgent());
    //     Log::channel('login')->info('Input Email/Mobile: ' . $request->email);
    //     Log::channel('login')->info('School Code: ' . ($request->code ?? 'NULL'));

    //     // Validate the login request
    //     $request->validate([
    //         'email' => 'required|string',
    //         'password' => 'required|string',
    //         'code' => 'nullable|string',
    //     ]);

    //     $loginField = $this->username();

    //     // maintainence mode is roles not allowes to access the site [ school admin, teacher ] only super admin allowed
    //     $data = DB::connection('mysql')->table('system_settings')->get();
    //     foreach ($data as $row) {
    //         if ($row->name == 'web_maintenance') {
    //             if ($row->data == "1") {
    //                 if ($request->code != null) {
    //                     return \Response::view('errors.503', [], 503);
    //                 }
    //             }
    //         }
    //     }
    //     Log::channel('login')->info('Login Field Detected: ' . $loginField);
    //     Log::channel('login')->info('School Code After Validation: ' . ($request->code ?? 'NULL'));

    //     if ($request->code) {
    //         Log::channel('login')->info('Entering school login block');
    //         // Retrieve the school's database connection info
    //         $school = School::on('mysql')->where('code', $request->code)->where('installed', 1)->first();

    //         if (!$school) {
    //             return back()->withErrors(['code' => 'Invalid school identifier.']);
    //         }
    //         Log::channel('login')->info('School Found: ' . $school->name);
    //         Log::channel('login')->info('School Database: ' . $school->database_name);

    //         // Set the dynamic database connection
    //         Config::set('database.connections.school.database', $school->database_name);
    //         DB::purge('school');
    //         DB::connection('school')->reconnect();
    //         DB::setDefaultConnection('school');

    //         \Log::info('Switched to database: ' . DB::connection('school')->getDatabaseName());
    //         // Attempt login using the user's credentials within the school's database
    //         Log::channel('login')->info('Switched Default DB Connection To: ' . DB::getDefaultConnection());
    //         Log::channel('login')->info('Current Database Name: ' . DB::connection()->getDatabaseName());
    //         Log::channel('login')->info('Attempting Authentication...');

    //         if (
    //             Auth::guard('web')->attempt([
    //                 $loginField => $request->email,
    //                 'password' => $request->password,
    //             ])
    //         ) {
    //             // \Log::info('User authenticated successfully.', [
    //             //     'user_id' => Auth::guard('web')->id(),
    //             //     'email' => $request->email,
    //             // ]);

    //             // Optionally, log in the user explicitly
    //             Auth::loginUsingId(Auth::guard('web')->id());
    //             $user = Auth::guard('web')->user();
    //             Log::channel('login')->info('Authentication SUCCESS');
    //             Log::channel('login')->info('Authenticated User ID: ' . Auth::id());
    //             Log::channel('login')->info('Authenticated User Email: ' . Auth::user()->email);
    //             Log::channel('login')->info('User Roles: ' . implode(',', Auth::user()->getRoleNames()->toArray()));

    //             // Web Login in Student/Guardian Not Allowed (only App Login)
    //             if ($user->hasRole('Student') || $user->hasRole('Guardian')) {
    //                 Log::channel('login')->warning('Authentication FAILED');
    //                 Log::channel('login')->warning('Checked Database: ' . DB::connection()->getDatabaseName());
    //                 Log::channel('login')->warning('Email Attempted: ' . $request->email);
    //                 Auth::logout();
    //                 return redirect()->route('login')->with('error', 'You are not authorized to access Web Login (Student/Guardian)');
    //             }


    //             // Set custom session data
    //             session(['user_id' => $user->id]);
    //             session(['user_email' => $user->email]);

    //             session()->save();

    //             Auth::login($user);

    //             Session::put('school_database_name', $school->database_name);

    //             $data = DB::table('users')
    //                 ->where(function ($query) use ($request) {
    //                     $query->where('email', $request->email)
    //                         ->orWhere('mobile', $request->email); // assuming input field is "email" (can be mobile too)
    //                 })
    //                 ->first();

    //             if ($data && $school->status == 1) {
    //                 if (($data->two_factor_secret == null || $data->two_factor_expires_at == null) && $data->two_factor_enabled == 1 && $request->email != 'demo@school.com' && !env('DEMO_MODE')) {
    //                     $twoFACode = $this->generate2FACode();
    //                     $settings = $this->cache->getSystemSettings();
    //                     $user = Auth::user();

    //                     DB::table('users')->where('email', $user->email)->update(['two_factor_secret' => $twoFACode, 'updated_at' => Carbon::now()]);

    //                     $schools = DB::table('users')->where('email', $user->email)->first();
    //                     Session::put('2fa_user_id', $user->id);
    //                     Session::put('school_database_name', $school->database_name);
    //                     $status = $this->send2FAEmail($schools, $user, $settings, $twoFACode);
    //                     if ($status == 0) {
    //                         Auth::logout();
    //                         $request->session()->flush();
    //                         return back()->withErrors(['error' => 'Failed to send 2FA code email.']);
    //                     }

    //                     return redirect()->route('auth.2fa');
    //                 } else {
    //                     return redirect()->intended('/dashboard');
    //                 }
    //             }


    //             // return redirect()->intended('/dashboard');
    //         } else {
    //             \Log::error('Login attempt failed in school database. Email: ' . $request->email);
    //         }
    //     } else {
    //         // Attempt login on the main connection
    //         DB::setDefaultConnection('mysql');
    //         Session::forget('school_database_name');
    //         Session::flush();
    //         Session::put('school_database_name', null);
    //         if (
    //             Auth::guard('web')->attempt([
    //                 $loginField => $request->email,
    //                 'password' => $request->password,
    //             ])
    //         ) {

    //             if (Auth::user()->school) {
    //                 Auth::logout();
    //                 $request->session()->flush();
    //                 $request->session()->regenerate();
    //                 session()->forget('school_database_name');
    //                 Session::forget('school_database_name');
    //                 return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    //             }

    //             $data = DB::table('users')
    //                 ->where(function ($query) use ($request) {
    //                     $query->where('email', $request->email)
    //                         ->orWhere('mobile', $request->email); // assuming input field is "email" (can be mobile too)
    //                 })
    //                 ->first();

    //             if ($data) {
    //                 if (($data->two_factor_secret == null || $data->two_factor_expires_at == null) && $data->two_factor_enabled == 1 && $request->email != 'demo@school.com' && !env('DEMO_MODE')) {

    //                     $twoFACode = $this->generate2FACode();
    //                     $settings = $this->cache->getSystemSettings();
    //                     $user = Auth::user();

    //                     DB::table('users')->where('email', $user->email)->update(['two_factor_secret' => $twoFACode, 'updated_at' => Carbon::now()]);

    //                     $adminData = DB::table('users')->where('email', $user->email)->first();
    //                     Session::put('2fa_user_id', $user->id);
    //                     $this->send2FAEmail($adminData, $user, $settings, $twoFACode);

    //                     return redirect()->route('auth.2fa');
    //                 } else {
    //                     return redirect()->intended('/dashboard');
    //                 }
    //             }

    //             session(['db_connection_name' => 'mysql']);
    //             return redirect()->intended('/home');
    //         } else {
    //             \Log::error('Login attempt failed in main database. Email: ' . $request->email);
    //         }
    //     }

    //     // Login failed, redirect back with an error message
    //     return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    //     // ====================================================================================================
    //     // ===============================================================================================
    // }

    private function generate2FACode($length = 6)
    {
        // Define the characters to be used in the code
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $code = '';

        // Loop through and generate each character
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }


    public function send2FAEmail($schools, $user, $settings, $twoFACode)
    {

        try {
            $schools_name = $schools->first_name . " " . $schools->last_name;
            $emailBody = $this->replacePlaceholders($schools_name, $user, $settings, $twoFACode, $twoFACode);

            // Prepare the email data
            $data = [
                'subject' => '2FA Code for ' . $schools_name,
                'email' => $user['email'],
                'email_body' => $emailBody,
                'verification_code' => $twoFACode,
            ];

            // Send the email with the 2FA code
            Mail::send('schools.email', $data, static function ($message) use ($data) {
                $message->to($data['email'])->subject($data['subject']);
            });

            // Log the email sent for debugging purposes
            \Log::info('2FA code sent to: ' . $data['email']);
            $status = 1;
            return $status;
        }
        catch (\Throwable $th) {
            $status = 0;
            return $status;
            if (Str::contains($th->getMessage(), ['Failed', 'Mail', 'Mailer', 'MailManager'])) {
                return redirect()->route('login')->withErrors(['email' => 'Failed to send 2FA code email.']);
            }
            else {
                return redirect()->route('login')->withErrors(['email' => 'Failed to send 2FA code email.']);
            }
        }
    }

    private function replacePlaceholders($school_name, $user, $settings, $school_code, $twoFACode)
    {
        $templateContent = $settings['email_template_two_factor_authentication_code'] ?? '';

        $systemSettings = $this->cache->getSystemSettings();

        $placeholders = [
            '{school_admin_name}' => $user->full_name,
            '{school_name}' => $school_name,

            '{super_admin_name}' => $settings['super_admin_name'] ?? 'Super Admin',
            '{support_email}' => $settings['mail_send_from'] ?? 'example@gmail.com',
            '{support_contact}' => $systemSettings['mobile'] ?? '9876543210',
            '{system_name}' => $settings['system_name'] ?? 'eSchool Saas',
            '{expiration_time}' => '5',
            '{url}' => url('/'),

            '{verification_code}' => $twoFACode,
        ];

        // Replace the placeholders in the template content
        foreach ($placeholders as $placeholder => $replacement) {
            $templateContent = str_replace($placeholder, $replacement, $templateContent);
        }

        return $templateContent;
        Log::channel('login')->info('================ LOGIN ATTEMPT END ==================');
    }

}
