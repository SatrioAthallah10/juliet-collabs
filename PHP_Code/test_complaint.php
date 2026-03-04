<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $c = \App\Models\Complaint::create([
        'user_name' => 'nama saya satrio athallah',
        'contact_info' => 'test@email.com',
        'contact_type' => 'email',
        'message' => 'halo, saya terkendala tagihan email saya: test@email.com',
        'category' => 'billing',
        'status' => 'new',
        'ip_address' => '127.0.0.1'
    ]);
    echo "SUCCESS_CREATED_" . $c->id;
}
catch (\Exception $e) {
    echo "ERROR_THROWN: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
