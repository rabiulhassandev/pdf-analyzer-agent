<?php

namespace Database\Seeders;

use App\Models\Analysis;
use App\Models\Customer;
use App\Models\Prompt;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@pdfanalyzer.com',
            'password' => bcrypt('password'),
        ]);

        Customer::factory()->count(5)->create();

        $customers = Customer::all();
        $promptNames = [
            'Purchase Order Analysis',
            'Invoice Extraction',
            'Contract Review',
            'Technical Spec Summary',
            'Financial Report Parser',
        ];

        foreach ($promptNames as $index => $name) {
            Prompt::factory()->create([
                'customer_id' => $customers[$index % $customers->count()]->id,
                'name' => $name,
                'slug' => Str::slug($name).'-'.rand(1000, 9999),
                'content' => "Analyze the uploaded document for {$name} purposes. Extract key information including dates, amounts, parties involved, and any special terms or conditions.",
            ]);
        }

        $prompts = Prompt::all();

        foreach ($customers as $customer) {
            Analysis::factory()->count(rand(2, 5))->create([
                'customer_id' => $customer->id,
                'prompt_id' => $prompts->random()->id,
            ]);
        }
    }
}
