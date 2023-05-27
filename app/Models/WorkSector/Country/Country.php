<?php

namespace App\Models\WorkSector\Country;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends BaseModel
{
    use HasFactory;

    protected $table = "countries";

    protected $fillable = [
        "name", "code"
    ];
}
