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
        $photoDefault = 'img/users/profiles/photo/user_default.png';

        if ($this->photo && Storage::disk('public')->exists($this->photo)) {
            return asset('storage/' . $this->photo);
        }

        return asset($photoDefault);
    }

    public function getCoverUrlAttribute()
    {
        $coverDefault = 'img/users/profiles/cover/cover_default.jpg';

        if ($this->cover && Storage::disk('public')->exists($this->cover)) {
            return asset('storage/' . $this->cover);
        }

        return asset($coverDefault);
    }

}
