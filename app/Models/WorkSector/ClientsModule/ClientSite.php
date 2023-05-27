<?php

namespace App\Models\WorkSector\ClientsModule;

use App\Traits\Status;
use App\Models\Client;
use App\Models\BaseModel;

use App\Traits\Calculations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientSite extends BaseModel
{
    use HasFactory, Calculations, SoftDeletes; //Status

    protected $table = "client_sites";


    protected $dates = array('created_at', 'updated_at');

    protected $fillable = [
        'client_id',
        'name',
    ];



    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
