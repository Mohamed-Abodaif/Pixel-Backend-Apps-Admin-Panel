<?php

namespace App\Models\WorkSector\ClientsModule;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientContact extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_address_id',
        'contact_name',
        'job_role',
        'contact_email',
        'contact_phone'
    ];
    function clientAddress()
    {
        return $this->belongsTo(ClientAddress::class);
    }
}
