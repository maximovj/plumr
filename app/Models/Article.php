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
        'is_publish' => 'bool',
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
        'cover_url',
        'tags_str',
    ];

    public function getCoverUrlAttribute()
    {
        if ($this->cover && Storage::disk('public')->exists($this->cover)) {
            return asset('storage/' . $this->cover);
        }

        return asset('img/articles/cover/cover-default.jpg');
    }

    public function getTagsStrAttribute()
    {
        return implode(',', $this->tags);
    }

    // Ruta amigable
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Esta relación será usado por componente livewire `ConfirmDeleteModelClass`
    public function owner()
    {
        return $this->belongsToMany(User::class, 'articles_users');
    }

}
