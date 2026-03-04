<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Students;
use Illuminate\Http\Request;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;

try {
    // Mock Auth
    $user = User::whereHas('roles', function ($q) {
        $q->where('name', 'School Admin');
    })->first();

    if (!$user) {
        file_put_contents('test_error.log', "No school admin found.\n");
        exit;
    }

    Auth::login($user);

    // Get a class_id
    $class = \App\Models\ClassSchool::where('school_id', $user->school_id)->first();
    $sessionYear = app(\App\Services\CachingService::class)->getDefaultSessionYear($user->school_id);

    if (!$class || !$sessionYear) {
        file_put_contents('test_error.log', "No class or session year found.\n");
        exit;
    }

    $request = Request::create('/students', 'POST', [
        'first_name' => 'Test',
        'last_name' => 'Student' . time(),
        'mobile' => '081234567890',
        'dob' => '2010-01-01',
        'class_id' => $class->id,
        'admission_no' => 'TEST-' . time(),
        'admission_date' => date('Y-m-d'),
        'session_year_id' => $sessionYear->id,
        'guardian_email' => 'guardian' . time() . '@example.com',
        'guardian_first_name' => 'Test',
        'guardian_last_name' => 'Guardian',
        'guardian_mobile' => '081234' . rand(100000, 999999),
        'guardian_gender' => 'male',
    ]);

    $controller = app(StudentController::class);

    $response = $controller->store($request);

    // Check if student was created as expected
    $student = Students::whereHas('user', function ($q) use ($request) {
        $q->where('email', $request->admission_no);
    })->latest()->first();

    if ($student) {
        $msg = "Student created successfully!\n";
        $msg .= "application_type: " . $student->application_type . "\n";
        $msg .= "application_status: " . $student->application_status . "\n";
        $msg .= "class_id: " . $student->class_id . "\n";
        $msg .= "class_section_id: " . ($student->class_section_id ?? 'null') . "\n";
        file_put_contents('test_error.log', $msg);
    }
    else {
        file_put_contents('test_error.log', "Failed to find created student.\n");
    }
}
catch (\Illuminate\Validation\ValidationException $e) {
    file_put_contents('test_error.log', "Validation Error: " . json_encode($e->errors()));
}
catch (\Throwable $e) {
    file_put_contents('test_error.log', "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
}
