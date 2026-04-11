<?php

namespace App\Http\Controllers\Web;

use App\Enums\IsHas;
use App\Http\Controllers\Controller;

use App\Repositories\StoreSubscriptionRepository;
use App\Http\Requests\PaymentGatewayRequest;
use App\Models\Order;
use App\Repositories\PaymentGatewayRepository;
use App\Models\PaymentGateway;
use App\Models\Subscription;
use App\Models\Store;
use App\Models\StorePaymentGateway;
use Illuminate\Http\Request;
use App\Services\StripePayService;
use App\Services\PaypalService;
use App\Services\RazorPayService;
use App\Services\PaystackService;
use Exception;
use App\PaymentMethod\OrangeMoney;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\Auth;

class PaymentGatewayController extends Controller
{
    public function __construct(
        protected StripePayService $stripeService,
        protected RazorPayService $razorpayService,
        protected PaypalService $paypalService,
        protected PaystackService $paystackService
    ) {}

    /**
     * Show payment gateway
     */
    public function index()
    {
        $user = auth()->user();
        $userId = Store::where('shop_owner', $user->id)->first();
        $paymentGateways = PaymentGateway::all();

        if ($user->hasRole('store')) {
            $storeId =  $userId->id;
            $paymentGateways = PaymentGateway::where('is_active', 1)->get();
            $storePaymentGateways = StorePaymentGateway::where(function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })->get()->keyBy('payment_gateway_id');
            return view('paymentGateway.store-index', compact('paymentGateways', 'storePaymentGateways'));
        }

