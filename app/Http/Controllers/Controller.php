<?php

namespace App\Http\Controllers;
use App\Http\Requests\ImportFile;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function paginateCollection($items, $perPage = 15, $page = null, $options = [],$type=null)
{
	$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

	$items = $items instanceof Collection ? $items : Collection::make($items);

    $lap = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    if($type)
    {
        return [
            'data'=>[
                'list'=>[
                'current_page' => $lap->currentPage(),
                'data' => $lap ->values(),
                'first_page_url' => $lap ->url(1),
                'from' => $lap->firstItem(),
                'last_page' => $lap->lastPage(),
                'last_page_url' => $lap->url($lap->lastPage()),
                'next_page_url' => $lap->nextPageUrl(),
                'per_page' => $lap->perPage(),
                'prev_page_url' => $lap->previousPageUrl(),
                'to' => $lap->lastItem(),
                'total' => $lap->total()
                ],'statistics'=>[]
            ]
        ];
        
    }
    return [
        'data'=>[
            'list'=>[
            'current_page' => $lap->currentPage(),
            'data' => $lap ->values(),
            'first_page_url' => $lap ->url(1),
            'from' => $lap->firstItem(),
            'last_page' => $lap->lastPage(),
            'last_page_url' => $lap->url($lap->lastPage()),
            'next_page_url' => $lap->nextPageUrl(),
            'per_page' => $lap->perPage(),
            'prev_page_url' => $lap->previousPageUrl(),
            'to' => $lap->lastItem(),
            'total' => $lap->total()
            ]
        ]
    ];
}

public function statistics($model, ?Request $request, string $context, array $filters = [])
{
    if (is_subclass_of($model, Model::class)) {
        $period = $request->filter["period_type"] ?? null;
        $from = $request->filter['from_date'] ?? null;
        $to = $request->filter['to_date'] ?? null;
        //
        return $model::getCalculation($context, $period, $from, $to, $filters);
    }

    return [];
    //
    //$modelObj = $this->getModel($model);
}
/* private function getModel($model)
{
    if (is_subclass_of($subject, Model::class)) {
        $subject = $subject::query();
    }

    $Model = 'App\\Models\\'.$model;
    return $Model;
} */
}
