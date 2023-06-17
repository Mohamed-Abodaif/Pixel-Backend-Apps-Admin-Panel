<?php

namespace App\Models\WorkSector\VendorsModule;

use App\Interfaces\HasStorageFolder;
use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends BaseModel  implements HasStorageFolder
{
    use HasFactory, Calculations, SoftDeletes;

    protected $table = "vendors";
    const ROUTE_PARAMETER_NAME = "vendorOrder";

    protected $fillable = [
        'name',
        'billing_address',
        'type',
        'country_id',
        'registration_no',
        'registration_no_attachment',
        'taxes_no',
        'taxes_no_attachment',
        'exemption_attachment',
        'sales_taxes_attachment',
        'logo',
        'notes',
    ];

    protected $casts = [
        'registration_no_attachment' => 'array',
        'taxes_no_attachment' => 'array',
        'exemption_attachment' => 'array',
        'sales_taxes_attachment' => 'array',
        'status' => 'boolean'
    ];

    public function getDocumentsStorageFolderName() : string
    {
        return "VendorsFiles/" . $this->name;
    }
}
