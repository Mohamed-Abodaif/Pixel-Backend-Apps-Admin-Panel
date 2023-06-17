<?php

namespace App\Models\WorkSector\ClientsModule;

use App\Services\CoreServices\CRUDServices\Interfaces\OwnsRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model implements OwnsRelationships
{
    use HasFactory;
    protected $fillable = [
        'site_name',
        'client_id',
        'country_id',
        'city_id',
        'floor',
        'residential_block',
        'street',
        'apartment_number'
    ];

    public function getOwnedRelationshipNames(): array
    {
        return ["contacts" ];
    }

    function client()
    {
        return $this->belongsTo(Client::class,"client_id");
    }

    function contacts()
    {
        return $this->hasMany(ClientContact::class);
    }
}
