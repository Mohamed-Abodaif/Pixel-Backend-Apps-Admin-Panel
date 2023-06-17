<?php

namespace App\Http\Controllers\WorkSector\SystemConfigurationControllers\DropdownLists;

use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\WorkSector\countries\CountriesResource;
use App\Models\WorkSector\CountryModule\Country;

class CountriesController extends Controller
{

    function list()
    {
        $data = QueryBuilder::for(Country::class)
            ->allowedFilters(['name'])
            ->customOrdering('id', 'asc')
            ->get(['id','name','code']);
        return CountriesResource::collection($data);
    }
}
