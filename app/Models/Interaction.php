<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interaction extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'user_id',
        'post_id',
        'type',
        'content',
        'parent_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // User (who performed the action)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Post (target post)
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Parent Comment (Reply system)
    public function parent()
    {
        return $this->belongsTo(Interaction::class, 'parent_id');
    }

    // Replies (children comments)
    public function replies()
    {
        return $this->hasMany(Interaction::class, 'parent_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (VERY IMPORTANT)
    |--------------------------------------------------------------------------
    */

    // Only Likes
    public function scopeLikes($query)
    {
        return $query->where('type', 'like');
    }

    // Only Comments
    public function scopeComments($query)
    {
        return $query->where('type', 'comment');
    }

    // Only Saves
    public function scopeSaves($query)
    {
        return $query->where('type', 'save');
    }

    // Only Shares
    public function scopeShares($query)
    {
        return $query->where('type', 'share');
    }
}