<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;
    protected $guard_name = 'sanctum';

    protected $fillable = [
        'name',
        'email',
        'password',
        'dob',
        'profile_image',
        'description',
        'is_active',
        'otp',
        'otp_expires_at',
        'is_otp_verified'
    ];

    protected $hidden = ['password', 'remember_token'];

}
// $user->roles;             // Get roles
// $user->permissions;       // Get permissions
// $user->getAllPermissions(); // Get all permissions (via role or direct)
