<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'problem_description' => $this->faker->sentence(),
            'possible_cause'      => null,
            'status'              => 'pending',
        ];
    }
}
