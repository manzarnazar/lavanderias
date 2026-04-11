<?php

namespace App\Http\Controllers\Seller;

use App\Enums\PaymentGateway;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Service;
use App\Repositories\OrderRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\VariantRepository;
use App\Http\Resources\VariantResource;
use App\Repositories\ProductRepository;
use App\Http\Resources\ProductResource;
use Illuminate\Validation\Rules\Enum;

class PosController extends Controller
{
    public function posCustomer(){

        $customers = Customer::all();

        return $this->json('Customer Info',[
           'customers' => $customers
        ]);

    }
    public function posService(){

        $services = Service::all();
        return $this->json('Service Info',[
            'services' => $services
        ]);

    }

    public function posStore(Request $request)
    {

        $abc=$request->validate([
            'customer_id' => 'exists:customers,id',
            'service_id' => 'exists:services,id',
            'variant_id' => 'exists:variants,id',
            'payment_id' => ['required', new Enum(PaymentGateway::class)],
        ]);
        // dd($abc);
        $order = (new OrderRepository())->PosStoreByRequest($request);

        (new TransactionRepository())->storeForOrder($order);


        if($order->payment_type != 'cash'){
            $paymentUrl = route('pos.payment', ['order' => $order->id, 'gateway' => $order->payment_type]);

        return $this->json('Order Successful',[
            'message' => 'Order is added successfully',
            'payment_url' => $paymentUrl,
            'payment_type' => $order->payment_type,
            'orders' =>$order,
        ]);

        }else{
            return $this->json('Order Successful',[
                'message' => 'Order is added successfully',

            ]);
        }


    }

    public function fetchVariants()
    {
        $serviceId = \request('service_id');

        $store = auth()->user()->store;

        $variants = (new VariantRepository())->query()->where('service_id', $serviceId)->where('store_id', $store?->id)->orderBy('position', 'asc')->get();

        return $this->json('variant list', [
            'variants' => VariantResource::collection($variants)
        ]);
    }

    public function fetchProducts(Request $request)
    {
        $store = auth()->user()->store;

        if ($store) {
            $request->merge(['store_id' => $store?->id]);
        }

        $products = (new ProductRepository())->getByRequest($request);

        return $this->json('product list', [
            'products' => ProductResource::collection($products)
        ]);
    }
}
