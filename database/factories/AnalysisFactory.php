<?php

namespace Database\Factories;

use App\Models\Analysis;
use App\Models\Customer;
use App\Models\Prompt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnalysisFactory extends Factory
{
    protected $model = Analysis::class;

    public function definition(): array
    {
        $statuses = ['pending', 'processing', 'completed', 'failed'];
        $types = ['Finance', 'Legal', 'Marketing', 'General', 'Technical'];

        return [
            'customer_id' => Customer::factory(),
            'prompt_id' => Prompt::factory(),
            'user_id' => User::factory(),
            'file_name' => fake()->randomElement([
                'Q4_Financial_Statement.pdf',
                'Employment_Contract_v2.pdf',
                'Market_Research_Analysis.pdf',
                'Annual_Report_2024.pdf',
                'Invoice_#10234.pdf',
            ]),
            'file_path' => fake()->filePath(),
            'file_size' => fake()->numberBetween(100000, 5000000),
            'type' => fake()->randomElement($types),
            'status' => fake()->randomElement($statuses),
            'result' => [
                'entities' => fake()->words(10),
                'total_amount' => fake()->randomFloat(1000, 500000, 2),
                'invoice_date' => fake()->date(),
            ],
            'metadata' => [
                'pages' => fake()->numberBetween(1, 50),
                'ocr_confidence' => fake()->randomFloat(0.85, 0.99, 2),
            ],
            'confidence_score' => fake()->randomFloat(85, 99, 2),
            'processing_time_ms' => fake()->numberBetween(1000, 10000),
        ];
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes) => array_merge($attributes, ['status' => 'completed']));
    }
}
