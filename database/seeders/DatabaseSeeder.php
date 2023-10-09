<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Reel;
use App\Models\User;
use App\Models\FootballPe;
use App\Models\FootballCoach;
use App\Models\FootballPlayer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // FootballPlayer::factory(100)->create();
        // FootballCoach::factory(50)->create();
        // FootballPe::factory(50)->create();
        // Reel::factory(300)->create();
        // Comment::factory(250)->create();
        User::factory(100)->create();
        // CommentLike::factory(10)->create();
    }
}
