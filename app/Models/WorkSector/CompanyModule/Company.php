<?php

namespace App\Models\WorkSector\CompanyModule;

use App\Models\BaseModel;
use function PHPSTORM_META\map;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\WorkSector\Country\Country;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\WorkSector\SystemConfigurationModels\CompanySector;
use App\Notifications\UserNotifications\EmailVerificationNotifications\VerificationEmailNotification;
use OwenIt\Auditing\Contracts\Auditable;

class Company extends Authenticatable implements Auditable
{
    use HasFactory, SoftDeletes, HasApiTokens, Notifiable, \OwenIt\Auditing\Auditable;

    protected $table = "companies";

    public $fillable = [
        'name',
        'company_sector',
        'logo',
        // 'company_size',
        'country_id',
        'company_tax_type',
        'employees_no',
        'branches_no',
        'package_status',
        'first_name',
        'last_name',
        'is_active',
        'package_id',
        'dates',
        'admin_email',
        'nationality',
        'billing_address',
        'registration_status',
    ];

    public $exceptData = [
        'package_status',
        'admin_email',
        'is_active',
        'package_id',
        'dates',
        'registration_status',
        'deleted_at',
        'email_verified_at',
    ];
    protected $casts = [
        "is_active" => "boolean"
    ];
    public function isActive($value)
    {
        return $value ? "active" : "not active";
    }
    public function country()
    {
        return $this->belongsTo(Country::class)->select('id', 'code', 'name');
    }
}
