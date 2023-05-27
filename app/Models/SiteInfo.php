<?php

namespace App\Models;

use App\Models\Client ;
use App\Traits\Calculations;
use App\Traits\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteInfo extends BaseModel
{
    use HasFactory, Calculations,SoftDeletes; //Status

    protected $table="sites_info";
   

    protected $dates = array('created_at','updated_at');
    
    protected $fillable =[
        'client_site_id',
        'contact_name',
        'job_role',
        'contact_email',
        'contact_phone',
       ];

   

    public function clientSite()
    {
        return $this->belongsTo(ClientSite::class,'client_site_id');
    }
    

}
