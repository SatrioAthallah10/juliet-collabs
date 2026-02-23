<?php
$logFile = 'c:\\Users\\satri\\Downloads\\eSchool-Saas-V1.8.2\\PHP_Code\\storage\\logs\\laravel.log';
$lines = file($logFile);
$count = count($lines);
$found = false;
// Search backwards for the FAILURE log
for ($i = $count - 1; $i >= 0; $i--) {
    if (strpos($lines[$i], 'SetupSchoolDatabase FAILED') !== false) {
        // found failure log
        // execute context print
        $start = max(0, $i - 5);
        // Print until we see the end of the error block, usually a few lines down
        // Log::error context is usually printed as JSON on the same line or next few lines
        // Let's print 50 lines after to be safe and catch the trace
        $end = min($count - 1, $i + 50);

        echo "FOUND FAILURE AT LINE $i:\n";
        for ($j = $start; $j <= $end; $j++) {
            echo $lines[$j];
        }
        $found = true;
        break;
    }
}

if (!$found) {
    echo "No matching failure log entry found in the last scan.";
}
