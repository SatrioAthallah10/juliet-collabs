<?php
$url = "http://127.0.0.1:8000/api/external-api/students/store";

$data = [
    'school_id' => 1,
    'first_name' => 'John',
    'last_name' => 'Doe',
    'mobile' => '1234567890',
    'dob' => '2010-01-01',
    'class_id' => 1,
    'admission_no' => 'ADM' . rand(1000, 9999),
    'admission_date' => '2024-01-01',
    'session_year_id' => 1,
    'guardian_email' => 'guardian' . rand(1000, 9999) . '@example.com',
    'guardian_first_name' => 'Jane',
    'guardian_last_name' => 'Doe',
    'guardian_mobile' => '0987654321',
    'guardian_gender' => 'female',
    'gender' => 'male',
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
]);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpcode . "\n";
file_put_contents('test_output.json', $response);
echo "Response saved to test_output.json\n";
