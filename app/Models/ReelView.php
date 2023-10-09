<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelView extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','reel_id'];



    public static function prepareReelViewData($user , $request)
    {
        return [
            'user_id' => $user->id,
            'reel_id' => $request->id,
        ];
    }
}
