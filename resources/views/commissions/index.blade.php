@extends('layouts.app')
@section('title', __('Commission Payment'))

@section('content')
    <div class="container-fluid mt-4">

            @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

        {{-- ===== TOP SUMMARY ===== --}}
        <div class="row mb-4">

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Commission Wallet</small>
                        <h3 class="text-success mb-0">৳ {{ number_format($store->commission_wallet, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Commission Due Limit</small>
                        <h3 class="text-danger mb-0">৳ {{ number_format($store->commission_due_limit, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Subscription Status</small>
                        @php
                            $statusText = 'Active';
                            $statusClass = 'text-success';
                            if ($store->commission_wallet > $store->commission_due_limit) {
                                $statusText = 'Inactive';
                                $statusClass = 'text-danger';
                            }
                        @endphp
                        <h3 class="{{ $statusClass }}">{{ $statusText }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mt-3">
                <button type="button" class="btn btn-warning w-100 btn-lg" data-bs-toggle="modal"
                    data-bs-target="#commissionPaymentModal">
                    <i class="fas fa-credit-card"></i> Make Payment
                </button>
            </div>

        </div>

        {{-- ===== TRANSACTION HISTORY ===== --}}
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Commission History</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $index => $transaction)
                            <tr>
                                <td>{{ $transactions->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge {{ $transaction->type === 'credit' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td>৳ {{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ $transaction->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">No commission history found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($transactions->hasPages())
                <div class="card-footer">{{ $transactions->links() }}</div>
            @endif
        </div>
    </div>




    {{-- ===== PAYMENT MODAL ===== --}}
    <div class="modal fade" id="commissionPaymentModal" tabindex="-1" aria-labelledby="commissionPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="commissionPaymentModalLabel">Make Commission Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">x</button>
                </div>

                <div class="modal-body">
                    <form id="commissionPaymentForm" method="POST" action="{{ route('commission.pay.store') }}">
                        @csrf
                        <input type="hidden" name="store_id" value="{{ $store->id }}">
                        <input type="hidden" name="payment_method" id="commission_payment_method">
                        <input type="hidden" name="token_id" id="commission_token_id">

                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" name="amount" min="0.01" step="0.01"
                                value="{{ $store->commission_wallet }}" required>
                            <small class="text-muted">Current Wallet Balance: ৳
                                {{ number_format($store->commission_wallet, 2) }}</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Select Payment Method</label>
                            <div class="d-flex flex-wrap align-items-center justify-content-center">
                                @foreach ($paymentGateways as $gateway)
                                    <div class="client_payment_box p-2 m-1 border rounded text-center"
                                        data-method="{{ $gateway->name }}" style="cursor:pointer;">
                                        <img src="{{ $gateway->logo }}" alt="{{ $gateway->title }}" width="100">
                                        <div>{{ $gateway->title }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Stripe Card Element --}}
                        <div id="commission-card-element" class="form-control mb-3" style="display:none;"></div>
                        <span class="text-danger" id="commission-stripe-error"></span>

                        <button type="button" class="btn btn-primary w-100" id="payCommissionBtn" disabled>
                            Pay Now
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
<style>
    .client_payment_box {
        width: 48%;
    }

    .client_payment_box img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 100%;
        border: 1px solid #006CBA;
    }

    .client_payment_box.selected {
        border: 1px solid #006CBA !important;
    }
</style>
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://www.payfast.co.za/onsite/engine.js"></script>

    <script>
        $(document).ready(function() {
            let stripeCommission, elementsCommission, cardCommission;

            // Payment Method Selection
            $(document).on('click', '.client_payment_box', function() {
                $(this).addClass('selected').siblings().removeClass('selected');
                let method = $(this).data('method');
                $('#commission_payment_method').val(method);
                $('#payCommissionBtn').prop('disabled', false);

                if (method === 'stripe') {
                    $('#commission-card-element').show();
                    if (!stripeCommission) {
                        stripeCommission = Stripe('{{ $stripe_publish_key ?? '' }}');
                        elementsCommission = stripeCommission.elements();
                    }
                    if (!cardCommission) {
                        cardCommission = elementsCommission.create('card');
                        cardCommission.mount('#commission-card-element');
                    }
                } else {
                    $('#commission-card-element').hide();
                    if (cardCommission) {
                        cardCommission.destroy();
                        cardCommission = null;
                    }
                }
            });

            // Pay button click
            $('#payCommissionBtn').on('click', function() {
                let method = $('#commission_payment_method').val();
                if (!method) return alert('Please select a payment method');

                let amount = parseFloat($('input[name="amount"]').val());

                if (method === 'stripe') {
                    stripeCommission.createToken(cardCommission).then(function(result) {
                        if (result.error) {
                            $('#commission-stripe-error').text(result.error.message);
                        } else {
                            $('#commission_token_id').val(result.token.id);
                            $('#commissionPaymentForm').submit();
                        }
                    });
                } else if (method === 'paystack') {
                    if (!'{{ $paystack_publish_key }}') return alert('Paystack not configured');
                    let handler = PaystackPop.setup({
                        key: '{{ $paystack_publish_key }}',
                        email: '{{ auth()->user()->email }}',
                        amount: amount * 100,
                        currency: 'ZAR',
                        callback: function(response) {
                            $('#commission_token_id').val(response.reference);
                            $('#commissionPaymentForm').submit();
                        },
                        onClose: function() {
                            alert('Transaction cancelled');
                        }
                    });
                    handler.openIframe();
                } else if (method === 'razorpay') {
                    if (!'{{ $razorpay_publish_key }}') return alert('Razorpay not configured');
                    let options = {
                        key: '{{ $razorpay_publish_key }}',
                        amount: amount * 100,
                        currency: 'INR',
                        handler: function(response) {
                            $('#commission_token_id').val(response.razorpay_payment_id);
                            $('#commissionPaymentForm').submit();
                        },
                        prefill: {
                            email: '{{ auth()->user()->email }}'
                        }
                    };
                    let rzp = new Razorpay(options);
                    rzp.open();
                } else if (method === 'payfast') {
                    if (!'{{ $payfast_client_id }}' || !'{{ $payfast_client_secret }}') return alert(
                        'Payfast not configured');
                    let payfastUrl =
                        `https://sandbox.payfast.co.za/eng/process?merchant_id={{ $payfast_client_id }}&merchant_key={{ $payfast_client_secret }}&amount=${amount}&item_name=Commission Payment&email_address={{ auth()->user()->email }}&cancel_url={{ route('payment.cancel') }}&notify_url={{ route('payment.notify') }}`;
                    window.open(payfastUrl, 'PayFast Payment', 'width=800,height=600,scrollbars=no');
                } else if (method === 'orangepay') {
                    $('#commissionPaymentForm').submit();
                }
            });
        });
    </script>
@endpush
