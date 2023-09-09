<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\FootballCoach;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FootballCoach>
 */
class FootballCoachFactory extends Factory
{

    protected $model = FootballCoach::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        return [
            'user_id' =>  $user->id,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'age' => $this->faker->numberBetween(18, 40),
            'jop_title' => $this->faker->numberBetween(0, 60),
            'gender' => $this->faker->randomElement(['male', 'female']),
            // 'possibility_travel' => $this->faker->randomElement(['yes','no']),
            'phone_number' => $this->faker->phoneNumber,
            'whatsapp_number' => $this->faker->phoneNumber,
            'cv' => $this->faker->url,
        ];
    }
}
