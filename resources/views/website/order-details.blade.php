@extends('website.layout.app')
@section('content')
    <style>
        .rs-order-info-icon::before {
            content: '';
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%) rotate(0deg);
            width: 2px;
            height: 50px;
            background: linear-gradient(180deg, #006CBA 0%, transparent 100%);
        }

        @media (max-width: 1092px) {
            .rs-order-info-icon::before {
                height: 44px;
                bottom: -44px;
            }
        }

        .rs-order-info-icon-2::before {
            content: '';
            position: absolute;
            bottom: -50px;
            left: 50%;
            transform: translateX(-50%) rotate(0deg);
            width: 2px;
            height: 50px;
            background: linear-gradient(180deg, #D9D9D9 0%, transparent 100%);
        }

        @media (max-width: 1092px) {
            .rs-order-info-icon-2::before {
                height: 44px;
                bottom: -44px;
            }
        }

        .rs-order-info-item:last-child .rs-order-info-icon-2::before {
            content: none;
        }

        .rs-order-info-item:last-child {
            padding-bottom: 25.5px;
            border-bottom: 1px solid var(--color-neutral-200);
        }

        .rs-order-items-box:last-child {
            position: relative;
            margin-bottom: 48px;
        }

        .rs-order-items-box:last-child::after {
            position: absolute;
            content: '';
            width: 100%;
            height: 1px;
            border-bottom: 1px solid var(--color-neutral-200);
            bottom: -24px;
            left: 0;
        }

        .rs-order-info-icon-2::after {
            content: '';
            position: absolute;
            top: 32%;
            left: 50%;
            transform: translate(-50%) rotate(0deg);
            width: 14px;
            height: 14px;
            background: var(--color-neutral-400);
            border-radius: 100%;
        }

        .rs-rate-message textarea::placeholder {
            font-weight: 400;
            font-size: 14px;
            line-height: 140%;
            color: var(--color-neutral-500);
        }

        @media (max-width: 767px) {
            .rs-rate-message textarea::placeholder {
                font-size: 12px;
            }
        }

        @media (max-width: 360px) {
            .rs-order-items-box {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 410px) {
            .rs-order-items-box-2 {
                flex-wrap: wrap !important;
            }
        }

        @media (max-width: 360px) {
            .rs-order-items-price {
                margin-left: 0 !important;
            }
        }

        @media (max-width: 410px) {
            .rs-order-items-price-2 {
                margin-left: 0 !important;
            }
        }

        .rs-rate-message textarea:focus::placeholder {
            color: transparent;
        }

        .rs-rate-message textarea {
            outline: none;
        }

        .rs-rate-message textarea:focus {
            border: 1px solid var(--color-neutral-200);
        }

        .rs-rate-submit-btn {
            background: var(--color-mint-600) linear-gradient(4deg, rgba(232, 251, 244, 0.3) 0%, rgba(26, 112, 88, 0.3) 100%);
        }
    </style>
    <!-- breadcrumb -->
    <section
        class="rs-breadcrumb-area bg-[#1A7058] h-[260px] w-full bg-[url('../assets/images/header/breadcrumb.png')] bg-cover bg-center flex flex-col items-center justify-center text-center">
        <div class="rs-breadcrumb-content">
            <h1
                class="rs-breadcrumb-title mb-[5px] sm:mb-[10px] text-[26px] md:text-[30px]  md:text-4xl text-white font-semibold leading-[140%]">
                Order Details
            </h1>
            <div class="rs-breadcrumb-top-content">
                <a href="#" class="text-base md:text-lg text-white font-normal leading-[100%]">Dashboard / </a>
                <a href="#" class="text-base md:text-lg text-white font-normal leading-[100%]">My Orders / </a>
                <a href="#" class="text-base md:text-lg text-white font-normal leading-[100%]">Order
                    #{{ $order->order_code }}</a>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Order Details area -->
    <section class="rs-order-details-section pt-[60px] pb-[80px] px-4 xl:px-0 bg-neutral-50">
        <div class="rs-order-details-area max-w-2lg mx-auto flex flex-col lg:flex-row gap-[23.5px]">

            <!-- Left Column -->
            <div class="rs-order-details-left-area w-full h-fit lg:w-2/3 bg-white p-4 md:p-6 rounded-3xl">
                <div class="rs-order-details-content">
                    <div class="rs-order-details-content-top flex justify-between items-center mb-4">
                        <h4 class="text-sm md:text-base text-neutral-700 font-semibold leading-[140%]">
                            Order #{{ $order->order_code }}
                        </h4>
                        <a href="{{ route('order.invioce', $order->id) }}"
                            class="flex text-center gap-1 text-xs text-neutral-700 font-medium w-[88px] h-[40px] justify-center items-center border-[1.5px] border-neutral-200 rounded-xl">
                            <img class="w-3 h-3" src="../assets/icons/download-icon.svg" alt="">
                            Invoice
                        </a>
                    </div>

                    @php
                        $defaultClass = 'rs-order-info-icon-2 bg-neutral-100';
                        $confirmClass = 'rs-order-info-icon bg-mint-50';
                        $checkIcon = '../assets/icons/check-green.svg';
                        $highlightColor = 'text-neutral-700';
                        $shadowColor = 'text-neutral-400';
                        $highlightP = 'text-neutral-500';
                        $shadowP = 'text-neutral-400';
                    @endphp

                    <div class="rs-order-info-item flex gap-[16px] mb-[20px]">
                        <button
                            class="{{ in_array($order->order_status->value, ['Confirm', 'Picked up', 'Processing', 'On Going', 'Delivered']) ? $confirmClass : $defaultClass }} flex-[0_0_auto] w-[40px] h-[40px] flex items-center justify-center rounded-full relative">
                            @if (in_array($order->order_status->value, ['Confirm', 'Picked up', 'Processing', 'On Going', 'Delivered']))
                                <img class="w-[18px] h-[18px]" src="{{ $checkIcon }}" alt="">
                            @endif

                        </button>
                        <div class="rs-order-info-right">
                            <h5
                                class="text-xs md:text-sm {{ in_array($order->order_status->value, ['Confirm', 'Picked up', 'Processing', 'On Going', 'Delivered']) ? $highlightColor : $shadowColor }} font-medium leading-[100%] mb-[10px]">
                                Order Placed
                            </h5>
                            <p
                                class="text-xs md:text-sm {{ in_array($order->order_status->value, ['Confirm', 'Picked up', 'Processing', 'On Going', 'Delivered']) ? $highlightP : $shadowP }} font-normal leading-[140%] italic">
                                Your order has
                                been confirmed</p>
                            <span
                                class="text-xs md:text-sm {{ in_array($order->order_status->value, ['Confirm', 'Picked up', 'Processing', 'On Going', 'Delivered']) ? $highlightP : $shadowP }} font-normal leading-[140%]">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y - h:i A') }}
                            </span>
                        </div>
                    </div>

                    <div class="rs-order-info-item flex gap-[16px] mb-[20px]">
                        <button
                            class="{{ in_array($order->order_status->value, ['Picked up', 'Processing', 'On Going', 'Delivered']) ? $confirmClass : $defaultClass }} flex-[0_0_auto] w-[40px] h-[40px] flex items-center justify-center rounded-full relative">
                            @if (in_array($order->order_status->value, ['Picked up', 'Processing', 'On Going', 'Delivered']))
                                <img class="w-[18px] h-[18px]" src="{{ $checkIcon }}" alt="">
                            @endif
                        </button>
                        <div class="rs-order-info-right">
                            <h5
                                class="text-xs md:text-sm {{ in_array($order->order_status->value, ['Picked up', 'Processing', 'On Going', 'Delivered']) ? $highlightColor : $shadowColor }} font-medium leading-[100%] mb-[10px]">
                                Items Collected
                            </h5>
                            <p
                                class="text-xs md:text-sm {{ in_array($order->order_status->value, ['Picked up', 'Processing', 'On Going', 'Delivered']) ? $highlightP : $shadowP }} font-normal leading-[140%] italic">
                                Your items have
                                been picked up</p>
                            <span
                                class="text-xs md:text-sm {{ in_array($order->order_status->value, ['Picked up', 'Processing', 'On Going', 'Delivered']) ? $highlightP : $shadowP }} font-normal leading-[140%]">
                                {{ \Carbon\Carbon::parse($order->pick_date)->format('M d, Y') }} -
                                {{ \Carbon\Carbon::parse($order->pick_hour)->format('h:i A') }}
                            </span>
                        </div>
                    </div>

                    <div class="rs-order-info-item flex gap-[16px] mb-[20px]">
                        <button
                            class="{{ in_array($order->order_status->value, ['Processing', 'On Going', 'Delivered']) ? $confirmClass : $defaultClass }} flex-[0_0_auto] w-[40px] h-[40px] flex items-center justify-center rounded-full relative">
                            @if (in_array($order->order_status->value, ['Processing', 'On Going', 'Delivered']))
                                <img class="w-[18px] h-[18px]" src="{{ $checkIcon }}" alt="">
                            @endif
                        </button>
                        <div class="rs-order-info-right">
                            <h5
                                class="text-xs md:text-sm {{ in_array($order->order_status->value, ['Processing', 'On Going', 'Delivered']) ? $highlightColor : $shadowColor }} font-medium leading-[100%] mb-[10px]">
                                In Processing
                            </h5>
                            <p
                                class="text-xs md:text-sm {{ in_array($order->order_status->value, ['Processing', 'On Going', 'Delivered']) ? $highlightP : $shadowP }} font-normal leading-[140%] italic">
                                Your items are
                                being cleaned</p>
                            <span
                                class="text-xs md:text-sm {{ in_array($order->order_status->value, ['Processing', 'On Going', 'Delivered']) ? $highlightP : $shadowP }} font-normal leading-[140%]">In
                                Progress</span>
                        </div>
                    </div>

                    <div class="rs-order-info-item flex gap-[16px] mb-[20px]">
                        <button
                            class="{{ in_array($order->order_status->value, ['On Going', 'Delivered']) ? $confirmClass : $defaultClass }} flex-[0_0_auto] w-[40px] h-[40px] flex items-center justify-center rounded-full relative">
                            @if (in_array($order->order_status->value, ['On Going', 'Delivered']))
                                <img class="w-[18px] h-[18px]" src="{{ $checkIcon }}" alt="">
                            @endif
                        </button>
                        <div class="rs-order-info-right">
                            <h5
                                class="text-xs md:text-sm {{ in_array($order->order_status->value, ['On Going', 'Delivered']) ? $highlightColor : $shadowColor }} font-medium leading-[100%] mb-[10px]">
                                On Going
                            </h5>
                            <p class="text-xs md:text-sm text-neutral-400 font-normal leading-[140%] italic">Driver on the
                                way for delivery</p>
                            <span class="text-xs md:text-sm text-neutral-400 font-normal leading-[140%]">On going</span>
                        </div>
                    </div>

                    <div class="rs-order-info-item flex gap-[16px] mb-[20px]">
                        <button
                            class="{{ $order->order_status->value == 'Delivered' ? $confirmClass : $defaultClass }} flex-[0_0_auto] w-[40px] h-[40px] flex items-center justify-center rounded-full relative">
                            @if ($order->order_status->value == 'Delivered')
                                <img class="w-[18px] h-[18px]" src="{{ $checkIcon }}" alt="">
                            @endif
                        </button>
                        <div class="rs-order-info-right">
                            <h5
                                class="text-xs md:text-sm {{ $order->order_status->value == 'Delivered' ? $highlightColor : $shadowColor }} font-medium leading-[100%] mb-[10px]">
                                Delivered
                            </h5>
                            <p class="text-xs md:text-sm text-neutral-400 font-normal leading-[140%] italic">Order completed
                                successfully</p>
                            <span class="text-xs md:text-sm text-neutral-400 font-normal leading-[140%]">
                                {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }} -
                                {{ \Carbon\Carbon::parse($order->delivery_hour)->format('h:i A') }}
                            </span>
                        </div>
                    </div>

                </div>
                <div class="rs-order-items-area">
                    <div class="rs-order-items-content">
                        <h5 class="text-sm md:text-base text-neutral-700 font-semibold leading-[140%] mb-[15px]">
                            Order Items
                        </h5>

                        @foreach ($order->products as $product)
                            <div
                                class="rs-order-items-box flex items-center gap-[16px] border-[1px] border-neutral-200 rounded-xl px-[12px] py-[11px] mb-[15px]">
                                <div
                                    class="rs-order-items-icon w-[48px] h-[48px] sm:w-[56px] sm:h-[56px] bg-mint-50 rounded-[4px] flex flex-[0_0_auto] items-center justify-center">
                                    <img class="w-[22px] h-[22px] sm:w-[24px] sm:h-[24px]"
                                        src="../assets/icons/shart-icon.svg" alt="">
                                </div>
                                <div class="rs-order-items-box-right-content">
                                    <h5 class="text-xs md:text-sm text-neutral-900 font-medium leading-[140%]">
                                        {{ $product->name }}</h5>
                                    <span class="text-xs md:text-sm text-neutral-500 font-normal leading-[140%]">Quantity:
                                        {{ $product->pivot->quantity }}</span>
                                </div>
                                <div class="rs-order-items-price ml-auto ">
                                    <span class="text-sm md:text-base text-neutral-900 font-semibold leading-[140%]">
                                        {{ $currency }}{{ number_format($product->discount_price ?? $product->price, 2) }}
                                    </span>

                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>
                <div class="rs-pickup-details-area flex flex-wrap  sm:flex-nowrap gap-[24px]">
                    <div
                        class="rs-pickup-details-box border-[1px] border-neutral-200 rounded-xl py-[15px] px-[24px] w-full sm:w-[50%]">
                        <div class="rs-pickup-details-title">
                            <h5 class="text-sm text-neutral-700 font-medium leading-[140%] mb-[24px]">Pickup Details
                            </h5>
                            <a href="javascript:void(0)"
                                class="leading-[140%] mb-[10px] text-[12px] sm:text-sm font-normal flex gap-[6px] text-neutral-700">
                                <img class="self-start mt-[4px]" src="../assets/icons/location.svg" alt="">
                                {{ $order->address->address_name }} <br> {{ $order->address->road_no }}
                            </a>
                            <a href="javascript:void(0)"
                                class="leading-[140%] mb-[6px] text-[12px] sm:text-sm font-normal flex gap-[6px] text-neutral-700">
                                <img src="../assets/icons/celender.svg" alt="">
                                {{ \Carbon\Carbon::parse($order->pick_date)->format('M d, Y') }} -
                                {{ \Carbon\Carbon::parse($order->pick_hour)->format('h:i A') }}
                            </a>
                        </div>
                    </div>
                    <div
                        class="rs-pickup-details-box border-[1px] border-neutral-200 rounded-xl py-[15px] px-[24px]  w-full sm:w-[50%]">
                        <div class="rs-pickup-details-title">
                            <h5 class="text-sm text-neutral-700 font-medium leading-[140%] mb-[24px]">Delivery Details
                            </h5>
                            <a href="javascript:void(0)"
                                class="leading-[140%] mb-[10px] text-[12px] sm:text-sm font-normal flex gap-[6px] text-neutral-700">
                                <img class="self-start mt-[4px]" src="../assets/icons/location.svg" alt="">
                                {{ $order->address->address_name }} <br> {{ $order->address->road_no }}
                            </a>
                            <a href="javascript:void(0)"
                                class="leading-[140%] mb-[6px] text-[12px] sm:text-sm font-normal flex gap-[6px] text-neutral-700">
                                <img src="../assets/icons/celender.svg" alt="">
                                {{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }} -
                                {{ \Carbon\Carbon::parse($order->delivery_hour)->format('h:i A') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="rs-order-details-rightarea w-full lg:w-1/3">
                <div class="rs-store-details-box bg-white px-[16px] py-[24px] rounded-3xl mb-[24px]">
                    <div class="rs-order-details-comon-title">
                        <h5 class="text-sm md:text-base text-neutral-700 font-semibold leading-[140%] mb-[16px]">
                            Store Details
                        </h5>
                        <h6 class="text-sm text-neutral-700 font-semibold leading-[140%] mb-[5px]">
                            {{ $order->store->name }}
                        </h6>
                        <div class="flex gap-[3px] pb-[16px]">

                            @php
                                $ratingCount = $order->store->ratings->count();
                                $avgRating =
                                    $ratingCount > 0
                                        ? round(($order->store->ratings->sum('rating') / $ratingCount) * 2) / 2
                                        : 0; // or null, or "No rating"
                            @endphp


                            <img src="../assets/icons/star-small.svg" alt="">
                            <span class="text-sm text-neutral-700 font-medium leading-[140%]">{{ $avgRating }}</span>
                            <span
                                class="text-sm text-neutral-700 font-normal leading-[140%]">({{ $order?->store?->ratings->count() }}
                                reviews)</span>
                        </div>
                        <div class="rs-details-btn">
                            <a href="tel:+1(555)123-4567"
                                class="leading-[100%] text-xs font-medium flex gap-[6px] text-neutral-700 flex items-center h-[40px] px-[16px] border-[1.50px] border-neutral-200 rounded-xl">
                                <img class="w-[12px] h-[12px]" src="../assets/icons/call.svg" alt=""
                                    srcset="">
                                {{ $order->store->user->mobile }}
                            </a>
                            <a href="#" style="display: none"
                                class="leading-[100%] text-xs font-medium flex gap-[6px] text-neutral-700 flex items-center h-[40px] px-[16px] border-[1.50px] border-neutral-200 rounded-xl">
                                <img class="w-[12px] h-[12px]" src="../assets/icons/comment.svg" alt=""
                                    srcset="">
                                Chat With Store
                            </a>
                        </div>
                    </div>
                </div>
                <div class="rs-driver-details-box bg-white px-[16px] py-[24px] rounded-3xl mb-[24px]">
                    <div class="rs-order-details-comon-title">
                        <h5 class="text-sm md:text-base text-neutral-700 font-semibold leading-[140%] mb-[16px]">
                            Driver Details
                        </h5>

                        @if (!$order->drivers->isEmpty())
                            <h6 class="text-sm text-neutral-700 font-semibold leading-[140%] mb-[5px]">
                                {{ $order->drivers[0]->driver->user->first_name }}
                                {{ $order->drivers[0]->driver->user->last_name }}
                            </h6>
                            {{-- <div class="flex gap-[3px] pb-[16px] border-b border-b-neutral-200 mb-[16px]">
                            <span class="text-sm text-neutral-700 font-normal leading-[140%]">Toyota Prius - ABC
                                1234
                            </span>
                        </div> --}}
                            <div class="rs-details-btn">
                                <a href="tel:+1(555)123-4567"
                                    class="leading-[100%] text-xs font-medium flex gap-[6px] text-neutral-700 flex items-center h-[40px] px-[16px] border-[1.50px] border-neutral-200 rounded-xl">
                                    <img class="w-[12px] h-[12px]" src="../assets/icons/call.svg" alt=""
                                        srcset="">
                                    {{ $order->drivers[0]->driver->user->mobile }}
                                </a>
                            </div>
                        @else
                            <span class="text-sm text-neutral-700">No driver assigned</span>
                        @endif


                    </div>
                </div>

                <div class="rs-payment-details-box bg-white px-[16px] py-[24px] rounded-3xl mb-[24px]">
                    <div class="rs-order-details-comon-title">
                        <h5 class="text-sm md:text-base text-neutral-700 font-semibold leading-[140%] mb-[16px]">
                            Payment
                            Summery
                        </h5>
                    </div>
                    <div class="rs-payment-info-item flex justify-between mb-[10px]">
                        <span class="text-sm font-medium leading-[140%] text-neutral-500">Subtotal</span>
                        <span
                            class="text-sm md:text-base font-normal leading-[140%] text-neutral-700">{{ $currency }}{{ $order->total_amount }}</span>
                    </div>
                    <div class="rs-payment-info-item flex justify-between mb-[10px]">
                        <span class="text-sm font-medium leading-[140%] text-neutral-500">Discount</span>
                        <span
                            class="text-sm md:text-base font-normal leading-[140%] text-neutral-700">{{ $currency }}{{ $order->discount }}</span>
                    </div>
                    <div class="rs-payment-info-item flex justify-between mb-[10px]">
                        <span class="text-sm font-medium leading-[140%] text-neutral-500">Delivery Fee</span>
                        <span
                            class="text-sm md:text-base font-normal leading-[140%] text-neutral-700">{{ $currency }}{{ $order->delivery_charge }}</span>
                    </div>

                    <div class="rs-order-details-comon-title flex justify-between mb-4">
                        <h5 class="text-base md:text-lg text-neutral-700 font-semibold leading-[140%]">Total
                        </h5>
                        <h5 class="text-base md:text-lg text-mint-700 font-semibold leading-[140%]">
                            {{ $currency }}{{ $order->payable_amount }}
                        </h5>
                    </div>
                    <div class="rs-payment-text flex items-center gap-2">

                        <span class="text-[13px] font-normal leading-[140%] text-neutral-400">
                            {{ $order->payment_type == 'cash' ? 'Pay with cash upon delivery' : 'Paid online via ' . $order->payment_type }}
                        </span>

                        @if ($order->payment_status === 'Pending' && $order->payment_type !== 'cash')
                            <a href="javascript:void(0)"
                                onclick="handleRepayment('{{ route('order.repayment', $order->id) }}')"
                                class="btn_solid h-10 w-28 px-3 py-1 text-xs font-medium text-white bg-primary rounded-md hover:bg-primary/90 flex items-center justify-center">
                                Re-payment
                            </a>
                        @endif

                    </div>

                </div>
                @if ($order->order_status->value === 'Delivered')

                    @if (!empty($review))
                        <div class="rs-rate-details-box bg-white px-[16px] py-[24px] rounded-3xl mb-[24px]">
                            <div class="rs-order-details-comon-title mb-[16px]">
                                <h5 class="text-sm md:text-base text-neutral-700 font-semibold">
                                    Your Review
                                </h5>
                            </div>


                            <div class="rs-rate-star mb-[16px] flex justify-center gap-[4px]">
                                @for ($i = 1; $i <= 5; $i++)
                                    <img src="{{ $i <= (int) $review->rating ? asset('assets/icons/star-gold.svg') : asset('assets/icons/star-big-grey.svg') }}"
                                        class="w-6 h-6" alt="star">
                                @endfor
                            </div>


                            <div class="rs-rate-message">
                                <textarea class="w-full h-[78px] p-[12px] bg-neutral-50" readonly>{{ $review->content }}</textarea>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('ratings.store') }}" method="POST">
                            @csrf

                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="store_id" value="{{ $order->store_id }}">

                            <div class="rs-rate-details-box bg-white px-[16px] py-[24px] rounded-3xl mb-[24px]">
                                <div class="rs-order-details-comon-title mb-[16px]">
                                    <h5 class="text-sm md:text-base text-neutral-700 font-semibold">
                                        Rate Your Experience
                                    </h5>
                                </div>


                                <div class="rs-rate-star mb-[16px] flex justify-center gap-[4px]">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <label>
                                            <input type="radio" name="rating" value="{{ $i }}"
                                                class="hidden">
                                            <img src="{{ asset('assets/icons/star-big-grey.svg') }}"
                                                class="star cursor-pointer w-6 h-6" data-value="{{ $i }}"
                                                alt="star">
                                        </label>
                                    @endfor
                                </div>

                                @error('rating')
                                    <p class="text-red-500 text-sm mt-2 text-center">
                                        {{ $message }}
                                    </p>
                                @enderror



                                <div class="rs-rate-message">
                                    <textarea name="content" class="w-full h-[78px] p-[12px] bg-neutral-50 mb-[16px]"
                                        placeholder="Share your experience with Laundry" required>{{ old('content') }}</textarea>
                                </div>


                                <button type="submit"
                                    class="rs-rate-submit-btn text-sm bg-linear-to-r from-cyan-500 to-blue-500 text-white h-[40px] w-full rounded-xl">
                                    Submit Review
                                </button>
                            </div>
                        </form>
                    @endif

                @endif




            </div>
    </section>
@endsection
@push('web-scripts')
    <script>
        function handleRepayment(url) {
            openPopupWindow(url, true);
        }

        const openPopupWindow = (url, debug = false) => {

            const winWidth = 700;
            const winHeight = 700;
            const left = screen.width / 2 - winWidth / 2;
            const top = screen.height / 2 - winHeight / 2;

            const options = `resizable,height=${winHeight},width=${winWidth},top=${top},left=${left}`;
            const win = window.open(url, "_blank", options);

            if (!win) {
                alert("Popup blocked! Please allow popups.");
                return;
            }

            let intervalID = null;

            const trackWindow = () => {
                try {
                    if (win.closed) {
                        clearInterval(intervalID);
                        return;
                    }

                    const currentHost = location.host;

                    if (win.location.host === currentHost) {
                        const pathname = win.location.pathname;

                        if (pathname.includes("payment/success")) {
                            clearInterval(intervalID);

                            win.close();

                            // reload page after success
                            location.reload();

                        } else if (pathname.includes("payment/cancel")) {
                            clearInterval(intervalID);
                            win.close();

                        } else if (pathname.includes("payment/fail")) {
                            clearInterval(intervalID);
                            win.close();

                            Swal.fire({
                                icon: "error",
                                title: "Payment Failed!",
                                text: "Your payment process was unsuccessful. Please try again.",
                            });
                        }
                    }

                } catch (e) {
                    // cross origin ignore
                }
            };

            intervalID = setInterval(trackWindow, 300);
            win.focus();
        };
    </script>
@endpush
