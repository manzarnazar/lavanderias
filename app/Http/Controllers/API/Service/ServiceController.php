<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ServiceResource;
use App\Repositories\ProductRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\StoreRepository;

class ServiceController extends Controller
{
    public function index()
    {
        $request = \request();
        $store = $request->store_id;
        $search = $request->search;
        $request->validate(['search' => 'nullable|min:2']);

        $services = (new ServiceRepository())->query()->isActive();

        if ($store) {
            $services = (new StoreRepository())->find($store)->services()
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}");
                });

        }

        return $this->json('service list', [
            'services' => ServiceResource::collection($services->get()),
        ]);
    }

 
    public function popularServices(){
    $request = request();
    $show = $request->show;

    $latitude = $request->latitude;
    $longitude = $request->longitude;

    $query = (new ProductRepository())
            ->query()
            ->whereIn('id', function ($query) {
                $query->select('product_id')
                    ->from('order_products')
                    ->groupBy('product_id')
                    ->havingRaw('SUM(quantity) >= 4');
            })
            ->withSum('orderProducts as total_orders', 'quantity')
            ->with('store') // store relation
            ->orderByDesc('total_orders');

    if ($show !== 'view') {
        $query->limit(8);
    }

    $popularProducts = $query->get();


    return $this->json('popular product list', [
        'products' => ProductResource::collection($popularProducts)->additional([
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]),
    ]);
}

}
