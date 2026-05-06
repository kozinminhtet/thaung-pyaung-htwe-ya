<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}