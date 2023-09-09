<?php

namespace Database\Factories;

use App\Models\Reel;
use App\Models\User;
use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommentLike>
 */
class CommentLikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CommentLike::class;
    
    public function definition()
    {
        return [
            'comment_id' => function () {
                return Comment::factory()->create()->id;
            },
            'reel_id' => function () {
                return Reel::factory()->create()->id;
            },
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'like_type' => 'like', // Default like type
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
