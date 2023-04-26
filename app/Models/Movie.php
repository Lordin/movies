<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Movie extends Model
{
    use HasFactory;

    protected $appends = [
        'buttonStatus',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likes()
    {
        return $this->hasMany(MovieRate::class, 'movie_id', 'id')->where('like', 1);
    }

    public function hates()
    {
        return $this->hasMany(MovieRate::class, 'movie_id', 'id')->where('like', 0);
    }

    public function getButtonStatusAttribute()
    {
        if (!Auth::check()) {
            return $this->buttonStatus = '';
        }
        if ($this->likes()->where('user_id', Auth::id())->count()) {
            return $this->buttonStatus = 'like';
        }
        if ($this->hates()->where('user_id', Auth::id())->count()) {
            return $this->buttonStatus = 'hate';
        }

        return $this->buttonStatus = '';
    }
}
