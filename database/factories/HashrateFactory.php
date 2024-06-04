<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hashrate>
 */
class HashrateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'worker_id' => strval(rand(1, 2147483647)),
            'worker_name' => $this->faker->name,
            'date' => $this->faker->date(),
            'hashrate' => strval($this->faker->randomFloat(2, 1, 5)),
            'reject' => strval($this->faker->randomFloat(4, 0, 100)),
        ];
    }
}
