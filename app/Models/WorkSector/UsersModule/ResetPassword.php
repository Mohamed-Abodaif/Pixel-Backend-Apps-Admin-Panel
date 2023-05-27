<?php

namespace App\Models\WorkSector\UsersModule;

use App\Models\BaseModel;

class ResetPassword extends BaseModel
{

    protected $table = 'password_resets';

    protected $fillable = ['email', 'token',];

    public function getLink($FrontEndUrl): string
    {
        return urldecode($FrontEndUrl . '/reset-password?token=' . $this->token);
    }

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function scopeRestPasswordWithToken($query, $token)
    {
        return $query->where('token', $token);
    }
}
