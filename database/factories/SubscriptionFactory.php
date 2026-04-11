<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement(['Demo', 'Ultra', 'Professional']),
            'price' => $this->faker->randomElement([20,50,100]),
            'description' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['Monthly', 'Yearly', 'Weekly']),
            'status' => $this->faker->randomElement(['Active']),
        ];
    }
}
