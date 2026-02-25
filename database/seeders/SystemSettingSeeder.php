<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'school_inquiry' => ['data' => 0, 'type' => 'string'],
            // Add other potentially missing settings here
            'web_maintenance' => ['data' => 0, 'type' => 'string'],
        ];

        foreach ($settings as $name => $setting) {
            SystemSetting::updateOrCreate(['name' => $name], $setting);
        }
    }
}
