<?php

namespace App\Models\WorkSector\CountryModule;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Country\Database\factories\AreaFactory;
use App\Traits\HasTranslations;

class Area extends Model
{

    use SoftDeletes, HasTranslations, HasFactory;

    public $translatable = ['name'];


    protected $fillable = [
        'name',
        'city_id',
    ];


    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    protected static function newFactory()
    {
        return AreaFactory::new();
    }
}
