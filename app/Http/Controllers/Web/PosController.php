<?php

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatus;
use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VariantResource;
use App\Models\AppSetting;
use App\Models\Customer;
use App\Models\Order;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\Repositories\VariantRepository;
use Illuminate\Http\Request;
use App\Repositories\PaymentGatewayRepository;
use App\Repositories\TransactionRepository;
use App\Models\PaymentGateway;
use App\Models\Store;
use App\Models\StorePaymentGateway;
use App\Services\StripePaymentService;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $services = $user->store?->services;

        $repository = new PaymentGatewayRepository();
        $storeId = $user->store->id;

        if ($user->roles->first()?->name === 'store') {
            $gateways = $repository->query()
                ->where('is_active', 1)
                ->whereHas('store_payment_gateways', function ($query) use ($storeId) {
                    $query->where('store_id', $storeId);
                })
                ->get();
        }

        $store = Store::where('id', $storeId)->first();
        $settings = AppSetting::all();

        $data = [
            'paymentGateways' => $gateways,
            'customers' => Customer::all(),
            'services' => $services,
            'store' => $store,
            'currency' => $settings[0]->currency,

        ];
        return view('pos.index', $data);
    }

    public function sales(Request $request)
    {
        $store = auth()->user()->store;
        $orderStatus = OrderStatus::cases();

        $orders = (new OrderRepository())->query()->withoutGlobalScope('pos')->where('store_id', $store?->id)->where('pos_order', 1)->get();

        // $orders = (new OrderRepository())->getSortedByPosRequest($request);

        return view('pos.sales', compact('orders', 'orderStatus'));
    }

    public function store(Request $request)
    {

        $order = (new OrderRepository())->PosStoreByRequest($request);
        $transaction = (new TransactionRepository())->storeForOrder($order);

        $payfastGateway = PaymentGateway::where('name', 'payfast')->first();
        $payfast_client_id = optional(json_decode($payfastGateway->config))->merchant_id ?? '';
        $payfast_client_secret = optional(json_decode($payfastGateway->config))->merchant_key ?? '';

        $paymentUrl = route('pos.payment', ['order' => $order->id, 'gateway' => $order->payment_type]);

        return response()->json([
            'message' => 'Order is added successfully',  // Success message
            'payment_url' => $paymentUrl,              // Payment URL
            'payment_type' => $order->payment_type,    // Payment type
            'orders' => $order,                        // Order data
            'payfast_client_id' => $payfast_client_id,
            'payfast_client_secret' => $payfast_client_secret,
        ]);
    }
    public function Update(Request $request, $orderId)
    {
        (new OrderRepository())->PosUpdateByRequest($request, $orderId);
        return to_route('order.index');
    }
    public function payment()
    {
        $stripeGateway  = PaymentGateway::where('name', 'stripe')->first();
        $razorpayGateway = PaymentGateway::where('name', 'razorpay')->first();
        $payStackGateway = PaymentGateway::where('name', 'paystack')->first();
        $payfastGateway = PaymentGateway::where('name', 'payfast')->first();
        $stripe_publish_key = optional(json_decode($stripeGateway->config))->published_key ?? '';
        $razorpay_publish_key = optional(json_decode($razorpayGateway->config))->key ?? '';
        $paystack_publish_key = optional(json_decode($payStackGateway->config))->public_key ?? '';
        $payfast_client_id = optional(json_decode($payfastGateway->config))->merchant_id ?? '';
        $payfast_client_secret = optional(json_decode($payfastGateway->config))->merchant_key ?? '';

        $data = [
            'stripe_publish_key' => $stripe_publish_key,
            'razorpay_publish_key' => $razorpay_publish_key,
            'paystack_publish_key' => $paystack_publish_key,
            'payfast_client_id' => $payfast_client_id,
            'payfast_client_secret' => $payfast_client_secret,
        ];
        return view('pos.payment', compact('data'));
    }


  public function paymentApi()
  {

        $order = Order::findOrFail(request()->query('order'));

        $stripeGateway  = PaymentGateway::where('name', 'stripe')->first();
        $razorpayGateway = PaymentGateway::where('name', 'razorpay')->first();
        $payStackGateway = PaymentGateway::where('name', 'paystack')->first();

        $stripeStoreGateway = StorePaymentGateway::where('store_id', $order->store_id)->where('payment_gateway_id', $stripeGateway->id)->first();
        $razorpayStoreGateway = StorePaymentGateway::where('store_id', $order->store_id)->where('payment_gateway_id', $razorpayGateway->id)->first();
        $payStackStoreGateway = StorePaymentGateway::where('store_id', $order->store_id)->where('payment_gateway_id', $payStackGateway->id)->first();

        $razorpay_publish_key = optional(json_decode(optional($razorpayStoreGateway)->config))->key ?? '';
        $stripe_publish_key = optional(json_decode(optional($stripeStoreGateway)->config))->published_key ?? '';
        $paystack_publish_key = optional(json_decode(optional($payStackStoreGateway)->config))->public_key ?? '';

        $data = [
            'stripe_publish_key' => $stripe_publish_key,
            'razorpay_publish_key' => $razorpay_publish_key,
            'paystack_publish_key' => $paystack_publish_key,
        ];

        return view('pos.payment-api',compact('data'));
    }


    public function storeCustomer(Request $request)
    {
        $request['is_active'] = 1;

        $user = (new UserRepository())->registerUser($request);

        $user->assignRole(Roles::CUSTOMER->value);

        (new CustomerRepository())->storeByUser($user);

        return $this->json(__('Created Successfully'), [
            'user' => (object)[
                'id' => $user->customer->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
            ],
        ], 200);
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
