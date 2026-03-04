<?php
use Illuminate\Http\Request;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Request::create('/api/external-api/students/store', 'POST', [
    'school_id' => 1,
    'first_name' => 'API',
    'last_name' => 'Test Student',
    'mobile' => '123456780',
    'guardian_email' => 'guardian@test.com',
    'guardian_first_name' => 'Test',
    'guardian_last_name' => 'Guardian',
    'guardian_mobile' => '098765431',
    'guardian_gender' => 'male',
    'class_id' => 1,
    'session_year_id' => 1,
    'gender' => 'male',
    'dob' => '2010-01-01',
    'admission_no' => 'ADM' . time(),
    'admission_date' => date('Y-m-d'),
]);
$request->headers->set('Accept', 'application/json');

try {
    $response = $app->handle($request);
    $content = json_decode($response->getContent(), true);
    if (isset($content['message'])) {
        echo "Message: " . $content['message'] . "\n";
        echo "File: " . ($content['file'] ?? '') . "\n";
        echo "Line: " . ($content['line'] ?? '') . "\n";
    }
    else {
        echo "Raw Response: " . $response->getContent() . "\n";
    }
}
catch (\Throwable $e) {
    echo "EXCEPTION CAUGHT!\n";
    echo $e->getMessage() . "\n";
}
