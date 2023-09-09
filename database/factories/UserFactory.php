<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $email = $this->faker->safeEmail;
        $username = explode('@', $email)[0];
        return [
            'name' => $this->faker->name,
            'email' => $email,
            'username' => $username,
            'phone' => $this->faker->unique()->phoneNumber,
            'bio' => $this->faker->sentence,
            'password' => bcrypt('password'), // You can set a default password here
            'provider_name' => $this->faker->randomElement(['Google', 'Facebook']),
            'provider_id' => $this->faker->uuid,
            'avatar' => $this->faker->imageUrl(),
            'role' => $this->faker->randomElement(['player']),
            'email_verified_at' => now(),
            ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
