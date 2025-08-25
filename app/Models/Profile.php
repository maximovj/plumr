<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    public $table = 'profiles';

    public $guarded = ['id'];

    public $fillable = [
        'user_id',
        'birthday',
        'fullname',
        'sex',
        'number_phone',
        'cover',
        'photo',
        'country',
        'address',
        'city',
        'status',
        'bio',
        'network_social',
    ];

    protected $appends = [
        'photo_url',
        'cover_url',
    ];

    public $casts = [
        'network_social' => 'array',
    ];

    public $dates = [
        'birthday',
        'created_at',
        'updated_at',
    ];

    public function getPhotoUrlAttribute()
    {
        $photo_default = 'users/profiles/photo/user_default.png';
        $photo = $this->photo;
        $exists = Storage::disk('public')->exists($photo);
        if ($photo && $exists) {
            return asset('storage/'.$photo); // portada desde /storage
        }

        return asset('storage/'.$photo_default); // portada por defecto
    }

    public function getCoverUrlAttribute()
    {
        $photo_default = 'users/profiles/cover/cover_default.jpg';
        $cover = $this->cover;
        $exists = Storage::disk('public')->exists($cover);
        if ($cover && $exists) {
            return asset('storage/'.$cover); // portada desde /storage
        }

        return asset('storage/'.$photo_default); // portada por defecto
    }

}
