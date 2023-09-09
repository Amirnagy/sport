<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'reel_id',
        'user_id',
        'report',
    ];


    public static function rule()
    {
        return [
            'comment_id' => 'required|integer|exists:comments,id',
            'reel_id' => 'required|integer|exists:reels,id',
            'report'=> 'required|string',
        ];
    }
}
