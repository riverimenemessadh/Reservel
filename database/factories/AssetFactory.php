<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'name'        => $this->faker->words(3, true),
            'type'        => $this->faker->randomElement(['room', 'equipment']),
            'description' => $this->faker->sentence(),
            'status'      => 'available',
            'image'       => null,
        ];
    }
}