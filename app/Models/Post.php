<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment (User Input Only)
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'user_id',
        'category_id',
        'content',
        'image_url',
        'video_url',
        'video_provider',
        'slug',
        'status',
        'published_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casting
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'published_at'   => 'datetime',
        'views_count'    => 'integer',
        'likes_count'    => 'integer',
        'comments_count' => 'integer',
        'saves_count'    => 'integer',
        'shares_count'   => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Author (Admin/User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Category (nullable)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Interactions (Like, Comment, Save, Share)
    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    // Views history
    public function views()
    {
        return $this->hasMany(View::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes (Recommended)
    |--------------------------------------------------------------------------
    */

    // Only published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /*
    |--------------------------------------------------------------------------
    | Presentation Helpers
    |--------------------------------------------------------------------------
    */

    public function getDisplayTitleAttribute(): string
    {
        return Str::limit(strip_tags($this->content ?: $this->video_url ?: 'Media post'), 70);
    }

    public function getImageSrcAttribute(): ?string
    {
        if (! $this->image_url) {
            return null;
        }

        if (Str::startsWith($this->image_url, ['http://', 'https://', '/'])) {
            return $this->image_url;
        }

        if (Str::startsWith($this->image_url, 'storage/')) {
            return asset($this->image_url);
        }

        if (Storage::disk('public')->exists($this->image_url)) {
            return asset('storage/' . ltrim($this->image_url, '/'));
        }

        return asset($this->image_url);
    }

    public function getVideoSrcAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        if (Str::startsWith($this->video_url, ['http://', 'https://', '/'])) {
            return $this->video_url;
        }

        return asset($this->video_url);
    }

    public function getVideoEmbedUrlAttribute(): ?string
    {
        if (! $this->video_url || ! Str::startsWith($this->video_url, ['http://', 'https://'])) {
            return null;
        }

        if (Str::contains($this->video_url, 'youtube.com/watch?v=')) {
            return Str::replace('watch?v=', 'embed/', $this->video_url);
        }

        if (Str::contains($this->video_url, 'youtu.be/')) {
            return 'https://www.youtube.com/embed/' . Str::after($this->video_url, 'youtu.be/');
        }

        if (Str::contains($this->video_url, 'vimeo.com/')) {
            return 'https://player.vimeo.com/video/' . Str::afterLast($this->video_url, '/');
        }

        return null;
    }

    public function getHasVideoFileAttribute(): bool
    {
        return (bool) $this->video_url
            && ! $this->video_embed_url
            && Str::endsWith(Str::lower(parse_url($this->video_url, PHP_URL_PATH) ?: $this->video_url), ['.mp4', '.webm', '.ogg']);
    }
}
