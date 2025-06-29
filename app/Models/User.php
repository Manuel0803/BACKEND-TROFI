<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function job()
    
    {
        return $this->belongsTo(Trabajo::class, 'id_job');
    }

    public function reviewsGiven()
{
    return $this->hasMany(Review::class, 'reviewer_id');
}

public function reviewsReceived()
{
    return $this->hasMany(Review::class, 'reviewed_id');
}

    protected $fillable = [
        'name',
        'email',
        'password',
        'phoneNumber',
        'userDescription',
        'imageProfile',
        'dni',
        'location',
        'is_worker',
        'id_job',
        'job_description',
        'job_images',
        'score',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'job_images' => 'array',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


}