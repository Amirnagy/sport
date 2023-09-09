<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Comment;
use App\Models\Reel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $uniqueUserId = User::inRandomOrder()->first()->id;
        $uniqueReelId = Reel::inRandomOrder()->first()->id;
        $uniquecommentId = Comment::inRandomOrder()->first()->id;
            return [
                'user_id' => $uniqueUserId,
                'reel_id' => $uniqueReelId,
                'parent_id' => $uniquecommentId, // By default, set it to null
                'content' => $this->faker->text,
                'likes' => $this->faker->numberBetween(0, 50000), // You can customize the range as needed
                'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ];

    }
}
