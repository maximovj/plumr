<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'file_path', 'mime_type', 'title', 'slug',
        'description', 'tags', 'visibility'
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function albums()
    {
        return $this->belongsToMany(Album::class, 'album_media')->withTimestamps();
    }

    // Método para saber si es imagen (para portada)
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
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
}
