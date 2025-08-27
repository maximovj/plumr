<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'folder',
        'cover', 'visibility', 'tags'
    ];

    protected $appends = [
        'cover_url',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function getCoverUrlAttribute()
    {
        $cover_default = 'albums/cover/cover_default.png';
        $exists = Storage::disk('public')->exists($this->cover);
        if ($this->cover && $exists) {
            return asset('storage/'.$this->cover); // portada desde /storage
        }

        return asset('storage/'.$cover_default); // portada por defecto
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'album_media')->withTimestamps();
    }

    // Métodos de visibilidad
    public function isPublic(): bool
    {
        return $this->visibility === 'public';
    }

    public function isPrivate(): bool
    {
        return $this->visibility === 'private';
    }

    public function isFollowersOnly(): bool
    {
        return $this->visibility === 'followers_only';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
