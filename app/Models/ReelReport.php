<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReelReport extends Model
{
    use HasFactory;
    protected $fillable = [

        'reel_id',
        'user_id',
        'report',
    ];


    public static function rule()
    {
        return [
            'id' => 'required|integer|exists:reels,id',
            'report'=> 'required|string',
        ];
    }

    public static function prepareReelReportData($user,$request) : array {
        return [
            'reel_id'=>$request->id,
            'user_id'=>$user->id,
            'report'=>$request->report,
        ];}
}
