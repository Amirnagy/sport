<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentSave extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'reel_id',
        'comment_id',
    ];

    public static function rule()
    {
        return [
            'comment_id' => 'required|integer|exists:comments,id',
            'reel_id' => 'required|integer|exists:reels,id',
        ];
    }


}
