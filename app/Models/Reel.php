<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reel extends Model
{
    use HasFactory , SoftDeletes;



    protected $fillable=['user_id','description','video_path','cover','views','likes'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }







    public static function rules() : array
    {
        return [
            'description' => 'sometimes|string',
            'video' => 'required|mimetypes:video/mp4,video/quicktime,video/mpeg,video/3gpp|max:100480',
            'cover' => 'sometimes|mimetypes:image/jpeg,image/png,image/gif|max:20480',
        ];
    }


    public static function updateRules() : array
    {
        return [
            'description' => 'required|string',
            'video' => 'sometimes|mimetypes:video/mp4,video/quicktime,video/mpeg,video/3gpp|max:100480',
            'cover' => 'sometimes|mimetypes:image/jpeg,image/png,image/gif|max:20480',
        ];
    }




    public static function prepareReelData($user ,array $data,string $videopath, $pathcover = null){
        return [
            'user_id' => $user->id,
            'description' => $data['description'],
            'video_path' => $videopath,
            'cover' => $pathcover ?? null,
        ];
    }



    public function scopeUserOfReel(Builder $query,$user,$request): void
    {
        $query->where('user_id', $user->id)->where('id',$request->id);
    }
}
