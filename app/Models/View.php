<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'post_id',
        'user_id',
        'ip_address',
        'user_agent',
        'view_date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casting
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'view_date'  => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // View belongs to Post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // View belongs to User (nullable)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (Useful for queries)
    |--------------------------------------------------------------------------
    */

    // Today views
    public function scopeToday($query)
    {
        return $query->whereDate('view_date', now()->toDateString());
    }

    // Specific post
    public function scopeForPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    // Guest views only
    public function scopeGuests($query)
    {
        return $query->whereNull('user_id');
    }

    // Logged-in users only
    public function scopeUsers($query)
    {
        return $query->whereNotNull('user_id');
    }
}
