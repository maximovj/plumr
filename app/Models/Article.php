<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        'slug',
        'cover',
        'author',
        'profession',
        'title',
        'subtitle',
        'summary',
        'header',
        'content',
        'footer',
        'tags',
        'network_social',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_title',
        'og_description',
        'og_image',
        'is_publish',
        'published_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'tags' => 'array',
        'network_social' => 'array',
    ];

    protected $dates = [
        'published_at',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'cover_url'
    ];

    public function getCoverUrlAttribute()
    {
        $path = "articles/cover/";
        $exists = Storage::disk('public')->exists($path.$this->cover);
        if ($this->cover && $exists) {
            return asset($path.$this->cover);
        }

        return asset($path.'cover-default.jpg'); // portada por defecto
    }

    // Ruta amigable
    public function getRouteKeyName()
    {
        return 'slug';
    }

}
