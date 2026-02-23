<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\School;
use App\Services\SchoolDataService;
use App\Services\SubscriptionService;
use App\Services\CachingService;
use App\Repositories\SystemSetting\SystemSettingInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

final class SetupSchoolDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;
    public int $backoff = 120;

    public function __construct(
        private readonly int $schoolId,
        private readonly ?int $packageId = null,
        private readonly ?string $schoolCodePrefix = null
    ) {}

    /* ========================================================= */
    /* ===================== MAIN HANDLER ====================== */
    /* ========================================================= */

    public function handle(
        SchoolDataService $schoolService,
        SubscriptionService $subscriptionService,
        CachingService $cache,
        SystemSettingInterface $systemSettings
    ): void {

        $jobStartedAt = microtime(true);
        $settings = $cache->getSystemSettings();

        $this->logStep("JOB STARTED", [
            'tries' => $this->tries,
            'timeout' => $this->timeout,
            'backoff' => $this->backoff,
        ]);

        // Optional SQL listener (enable only in local/debug)
        if (config('app.debug')) {
            DB::listen(function ($query) {
                Log::debug('[SQL]', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time_ms' => $query->time,
                ]);
            });
        }

        try {

            DB::setDefaultConnection('mysql');

            /* ===================== FETCH SCHOOL ===================== */

            $school = School::with('user')->findOrFail($this->schoolId);

            $this->logStep("School Loaded", [
                'school_name' => $school->name,
                'database_name' => $school->database_name,
                'status' => $school->status,
                'installed' => $school->installed,
            ]);

            /* ===================== DATABASE CREATION ===================== */

            $this->createAndVerifyDatabaseAndActivate($school);

            /* ===================== MIGRATION ===================== */

            $migrationStart = microtime(true);
            $this->logStep("Starting migration");

            $schoolService->createDatabaseMigration($school);

            $this->logStep("Migration finished", [
                'duration_seconds' => round(microtime(true) - $migrationStart, 2),
            ]);

            /* ===================== PRE SETTINGS ===================== */

            $this->logStep("Running pre-settings setup");
            $schoolService->preSettingsSetup($school);

            /* ===================== SUBSCRIPTION ===================== */

            if ($this->packageId) {

                $this->logStep("Creating subscription", [
                    'package_id' => $this->packageId
                ]);

                if ($school->user) {
                    \Illuminate\Support\Facades\Auth::login($school->user);
                }

                $subscription = $subscriptionService->createSubscription(
                    $this->packageId,
                    $school->id,
                    null,
                    1
                );
\Illuminate\Support\Facades\Auth::logout();
                // Give ALL features to the newly registered school to unlock everything
                $allFeatures = \App\Models\Feature::activeFeatures()->get();
                $subscriptionFeatures = [];
                foreach ($allFeatures as $feature) {
                    $subscriptionFeatures[] = [
                        'subscription_id' => $subscription->id,
                        'feature_id'      => $feature->id
                    ];
                }
                \App\Models\SubscriptionFeature::upsert($subscriptionFeatures, ['subscription_id', 'feature_id'], ['subscription_id', 'feature_id']);
                $cache->removeSchoolCache(
                    config('constants.CACHE.SCHOOL.SETTINGS'),
                    $school->id
                );
            }

            /* ===================== PREFIX UPDATE ===================== */

            if ($this->schoolCodePrefix) {

                

                if (($settings['school_prefix'] ?? '') !== $this->schoolCodePrefix) {

                    $this->logStep("Updating school code prefix", [
                        'new_prefix' => $this->schoolCodePrefix
                    ]);

                    $systemSettings->upsert([
                        [
                            "name" => 'school_prefix',
                            "data" => $this->schoolCodePrefix,
                            "type" => "text"
                        ]
                    ], ["name"], ["data"]);

                    $cache->removeSystemCache(
                        config('constants.CACHE.SYSTEM.SETTINGS')
                    );

                    $settings = $cache->getSystemSettings();
                }
            }

            /* ===================== STATUS UPDATE (TRANSACTION SAFE) ===================== */

            
            /* ===================== SWITCH CONNECTION ===================== */

            $this->switchToSchoolDatabase($school->database_name);

            /* ===================== EMAIL ===================== */
            
            $this->logStep("Setting school status to ACTIVE in TENANT DATABASE");

            $school->update([
                'status' => 1,
                'installed' => 1
            ]);

            $this->logStep("School marked as INSTALLED and ACTIVE", [
                'school_id' => $school->id
            ]);

            $emailBody = $this->replacePlaceholders(
                $school,
                $school->user,
                $settings,
                $school->code
            );

            $recipientEmail = $school->support_email ?? $school->user->email;
            if (!empty($recipientEmail)) {
                $this->sendWelcomeEmail(
                    (string)$recipientEmail,
                    $settings,
                    $emailBody
                );
            } else {
                $this->logStep("Skipping welcome email: No recipient email found.");
            }

            /* ===================== EMAIL VERIFICATION ===================== */

            // if (!$school->user->hasVerifiedEmail()) {

            //     $this->logStep("Sending email verification notification");

            //     sleep(5);

            //     $school->user->sendEmailVerificationNotification();
            // }

            $this->logStep("JOB COMPLETED SUCCESSFULLY", [
                'total_duration_seconds' =>
                    round(microtime(true) - $jobStartedAt, 2),
                'memory_usage_mb' =>
                    round(memory_get_usage(true) / 1024 / 1024, 2),
            ]);

        } catch (Throwable $e) {

            Log::error("SetupSchoolDatabase FAILED", [
                'school_id' => $this->schoolId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /* ========================================================= */
    /* ================= DATABASE CREATION ====================== */
    /* ========================================================= */

    private function createAndVerifyDatabaseAndActivate(School $school): void
    {
        $databaseName = $school->database_name;

        $this->logStep("Checking database existence", [
            'database' => $databaseName
        ]);

        $exists = DB::select(
            "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
            [$databaseName]
        );

        if (!empty($exists)) {
            $this->logStep("Database already exists, forcing status ACTIVE");
        } else {

            $this->logStep("Creating database");

            DB::statement("CREATE DATABASE `{$databaseName}`");

            $verify = DB::select(
                "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?",
                [$databaseName]
            );

            if (empty($verify)) {
                throw new \Exception("Database creation verification failed.");
            }

            $this->logStep("Database successfully created and verified");
        }
    }

    /* ========================================================= */
    /* ================= CONNECTION SWITCH ====================== */
    /* ========================================================= */

    private function switchToSchoolDatabase(string $databaseName): void
    {
        $this->logStep("Switching connection to school database", [
            'database' => $databaseName
        ]);

        Config::set('database.connections.school.database', $databaseName);

        DB::purge('school');
        DB::connection('school')->reconnect();
        DB::setDefaultConnection('school');

        $this->logStep("Connection switched successfully");
    }

    // /* ========================================================= */
    // /* ================= EMAIL HANDLING ========================= */
    // /* ========================================================= */

    private function sendWelcomeEmail(
        string $to,
        $settings,
        string $emailBody
    ): void {

        $subject = 'Welcome to ' . ($settings['system_name'] ?? 'eSchool Saas');

        $this->logStep("Sending welcome email", [
            'to' => $to,
            'subject' => $subject
        ]);

        try {

            Mail::send('schools.email', [
                'subject' => $subject,
                'email' => $to,
                'email_body' => $emailBody
            ], function ($message) use ($to, $subject, $settings) {
                $message->to($to)
                    ->from($settings['mail_username'] ?? 'no-reply@example.com')
                    ->subject($subject);
            });

            $this->logStep("Welcome email sent successfully");

        } catch (Throwable $e) {

            Log::error("Welcome email failed", [
                'school_id' => $this->schoolId,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /* ========================================================= */
    /* ================= HELPER METHODS ========================= */
    /* ========================================================= */

    private function logStep(string $message, array $context = []): void
    {
        Log::info("[SetupSchoolDatabase][SchoolID: {$this->schoolId}] {$message}", $context);
        echo "[SetupSchoolDatabase] {$message}" . PHP_EOL;
    }

    // public function failed(Throwable $exception): void
    // {
    //     Log::critical("SetupSchoolDatabase PERMANENT FAILURE", [
    //         'school_id' => $this->schoolId,
    //         'error' => $exception->getMessage(),
    //         'trace' => $exception->getTraceAsString(),
    //     ]);
    // }

    private function replacePlaceholders($school, $user, $settings, $schoolCode): string
    {
        $template = $settings['email_template_school_registration'] ?? '';

        $placeholders = [
            '{school_admin_name}' => $user->full_name ?? '',
            '{code}' => $schoolCode ?? '',
            '{email}' => $user->email ?? '',
            '{password}' => $user->mobile ?? '', // Assuming mobile is used as initial password?
            '{school_name}' => $school->name ?? '',
            '{super_admin_name}' => $settings['super_admin_name'] ?? 'Super Admin',
            '{support_email}' => $settings['mail_username'] ?? '',
            '{contact}' => $settings['mobile'] ?? '',
            '{system_name}' => $settings['system_name'] ?? 'eSchool Saas',
            '{url}' => url('/'),
        ];

        foreach ($placeholders as $key => $value) {
            $template = str_replace($key, $value, $template);
        }

        return $template;
    }
}