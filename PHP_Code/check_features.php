<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$school = App\Models\School::orderBy('id', 'desc')->first();
$output = "School ID: " . $school->id . "\n";

$subs = \DB::table('subscriptions')->where('school_id', $school->id)->get();
$output .= "All Subscriptions:\n";
foreach ($subs as $sub) {
    try {
        $status = App\Models\Subscription::find($sub->id)->status;
    }
    catch (\Exception $e) {
        $status = "Error getting status";
    }
    $output .= "ID: " . $sub->id . " | Start: " . $sub->start_date . " | End: " . $sub->end_date . " | Status: " . $status . " | Package Type: " . $sub->package_type . "\n";
}

$active = app(App\Services\SubscriptionService::class)->active_subscription($school->id);
if ($active) {
    $output .= "Active found: " . $active->id . "\n";
}
else {
    $output .= "No active found by active_subscription() Service.\n";
}

file_put_contents('subs_output.txt', $output);
echo "Written to subs_output.txt\n";
