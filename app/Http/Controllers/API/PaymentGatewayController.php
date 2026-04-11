<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Repositories\TransationRepository;
use App\Repositories\PaymentGatewayRepository;
use App\Http\Requests\PaymentGatewayRequest;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\StripePayService;
use App\Services\RazorPayService;
use App\Services\PaystackService;
use App\Repositories\TransactionRepository;

class PaymentGatewayController extends Controller
{
    public function __construct(
        protected StripePayService $stripeService,
        protected RazorPayService $razorpayService,
        protected PaystackService $paystackService
    ) {}

    /**
     * Show payment gateway
     */
    public function index()
    {
        $paymentGateways = PaymentGateway::get();

        return $this->json('payment details', [
            'payment' => $paymentGateways
        ]);

        // return view('paymentGateway.index', compact('paymentGateways'));
    }



    public function processOrder(Request $request, $order)
    {
        // dd($order);
        $paymentGateway = PaymentGateway::where('name', $request->payment_method)->first();
        $config = json_decode($paymentGateway->config);

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

}
