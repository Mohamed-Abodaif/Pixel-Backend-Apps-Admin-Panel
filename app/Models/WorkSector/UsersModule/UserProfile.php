<?php

namespace App\Models\WorkSector\UsersModule;

use App\Models\BaseModel;
use App\Models\WorkSector\Country\Country;
use App\Models\WorkSector\UsersModule\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends BaseModel
{
    use HasFactory;

    protected $table = "user_profile";
    protected $fillable = [
        'country_id',
        'avatar',
        'gender',
        'national_id_number',
        'national_id_files',
        'passport_number',
        'passport_files',
    ];

    public $timestamps = false;
    protected $primaryKey = "user_id";
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'national_id_files' => 'array',
        'passport_files' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, "country_id", "id")->select("name");
    }
}
