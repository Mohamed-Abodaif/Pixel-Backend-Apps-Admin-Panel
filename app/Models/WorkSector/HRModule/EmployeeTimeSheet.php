<?php

namespace App\Models\WorkSector\HRModule;


use App\Models\BaseModel;
use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SystemConfigurationModels\TimeSheetSubCategory;

class EmployeeTimeSheet extends BaseModel
{
    use HasFactory, Calculations;

    protected $table = "employees_timesheets";

    protected $casts = [
        'client_id' => 'integer',
        'client_po_id' => 'integer',
        'country_id' => 'integer',
        'night_shift' => 'integer',
        'timesheet_sc_id' => 'integer',
        'user_id' => 'integer',
        'vendor_id' => 'integer',
        'vendor_po_id' => 'integer',
    ];

    protected $fillable = [
        'client_id',
        'client_po_id',
        'timesheet_sc_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'country_id',
        'vendor_id',
        'user_id',
        'vendor_po_id',
        'description',
        'location',
        'night_shift'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->select('name');
    }
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id')->select('name', 'id');
    }

    public function clientOrder(): BelongsTo
    {
        return $this->belongsTo(ClientOrder::class, 'client_po_id')->select('order_name', 'id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id')->select('id', 'name');
    }

    public function vendorOrder(): BelongsTo
    {
        return $this->belongsTo(VendorOrder::class, 'vendor_po_id')->select('id', 'order_name');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id')->select('name', 'id');
    }

    public function subCategoryTimesheet(): BelongsTo
    {
        return $this->belongsTo(TimeSheetSubCategory::class, 'timesheet_sc_id');
    }
}
