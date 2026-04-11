<?php

namespace App\Http\Controllers;

use App\Models\CommissionHistory;
use App\Models\PaymentGateway;
use App\Models\Store;
use App\Repositories\PaymentGatewayRepository;
use App\Repositories\StoreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    // Dashboard
    public function index()
    {
        $store = Store::where('shop_owner', Auth::id())->firstOrFail();
        $paymentGateways = (new PaymentGatewayRepository())->query()->where('is_active', 1)->get();

        $transactions = CommissionHistory::where('store_id', $store->id)
            ->latest()
            ->paginate(10);

        // Extract keys for Blade JS
        $stripe_publish_key = optional($paymentGateways->firstWhere('name', 'stripe'))->config
            ? json_decode($paymentGateways->firstWhere('name', 'stripe')->config)->published_key
            : '';

        $paystack_publish_key = optional($paymentGateways->firstWhere('name', 'paystack'))->config
            ? json_decode($paymentGateways->firstWhere('name', 'paystack')->config)->public_key
            : '';

        $razorpay_publish_key = optional($paymentGateways->firstWhere('name', 'razorpay'))->config
            ? json_decode($paymentGateways->firstWhere('name', 'razorpay')->config)->key
            : '';

        $payfast_client_id = optional($paymentGateways->firstWhere('name', 'payfast'))->config
            ? json_decode($paymentGateways->firstWhere('name', 'payfast')->config)->merchant_id
            : '';

        $payfast_client_secret = optional($paymentGateways->firstWhere('name', 'payfast'))->config
            ? json_decode($paymentGateways->firstWhere('name', 'payfast')->config)->merchant_key
            : '';

        return view('commissions.index', compact(
            'store',
            'transactions',
            'paymentGateways',
            'stripe_publish_key',
            'paystack_publish_key',
            'razorpay_publish_key',
            'payfast_client_id',
            'payfast_client_secret'
        ));
    }

    // Store payment request and create pending transaction
    public function storePayment(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
        ]);

        $store = Store::findOrFail($request->store_id);


        $transaction = CommissionHistory::create([
            'store_id' => $store->id,
            'amount' => $request->amount,
            'type' => 'deduct',
            'payment_gateway' => $request->payment_method,
            'description' => 'Commission Payment',
            'status' => 0, // 0 = pending
        ]);

        // Return transaction ID and let frontend JS handle payment
        return redirect()->route('commission.notify', [
            'transaction_id' => $transaction->id
        ]);
    }

    // Update transaction after successful payment
    public function paymentNotify(Request $request)
    {

        $request->validate([
            'transaction_id' => 'required',
        ]);

        $transaction = CommissionHistory::findOrFail($request->transaction_id);
        $store = Store::findOrFail($transaction->store_id);

        // Update transaction status
        $transaction->status = 1;
        $transaction->save();

        // Deduct amount from store wallet
        $store->commission_wallet -= $transaction->amount;
        $store->save();

        return redirect()->route('commission.index')
            ->with('success', 'Commission payment completed successfully.');
    }


    // Commission History
   public function history(Request $request)
{
    $query = CommissionHistory::with('store')->latest();

    // Store-wise filter
    if ($request->filled('store_id')) {
        $query->where('store_id', $request->store_id);
    }

    // Date-wise filter
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }

    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }

    $transactions = $query->paginate(10)->withQueryString();

    // All stores for filter dropdown
    $stores = Store::all();

    return view('commissions.history', compact('transactions', 'stores'));
}

}
