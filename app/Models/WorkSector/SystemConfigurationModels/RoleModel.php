<?php

namespace App\Models\WorkSector\SystemConfigurationModels;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class RoleModel extends Role
{
    use SoftDeletes;

    protected $casts = [
        'disabled' => 'boolean',
        'default' => 'boolean'
    ];

    public function scopeActiveRole()
    {
        return $this->where('disabled', 0);
    }
    public function scopeDefaultRole()
    {
        return $this->where('default', 1);
    }
}
