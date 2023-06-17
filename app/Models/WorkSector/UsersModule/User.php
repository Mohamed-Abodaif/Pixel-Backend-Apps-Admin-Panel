<?php

namespace App\Models\WorkSector\UsersModule;

use App\Traits\Calculations;

use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Role;
use App\Interfaces\HasStorageFolder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\WorkSector\UsersModule\UserProfile;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\WorkSector\SystemConfigurationModels\Department;
use App\Models\PersonalSector\PersonalTransactions\Outflow\Expense;
use App\Notifications\UserNotifications\EmailVerificationNotifications\VerificationEmailNotification;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{

    use HasApiTokens, HasFactory, Notifiable,  Calculations, SoftDeletes;

    public static $snakeAttributes = false;


    const EMPLOYEE_USER_STATUS = [1, 2];
    const SIGN_UP_STATUS = [0, 3];
    const UserAllowedStatuses = [
        "pending" => 0,
        "active" => 1,
        "inactive" => 2,
        "blocked" => 3
    ];
    const UserAllowedTypes = ["employee", "signup"];

    protected $with = ["role:id,name", "role.permissions:name,id"];
    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        "name",
        'password',
        'first_name',
        'last_name',
        'mobile',
        'employee_id',
        'verification_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    //Relationships part - start
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, "user_id", "id");
    }


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, "role_id", "id");
    }

    public function HasPermission(string $permissionToCheck): bool
    {
        $userPermissions = $this->role?->permissions->pluck("name")->toArray() ?? [];
        return in_array($permissionToCheck, $userPermissions);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class)->select('id', 'name');
    }

    //Relationships part - end

    public function getDocumentsStorageFolderName(): string
    {
        return "users_files/" . str_replace(" ", "_", $this->name) . "_" . $this->id;
    }

    public function scopeActiveEmployees($query)
    {
        $query->whereIn('status', $this::EMPLOYEE_USER_STATUS)->whereNotNull('email_verified_at')->where('user_type', 'employee');
    }

    public function scopeActiveSignup($query)
    {
        $query->whereIn('status', $this::SIGN_UP_STATUS)->whereNotNull('email_verified_at')->where('user_type', 'signup');
    }

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    //    public function retrieveQuietly($options = [])
    //    {
    //        return static::withoutEvents(function () use ($options) {
    //            return $this->get($options);
    //        });
    //        $query->whereIn('status', $this->signupStatus)->where('user_type','signup');
    //    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerificationEmailNotification($this->verification_token));
    }

    public function getVerificationLink(): string
    {
        return urldecode(env("FRONTEND_APP_URL") . '/account-verification?token=' . $this->verification_token);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
