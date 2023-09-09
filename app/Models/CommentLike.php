<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    use HasFactory;


    protected $fillable = [
        'comment_id',
        'reel_id',
        'user_id',
        'like_type',
    ];


    public static function rule()
    {
        return [
            'comment_id' => 'required|integer|exists:comments,id',
            'reel_id' => 'required|integer|exists:reels,id',
            'like_type'=> 'required|in:like,love',
        ];
    }


    public static function updaterule()
    {
        return [
            'comment_id' => 'required|integer|exists:comments,id',
            'reel_id' => 'required|integer|exists:reels,id',
        ];
    }
}
