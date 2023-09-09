<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\FootballPlayer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FootballPlayer>
 */
class FootballPlayerFactory extends Factory
{
    protected $model = FootballPlayer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $uniqueUserId = User::inRandomOrder()->first()->id;
        return [
            'country' => $this->faker->country,
            'user_id' =>  $uniqueUserId,
            'city' => $this->faker->city,
            'age' => $this->faker->numberBetween(18, 40),
            'weight' => $this->faker->randomFloat(2, 50, 100),
            'height' => $this->faker->randomFloat(2, 150, 200),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'skills_level' => $this->faker->randomElement(['beginner', 'professional']),
            'foot_dominant' => $this->faker->randomElement(['right', 'left']),
            'main_position' => $this->faker->randomElement(['goalkeeper', 'defender', 'midfielder', 'forward']),
            // 'possibility_travel' => $this->faker->randomElement(['yes','no']),
            'phone_number' => $this->faker->phoneNumber,
            'whatsapp_number' => $this->faker->phoneNumber,
            'cv' => $this->faker->url,
        ];
    }
}
