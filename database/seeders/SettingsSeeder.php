<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Settings::create([
            'model_provider' => 'gpt',
            'model_version' => 'gpt-4o',
            'system_instructions' => 'You are a helpful AI assistant specialized in PDF document analysis.',
        ]);
    }
}
