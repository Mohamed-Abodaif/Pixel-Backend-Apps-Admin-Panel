<?php
namespace App\Models;

use App\Interfaces\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{

    public static $snakeAttributes = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->generateUUID();

    }

    protected function generateUUID() : void
    {
        if($this instanceof HasUUID)
        {
            if($this->hashed_id == null)
            {
                $this->setAttribute("hashed_id" ,Str::uuid()->toString() );
            }
        }
    }

}
