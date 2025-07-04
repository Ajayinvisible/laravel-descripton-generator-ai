<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'meta_keywords',
        'description',
        'image',
    ];
}
