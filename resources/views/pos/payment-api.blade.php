<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .heading {
            font-size: 23px;
            font-weight: 700;
        }

        .text {
            font-size: 16px;
            font-weight: 500;
            color: #b1b6bd;
        }

        .pricing {
            border: 2px solid #304FFE;
            background-color: #f2f5ff;
        }

        .business {
            font-size: 20px;
            font-weight: 500;
        }

        .plan {
            color: #aba4a4;
        }

        .dollar {
            font-size: 16px;
            color: #6b6b6f;
        }

        .amount {
            font-size: 50px;
            font-weight: 500;
        }

        .year {
            font-size: 20px;
            color: #6b6b6f;
            margin-top: 19px;
        }

        .detail {
            font-size: 22px;
            font-weight: 500;
        }

        .cvv {
            height: 44px;
            width: 73px;
            border: 2px solid #eee;
        }

        .cvv:focus {
            box-shadow: none;
            border: 2px solid #304FFE;
        }

        .email-text {
            height: 55px;
            border: 2px solid #eee;
        }

        .email-text:focus {
            box-shadow: none;
            border: 2px solid #304FFE;
        }

        .payment-button {
            height: 70px;
            font-size: 20px;
        }

        #card-element {
            height: 40px;
        }

        .client_payment_box {
            cursor: pointer;
            transition: border 0.3s;
        }

        .client_payment_box.selected {
            border: 2px solid #007bff;
            border-radius: 5px;
        }
    </style>
</head>

<body>


    <div class=" mt-5 mb-5 ">
        <div class="card p-3 my-5">
            @php
                $gateway = request()->query('gateway');
                $order = request()->query('order');
                $system = request()->query('system');
                $orderId = DB::table('orders')->where('id', $order)->first();

                $orders = App\Models\Order::with('customer')->find($order);
                $customer = $orders->customer;

                $users = App\Models\Customer::with('user')->find($customer->id);
                $user = $users->user;

            @endphp

            <span class="detail">Payment method: {{ $gateway }}</span>

            <form id="payment-form">
                @csrf
                <input type="hidden" name="payment_method" id="selected-method" value="{{ $gateway }}">
                <input type="hidden" name="token_id" id="token_id">
                <input type="hidden" name="order_id" value="{{ $order }}">

                @if ((int) $order == $orders->id)
                    <input type="hidden" name="total_amount" id="total_amount" value="{{ $orders->total_amount }}">
                @endif

                <div id="card-element" class="form-control my-3" style="display: none;"></div>
                <span class="text-danger" id="stripe-error"></span>
                <span class="text-danger" id="razorpay-error"></span>
                <div id="paypal-button-container" class="mt-3" style="display: none;"></div>


                <div class="mt-3">
                    <button type="button" class="btn btn-success btn-sm common-btn btn-block  w-100" id="pay-btn"
                        disabled>
                        Proceed to payment <i class="fa fa-long-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>


</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    let stripe, elements, cardElement;
    let formSubmitted = false;
    let paypalInitialized = false;
    var minOrder = @json(DB::table('stores')->first());
    const urlParams = new URLSearchParams(window.location.search);

    const system = urlParams.get('system_type');

    $(document).ready(function() {
        var paymentMethod = document.getElementById("selected-method").value;
        const gateway = urlParams.get('gateway');

        if (gateway === 'stripe') {
            if (gateway === 'stripe') {
                $('#card-element').show();
                $('#pay-btn').prop('disabled', false);
                initializeStripe();
            } else {
                $('#card-element').hide();
                destroyStripeCardElement();
            }
        } else if (gateway === 'paystack') {
            $('.card').css('display', 'none');

            initiatePayStack();
        } else if (gateway === 'razorpay') {
            $('.card').css('display', 'none');
            initiateRazorpay();
        }


        function initializeStripe() {
            var data = @json($data);

            if (!stripe) {
                stripe = Stripe(data.stripe_publish_key); // Use the key from response.data
                elements = stripe.elements();
            }
            if (!cardElement) {
                cardElement = elements.create('card');
                cardElement.mount('#card-element');

            }
        }
        // Destroy Stripe Elements
        function destroyStripeCardElement() {
            if (cardElement) {
                cardElement.destroy();
                cardElement = null;
            }
        }

        // Main pay button click handler
        function initiatePayStack(order) {
            var order = @json($orderId);
            var data = @json($data);

            var paystackKey = data ? data.paystack_publish_key : '';
            var minCost = minOrder.minimum_cost;

            var serviceType = order.service_type;
            var user = @json($user);
            var amount = order ? order.total_amount * 100 : '';

            let selectedCurrency = "ZAR";
            let handler = PaystackPop.setup({
                key: paystackKey,
                email: user.email ?? "root@example.com",
                amount: amount,
                currency: selectedCurrency,
                callback: function(response) {
                    $('#token_id').val(response.reference);
                    formSubmit();
                },
                onClose: function() {
                    alert('Transaction cancelled.');
                }
            });

            handler.openIframe();
        }

        // Razorpay initialization
        function initiateRazorpay(order) {

            var data = @json($data);
            var razorpayKey = data ? data.razorpay_publish_key : '';
            var minCost = minOrder.minimum_cost;
            var order = @json($orderId);
            var serviceType = order.service_type;

            var user = @json($user);


            var amount = order ? order.total_amount * 100 : '';


            let options = {
                key: razorpayKey,
                amount: amount,
                currency: "INR",
                description: "Subscription Payment",
                handler: function(response) {
                    $('#token_id').val(response.razorpay_payment_id);
                    formSubmit();

                },
                prefill: {
                    email: user.email ?? "root@example.com",
                    contact: user.mobile ?? "01735214251",
                },
                theme: {
                    color: "#304FFE"
                }
            };


            let rzp = new Razorpay(options);
            rzp.open();
        }

    });
    $(document).on('click', '#pay-btn', function() {

        var paymentMethod = document.getElementById("selected-method").value;
        if (paymentMethod === 'stripe') {
            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    $('#stripe-error').text(result.error.message);
                    $('#pay-btn').prop('disabled', false);
                    formSubmitted = false;
                } else {
                    $('#token_id').val(result.token.id);
                    formSubmit();

                }
            });
        } else if ($('#selected-method').val() === 'paypal') {
            initiatePaypal();
        }
    });

    function formSubmit() {
        const orderId = $('input[name="order_id"]').val();
        console.log('Order ID:', orderId);
        const url = `{{ route('payment.process.orders', ':order') }}`.replace(':order', orderId);
        console.log('URL:', url);
        const totalAmount = $('input[name="total_amount"]').val();

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                payment_method: $('input[name="payment_method"]').val(),
                token_id: $('input[name="token_id"]').val(),
                order: orderId,
                total_amount: totalAmount
            },
            success: function(response) {

                if (response.success == true) {
                    if (system === 'website') {
                        window.close();
                    } else {
                        window.location.href = "{{ route('payment.success', ['status' => 'success']) }}";
                    }

                }

            },
            error: function(xhr, status, error) {
                console.error('Error fetching variants:', error);
            }
        });
    }
</script>


</html>
