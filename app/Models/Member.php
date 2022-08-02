<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Member extends  Authenticatable implements MustVerifyEmail
{
    use HasFactory;

    protected $fillable = [
        'email',
        'gender',
        'first_name',
        'surname',
        'password',
    ];
}
