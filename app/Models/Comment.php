<?php

namespace App\Models;

use App\Models\Reel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'reel_id',
        'parent_id',
        'content',
        'likes'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reel(): BelongsTo
    {
        return $this->belongsTo(Reel::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }



    public static function rules()
    {
        return
        [
            'idreel' => 'required|integer|exists:reels,id',
            'content' => 'required|string',
        ];
    }

    public static function updateRules()
    {
        return
        [
            'idcomment' => 'required|integer|exists:comments,id',
            'idreel' => 'required|integer|exists:comments,reel_id',
            'content' => 'required|string',
        ];
    }



    public static function replayRules()
    {
        return
        [
            'id' => 'required|integer|exists:comments,id',
            'reel_id' => 'required|integer|exists:comments,reel_id',
        ];
    }
    public static function createReplayRules()
    {
        return
        [
            'id' => 'required|integer|exists:comments,id',
            'reel_id' => 'required|integer|exists:comments,reel_id',
            'content' => 'required|string',
        ];
    }

}
