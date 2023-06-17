<?php

namespace App\Models\WorkSector\CountryModule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Country\Database\factories\CountryFactory;
use App\Traits\HasTranslations;

class Country extends Model
{

    use SoftDeletes, HasFactory;


    protected $fillable = [
        'name','code'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function cities()
    {
        return $this->hasMany(City::class);
    }

    public function areas()
    {
        return $this->hasManyThrough(Area::class, City::class);
    }

    protected static function newFactory()
    {
        return CountryFactory::new();
    }
}