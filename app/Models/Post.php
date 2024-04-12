<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'path'];
    protected $appends = ['like_counts'];

    protected $timestamp = false;

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    protected function likeCounts(): Attribute  
    {
        return Attribute::make(
           get: fn () => count($this->likes),
       );
    }

    protected function path(): Attribute  
    {
        return Attribute::make(
           get: fn () => asset('images/posts/' . $this->attributes['path']),
       );
    }
}
