<?php

namespace App\Services\CoreServices\CRUDServices\FilesOperationsHandlers;

use App\Services\CoreServices\CRUDServices\Interfaces\MustUploadModelFiles;
use Illuminate\Database\Eloquent\Model;

abstract class FilesHandler
{

    protected function __construct(){ }

    static protected ?FilesHandler $instance = null ;

    static public function singleton() : FilesHandler
    {
        if(!static::$instance){static::$instance = new static();}
        return static::$instance;
    }

    static public function MustUploadModelFiles(Model $model) : bool
    {
        return $model instanceof MustUploadModelFiles;
    }
}
