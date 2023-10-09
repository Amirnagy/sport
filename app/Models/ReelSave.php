<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReelSave extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','reel_id'];

    public function scopeUserOfReel(Builder $query,$user,$request): void
    {
        $query->where('reel_id',$request->id)->where('user_id' , $user->id);
    }


    public static function prepareReelSaveData($user , $request)
    {
        return [
            'user_id' => $user->id,
            'reel_id' => $request->id,
        ];
    }
}
