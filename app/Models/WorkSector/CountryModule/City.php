<?php

namespace App\Models\WorkSector\CountryModule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Country\Database\factories\CityFactory;
use App\Traits\HasTranslations;

class City extends Model
{

    use SoftDeletes, HasTranslations, HasFactory;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'country_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];



    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    protected static function newFactory()
    {
        return CityFactory::new();
    }
}
