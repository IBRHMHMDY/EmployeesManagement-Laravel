<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'work_start_time', 'value' => '09:00:00'],
            ['key' => 'late_25_minutes', 'value' => 'half_day'],
            ['key' => 'late_45_minutes', 'value' => 'full_day'],
            ['key' => 'absent_penalty', 'value' => '3_days = full_day'],
            ['key' => 'overtime_rate', 'value' => '1.5'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }
    }
}
