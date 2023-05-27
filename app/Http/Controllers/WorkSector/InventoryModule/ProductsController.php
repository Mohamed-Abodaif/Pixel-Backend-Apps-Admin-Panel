<?php

namespace App\Http\Controllers\WorkSector\InventoryModule;

use Illuminate\Http\Request;

use App\Export\ExportBuilder;
use App\Import\ImportBuilder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\SingleResource;
use Rap2hpoutre\FastExcel\SheetCollection;
use App\Models\WorkSector\InventoryModule\Product;
use App\Models\WorkSector\InventoryModule\ProductLogistic;
use App\Models\WorkSector\InventoryModule\ProductSalesPrice;
use App\Models\WorkSector\VendorsModule\ProductVendorsPrice;
use App\Http\Requests\WorkSector\InventoryModule\ProductRequest;

class ProductsController extends Controller
{

    protected $filterable = [
        'product_name',
        'created_at',
        'product_condition',
    ];

    public function __construct()
    {
    }

    public function list(Request $request)
    {
        $data = QueryBuilder::for(Product::class)
            ->allowedFilters(['product_name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'product_name as name']);
        return ProductsResource::collection($data);
    }
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Product::class)->with(['category', 'department', 'salesPrices', 'vendorsPrices'])->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->paginate(request()->pageSize ?? 10);
        $statistics = $this->statistics(Product::class, $request, 'products');
        return response()->success([
            'list' => $data,
            'statistics' => $statistics
        ]);
    }

    public function store(ProductRequest $request)
    {
        $data = $request->only(
            'product_name',
            'department_id',
            'category_id',
            'product_condition',
            'datasheet_attachment',
            'manual_attachment',
            'msds_attachment'
        );
        // dd($data,$request->all());

        $product = Product::create($data);
        $product_logistics = $request->product_logistics;
        $product_sale_prices = $request->product_sale_prices;
        $product_vendors_prices = $request->product_vendors_prices;

        if (isset($product_logistics)) {
            foreach ($product_logistics as $product_logistic) {
                $product_logistic['product_id'] = $product->id;
                ProductLogistic::create($product_logistic);
            }
        }
        if (isset($product_sale_prices)) {
            foreach ($product_sale_prices as $product_sale_price) {
                $product_sale_price['product_id'] = $product->id;

                ProductSalesPrice::create($product_sale_price);
            }
        }
        if (isset($product_vendors_prices)) {
            foreach ($product_sale_prices as $product_sale_price) {
                $product_sale_price['product_id'] = $product->id;

                ProductVendorsPrice::create($product_sale_price);
            }
        }
        $response = [
            "message" => "Created Successfully",
            "status" => "success",
            "data" => $product
        ];
        return response()->json($response, 200);
    }

    public function update(ProductRequest $request, $id)
    {
        $data = $request->all();

        $expens_type = Product::findOrFail($id);
        $expens_type->update($data);
        $response = [
            "message" => "Updated Successfully",
            "status" => "success",
            "data" => $expens_type
        ];
        return response()->json($response, 200);
    }

    public function destroy($id)
    {
        $expens_type = Product::findOrFail($id);
        $expens_type->delete();
        $response = [
            "message" => "Deleted Successfully",
            "status" => "success"
        ];
        return response()->json($response, 200);
    }

    public function show($id)
    {
        $item = Product::with(['category', 'department', 'salesPrices', 'vendorsPrices'])->findOrFail($id);
        return new SingleResource($item);
    }
    public function importProducts(ImportFile $import)
    {
        $file = $import->file;
        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Product::create($item);
            })
            ->import();
    }

    public function exportProducts(Request $request)
    {
        $products = QueryBuilder::for(Product::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Products" => ExportBuilder::generator($products)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['product_name'], 'Status' => $item['status']])
            ->name('Products')->build();
    }

    public function duplicate(ProductRequest $request, $id)
    {
        $data = $request->all();
        $recored = Product::find($id);
        $copy = $recored->replicate()->fill($data);
        $copy->save();
        $response = [
            "message" => "duplicated Successfully",
            "status" => "success",
            "data" => $copy
        ];
        return response()->json($response, 200);
    }

    public function changeStatus(Request $request, $id)
    {
        $status = $request->status;
        Product::where('id', $id)->update(['status' => $status]);
        $response = [
            "message" => "Status Updated Successfully",
            "status" => "success"
        ];

        return response()->json($response, 200);
    }
}
