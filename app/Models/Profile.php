<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public $appends = [];

    public $casts = [
        'network_social' => 'array',
    ];

    public $dates = [
        'created_at',
        'updated_at',
    ];

}
