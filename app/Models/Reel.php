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



    protected $fillable=[
        'user_id',
        'description',
        'video_path',
        'cover',
        'views',
        'likes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeUsers(Builder $query,$user_id): void
    {
        $query->where('user_id', '=', $user_id);
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
}
