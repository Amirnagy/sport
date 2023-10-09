<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReelLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'reel_id',
        'user_id',
        'like_type'
    ];




    public static function prepareReelLikeData($user , $request) : array
    {
        return
        [
            'reel_id' => $request->id,
            'user_id' => $user->id,
            'like_type'=>$request->like_type
        ];
    }

    public function scopeUserOfReel(Builder $query,$user,$request): void
    {
        $query->where('reel_id',$request->id)->where('user_id' , $user->id);
    }
}
