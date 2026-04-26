<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Services\CloudinaryService;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'image',
        'images',
        'image_public_ids',
        'caption',
    ];

    protected $casts = [
        'images' => 'array', // Cast to array for JSON storage
        'image_public_ids' => 'array', // Cast to array for Cloudinary public IDs
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    /**
     * Delete images from Cloudinary when post is deleted
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($post) {
            if (!empty($post->image_public_ids) && is_array($post->image_public_ids)) {
                $cloudinary = app(CloudinaryService::class);
                $cloudinary->deleteMultiple($post->image_public_ids);
            }
        });
    }
}
