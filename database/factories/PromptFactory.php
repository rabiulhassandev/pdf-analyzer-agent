<?php

namespace Database\Factories;

use App\Models\Prompt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PromptFactory extends Factory
{
    protected $model = Prompt::class;

    public function definition(): array
    {
        $types = ['entity_extraction', 'risk_assessment', 'summary', 'custom'];
        $statuses = ['draft', 'active', 'archived'];
        $models = ['gpt-4o', 'gpt-4-turbo', 'claude-3.5-sonnet', 'claude-3-opus'];

        return [
            'user_id' => User::factory(),
            'name' => fake()->randomElement([
                'Invoice Entity Extraction v2.1',
                'Contract Risk Assessment',
                'Technical Specification Summary',
                'Financial Document Analyzer',
                'Legal Document Parser',
            ]),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['name']);
            },
            'description' => fake()->randomElement([
                'Extract key entities from invoices including line items, totals, and payment terms.',
                'Analyze contracts for potential risks, liabilities, and compliance issues.',
                'Generate concise summaries of technical documents highlighting key specifications.',
            ]),
            'content' => fake()->paragraphs(3, true),
            'type' => fake()->randomElement($types),
            'status' => fake()->randomElement($statuses),
            'model' => fake()->randomElement($models),
            'accuracy_score' => fake()->randomFloat(85, 99, 2),
            'usage_count' => fake()->numberBetween(0, 500),
            'parameters' => [
                'temperature' => fake()->randomFloat(0, 1, 2),
                'max_tokens' => fake()->numberBetween(1000, 8000),
            ],
        ];
    }

    public function active(): self
    {
        return $this->state(fn (array $attributes) => array_merge($attributes, ['status' => 'active']));
    }

    public function draft(): self
    {
        return $this->state(fn (array $attributes) => array_merge($attributes, ['status' => 'draft']));
    }
}
