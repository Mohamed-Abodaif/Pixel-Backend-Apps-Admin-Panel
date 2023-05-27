<?php

namespace App\Http\Controllers\WorkSector\CompanyModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkSector\CompanyModule\PackageRequest;
use App\Models\WorkSector\CompanyModule\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    function store(PackageRequest $request)
    {
        $package = Package::create($request->all());
        return response()->json([
            "data" => $package
        ]);
    }

    function update(PackageRequest $request)
    {
        $package = Package::create($request->all());
        return response()->json([
            "data" => $package
        ]);
    }
}
