<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'posts';

    protected $guarded = ['id'];

    protected $fillable = [
        'url_access',
        'title',
        'content',
        'links',
        'descripcion',
        'status',
        'tags',
    ];

    protected $casts = [
        'status' => 'array',
        'tags' => 'array',
        'links' => 'array',
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    // Ruta amigable
    public function getRouteKeyName()
    {
        return 'url_access';
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_posts');
    }

    public function author()
    {
        return $this->belongsToMany(User::class, 'users_posts')->with('profile');
    }

}
