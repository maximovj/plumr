<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'file_path', 'mime_type', 'title', 'slug',
        'description', 'tags', 'visibility'
    ];

    protected $appends = [
        'file_path_url',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    protected static function booted()
    {
        // Crear un observer para eliminar el archivo desde storage
        static::deleting(function ($media) {
            if ($media->file_path && Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
        });
    }

    public function getFilePathUrlAttribute()
    {
        $cover_default = 'media/media_default.png';
        $exists = Storage::disk('public')->exists($this->file_path);
        if ($this->file_path && $exists) {
            return asset('storage/'.$this->file_path); // archivo desde /storage
        }

        return asset('storage/'.$cover_default); // archivo por defecto
    }

    // Ruta amigable
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

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
