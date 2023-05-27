<?php

namespace App\Models\WorkSector\SystemConfigurationModels;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tender extends BaseModel
{
    use HasFactory, SoftDeletes;
    const ROUTE_PARAMETER_NAME = "tender";
    protected $table = 'tenders';
    protected $fillable = ["name", "status"];
}
