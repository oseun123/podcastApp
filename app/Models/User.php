<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'reset_token',
        'reset_expires_at'
    ];



    protected $hidden = [
        'password',
        'remember_token',
        'reset_token',
        'reset_expires_at',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'reset_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