        return view('paymentGateway.index', compact('paymentGateways'));
    }

    /**
     * Update payment gateway
     */

    public function update(PaymentGatewayRequest $request, PaymentGateway $paymentGateway)
    {
        $repository = new PaymentGatewayRepository();
        $repository->updateByRequest($request, $paymentGateway);

        return back()->withSuccess(__('Payment Gateway Updated Successfully'));
    }
    public function storeUpdate(Request $request)
    {
        $user = auth()->user();
        $userId = Store::where('shop_owner', $user->id)->first();
        $storePaymentGateway = StorePaymentGateway::where('store_id', $userId->id)->where('payment_gateway_id', $request->payment_gateway_id)->first();
        $config = json_encode($request->config);

        if ($storePaymentGateway) {
            $storePaymentGateway->update([
                'config' => $config,
                'mode' => $request->mode,
            ]);
        } else {
            StorePaymentGateway::create([
                'store_id' => $userId->id,
                'config' => $config,
                'mode' => $request->mode,
                'payment_gateway_id' => $request->payment_gateway_id,
            ]);
        }

        return back()->withSuccess(__('Payment Gateway Updated Successfully'));
    }

    public function toggleStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:store_payment_gateways,id',
            'is_active' => 'required|boolean',
        ]);

        $gateway = StorePaymentGateway::find($request->id);
        $gateway->is_active = $request->is_active;
        $gateway->save();

        return response()->json([
            'success' => true,
            'message' => 'Payment gateway status updated successfully!',
        ]);
    }

    /**
     * Toggle payment gateway status
     */
    public function toggle(PaymentGateway $paymentGateway)
    {
        $paymentGateway->update([
            'is_active' => ! $paymentGateway->is_active,
        ]);
        return back()->withSuccess(__('Status Updated Successfully'));
    }

    public function storeToggle(PaymentGateway $paymentGateway)
    {
        $user = auth()->user();

        // Ensure the authenticated user owns a store
        $store = Store::where('shop_owner', $user->id)->first();

        if (!$store) {
            return back()->withErrors(__('Store not found for the authenticated user.'));
        }

        $status = request('status') === 'on' ? 1 : 0;

        $storePaymentGateway = StorePaymentGateway::where([
            'payment_gateway_id' => $paymentGateway->id,
            'store_id' => $store->id
        ])->first();

        if ($storePaymentGateway) {
            if ($storePaymentGateway->is_active !== $status) {
                $storePaymentGateway->is_active = $status;
                $storePaymentGateway->save();

                \Log::info('StorePaymentGateway status updated', [
                    'store_id' => $store->id,
                    'payment_gateway_id' => $paymentGateway->id,
                    'new_status' => $status
                ]);
            }
        } else {
            StorePaymentGateway::create([
                'payment_gateway_id' => $paymentGateway->id,
                'store_id' => $store->id,
                'is_active' => $status
            ]);

            \Log::info('StorePaymentGateway record created', [
                'store_id' => $store->id,
                'payment_gateway_id' => $paymentGateway->id,
                'status' => $status
            ]);
        }

        return back()->withSuccess(__('Status updated successfully.'));
    }

    public function payment()
    {
        return view('subscriptionPurchase.payment');
    }

    public function process(Request $request, Subscription $subscription)
    {

        $paymentGateway = PaymentGateway::where('name', $request->payment_method)->first();

        $request['paid_amount'] = $subscription->price ??  null;
        $request['description'] = $subscription->description ?? null;
        $request['mode'] = $paymentGateway->mode ?? null;

        $subscriptionRepo = new StoreSubscriptionRepository();
        $subscriptionRepo->storeByRequest($subscription, $request->payment_method);

        return back()->with('success', 'Payment successfully processed.');
    }
    public function processOrder(Request $request, $order)
    {
        $paymentGateway = PaymentGateway::where('name', $request->payment_method)->first();
        $storeGateway = StorePaymentGateway::where('payment_gateway_id', $paymentGateway->id)->first();

        $config = json_decode($storeGateway->config);

        $request['paid_amount'] = $request->total_amount ??  null;
        $request['description'] = $order->instruction ?? null;
        $request['mode'] = $paymentGateway->mode ?? null;
        $this->{$request->payment_method . 'Service'}->paymentProcess($request, $config);
        $transactionRepo = new TransactionRepository();
        $transactionRepo->updateWhenComplatePay($order, $request->payment_method);

        return response()->json([
            'success' => true,
            'message' => 'Payment successfully processed',
        ], 200);
    }

    public function paymentNotify(Request $request)
    {
        $merchantId = $request->input('merchant_id');
        $merchantKey = $request->input('merchant_key');
        $transactionId = $request->input('transaction_id');
        $paymentStatus = $request->input('payment_status');
        $amount = $request->input('amount');
        $itemName = $request->input('item_name');
        $emailAddress = $request->input('email_address');

        if ($paymentStatus === 'success') {
            return response()->json([
                'status' => 'success',
                'transaction_id' => $transactionId
            ]);
        }
        return response()->json(['status' => 'failure', 'message' => 'Payment failed']);
    }

    public function orangeMoneyPaymentprocess(Request $request, Subscription $subscription)
    {

        $request->validate([
            'pin_code' => 'required',
            'phone_number' => 'required'
        ]);
        $paymentGateway = PaymentGateway::where('name', $request->payment_method)->first();
        $orangePay = json_decode($paymentGateway->config);

        if (!$orangePay) {
            return to_route('subscription.purchase.update', $subscription->id)->withError('Orange money payment gateway not configured please contact to the admin');
        }
        $orangeMoney = new OrangeMoney($orangePay->client_id, $orangePay->client_secret);
        $responseOtp = $orangeMoney->getOtp($request->pin_code, $request->phone_number, $orangePay);

        if (!isset($responseOtp['otp'])) {

            return to_route('subscription.purchase.update', $subscription->id)->withError('Invalid pin code or phone number for orange money, please check and try again');
        }
        $response = $orangeMoney->makePayment($orangePay->merchant_code, $request->phone_number, $responseOtp['otp'], $subscription->price, $orangePay);

        $subscriptionRepo = new StoreSubscriptionRepository();
        $subscriptionRepo->storeByRequest($subscription, $request->payment_method);

        return redirect()->route('root')->with('success', 'Payment successfully processed.');
    }

    public function orangeMoneyPaymentprocessOrder(Request $request, $order)
    {

        // $request->validate([
        //     'pin_code' => 'required',
        //     'phone_number' => 'required'
        // ]);
        // $paymentGateway = PaymentGateway::where('name', $request->payment_method)->first();
        // $orangePay = json_decode($paymentGateway->config);

        // if (!$orangePay) {
        //     return to_route('subscription.purchase.update', $order)->withError('Orange money payment gateway not configured please contact to the admin');
        // }
        // $orangeMoney = new OrangeMoney($orangePay->client_id, $orangePay->client_secret);
        // $responseOtp = $orangeMoney->getOtp($request->pin_code, $request->phone_number, $orangePay);

        // if (!isset($responseOtp['otp'])) {

        //     return to_route('subscription.purchase.update', $order)->withError('Invalid pin code or phone number for orange money, please check and try again');
        // }
        // $response = $orangeMoney->makePayment($orangePay->merchant_code, $request->phone_number, $responseOtp['otp'], $order->price, $orangePay);

        // $subscriptionRepo = new StoreSubscriptionRepository();
        // $subscriptionRepo->storeByRequest($subscription, $request->payment_method);

        // return redirect()->route('root')->with('success', 'Payment successfully processed.');

    }

    public function success(Request $request)
    {

        return response()->json([
            'status' => 'success',
            'message' => 'Payment successful'
        ],200);
    }

    public function paymentCancel(Request $request)
    {
        return 'payment cancelled';
    }
}
