<?php

namespace App\Models\SystemAdminPanel;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'email',
        'name',
        'password',
        'mobile',
        'is_active',
        'role_id',
        'gender',
        'avatar'
    ];
    protected $hidden = [
        'password'
    ];
}
