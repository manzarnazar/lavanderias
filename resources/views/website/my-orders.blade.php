@extends('website.layout.app')
@section('content')
    <style>
        .tabs a.active {
            background:
                linear-gradient(4deg, rgba(232, 251, 244, 0.3) 0%, rgba(26, 112, 88, 0.3) 100%),
                var(--color-mint-600);
            color: #fff;
            border-radius: 50px;
        }

        .input-items img {
            top: 50%;
            transform: translateY(-50%);
            left: 16px;
        }

        .input-items input {
            height: 56px;
            border: 1.50px solid var(--color-neutral-100);
            border-radius: 12px;
            padding: 12px 12px 12px 46px;
            outline: none;
        }

        .input-items input::placeholder {
            font-weight: 400;
            font-size: 16px;
            line-height: 140%;
            color: var(--color-neutral-400);
        }

        .input-items input:focus {
            border: 1px solid var(--color-mint-200);
        }

        .input-items input:focus::placeholder {
            color: transparent;
        }
    </style>
    <!-- manage addresses area -->
    <section class="max-w-2lg mx-auto pt-[60px] pb-[80px] px-4 xl:px-0 space-y-5 md:space-y-10">

        <div class="w-full">
            <a href="{{ route('my-dashboard') }}"
                class="cursor-pointer text-base font-semibold text-neutral-500 h-12 w-[230px] border border-neutral-200 rounded-xl flex items-center justify-center gap-2 mb-[10px]">
                <i class="fa-solid fa-arrow-left"></i>
                Back To Dashboard
            </a>
            <p class="text-2xl md:text-4xl font-semibold text-left text-gray-700 mb-1">
                My Orders
            </p>
            <p class="text-base md:text-xl text-left text-gray-500">
                Track and manage all your laundry orders
            </p>
        </div>


        <div class="w-full grid grid-cols-12 gap-6">
            <div class=" col-span-12 lg:col-span-12 flex flex-col gap-6">
                <!-- widgets -->
                <div class="flex justify-between items-center gap-6 flex-nowrap overflow-x-auto scrollbar-hide mb-6">
                    <!-- widget 1 -->
                    <div class="p-4 flex justify-between items-start gap-4 w-full min-w-52 rounded-xl bg-white">
                        <div class="flex flex-col justify-between">
                            <p class="text-[28px] font-bold text-gray-700">{{ $orders->count() }}
                            <p class="text-base text-gray-700">Total Orders</p>
                            </p>

                        </div>
                        <div class="h-16 w-16 bg-mint-50 flex items-center justify-center rounded-full">
                            <img src="../assets/icons/dolly-flatbed.svg" alt="" class="w-6 h-6">
                        </div>
                    </div>
                    <!-- widget 2 -->
                    <div class="p-4 flex justify-between items-start gap-4 w-full min-w-52 rounded-xl bg-white">
                        <div class="flex flex-col justify-between">
                            <p class="text-[28px] font-bold text-gray-700">{{ $activeOrders->count() }}
                            <p class="text-base text-gray-700">Active</p>
                            </p>

                        </div>
                        <div class="h-16 w-16 bg-aqua-50 flex items-center justify-center rounded-full">
                            <img src="../assets/icons/boxes.svg" alt="" class="w-6 h-6">
                        </div>
                    </div>
                    <!-- widget 3 -->
                    <div class="p-4 flex justify-between items-start gap-4 w-full min-w-52 rounded-xl bg-white">
                        <div class="flex flex-col justify-between">
                            <p class="text-[28px] font-bold text-gray-700">{{ $completedOrders->count() }}
                            <p class="text-base text-gray-700">Completed</p>
                            </p>

                        </div>
                        <div class="h-16 w-16 bg-mint-50 flex items-center justify-center rounded-full">
                            <img src="../assets/icons/truck-check.svg" alt="" class="w-6 h-6">
                        </div>
                    </div>
                    <!-- widget 4 -->
                    <div class="p-4 flex justify-between items-start gap-4 w-full min-w-52 rounded-xl bg-white">
                        <div class="flex flex-col justify-between">
                            <p class="text-[28px] font-bold text-gray-700">{{ $cancelledOrders->count() }}
                            <p class="text-base text-gray-700">Cancelled</p>
                            </p>

                        </div>
                        <div class="h-16 w-16 bg-danger-50 flex items-center justify-center rounded-full">
                            <img src="../assets/icons/cross-circle.svg" alt="" class="w-6 h-6">
                        </div>
                    </div>
                </div>

                <!-- search -->
                <div class="w-full flex items-center justify-between mb-6 bg-white p-6 rounded-xl mb-6">
                    <div class="input-items">
                        <form action="{{ route('my-orders', ['status' => 'alldd']) }}" method="GET">

                            <div class="relative">
                                <img class="absolute w-[18px] h-[18px]" src="../assets/icons/search-icon.svg"
                                    alt="">
                                <input class="w-[496px] pl-[28px]" type="text" name="order_no"
                                    placeholder="Enter your order no." value="{{ request()->order_no }}">
                                <!-- Add a submit button for clear UX -->
                                <button type="submit"
                                    class="absolute right-0 top-0 h-[55px] w-[110px] bg-mint-600 text-white rounded-xl">
                                    Search
                                </button>
                            </div>
                        </form>

                    </div>

                    <div
                        class="tabs flex items-center justify-between [52px] leading-[40px] p-[6px] bg-mint-50 rounded-[50px]">
                        <a class="text-base font-medium leading-[40px] w-auto text-center inline-block text-neutral-500 h-[40px] px-[27.3px]
                            {{ request()->status == 'all' || !request()->has('status') ? 'active' : '' }}"
                            href="{{ route('my-orders', ['status' => 'all']) }}">
                            All
                        </a>

                        <a class="text-base font-medium leading-[40px] w-auto text-center inline-block text-neutral-500 h-[40px] px-[27.3px] {{ request()->status == 'Processing' ? 'active' : '' }}"
                            href="{{ route('my-orders', ['status' => 'Processing']) }}">
                            Active
                        </a>
                        <a class="text-base font-medium leading-[40px] w-auto text-center inline-block text-neutral-500 h-[40px] px-[27.3px] {{ request()->status == 'Delivered' ? 'active' : '' }}"
                            href="{{ route('my-orders', ['status' => 'Delivered']) }}">
                            Completed
                        </a>
                        <a class="text-base font-medium leading-[40px] w-auto text-center inline-block text-neutral-500 h-[40px] px-[27.3px] {{ request()->status == 'Cancelled' ? 'active' : '' }}"
                            href="{{ route('my-orders', ['status' => 'Cancelled']) }}">
                            Cancelled
                        </a>
                    </div>
                </div>

                <!-- details box 1-->

                @forelse ($filterOrders as $order)
                    <div class="box w-full bg-white p-6 rounded-3xl flex gap-6">
                        <div class="left w-2/3 pr-6 border-r border-neutral-200">
                            <div class="flex items-center gap-[15px] mb-6">
                                <div
                                    class="w-14 h-14 flex justify-center items-center flex-[0_0_auto] border border-gray-100 rounded-lg p-1">
                                    <img class="object-contain w-full h-full"
                                        src="{{ asset($order->store->logo?->file) ?? '../assets/images/stores/store-1.png' }}"
                                        alt="">
                                </div>
                                @php
                                    $statusColor = '';

                                    switch ($order->order_status->value) {
                                        case 'Pending':
                                            $statusColor = 'bg-gray-400';
                                            break;
                                        case 'Confirm':
                                            $statusColor = 'bg-blue-500';
                                            break;
                                        case 'Picked up':
                                            $statusColor = 'bg-yellow-500';
                                            break;
                                        case 'Processing':
                                            $statusColor = 'bg-orange-500';
                                            break;
                                        case 'On Going':
                                            $statusColor = 'bg-green-500';
                                            break;
                                        case 'Delivered':
                                            $statusColor = 'bg-teal-500';
                                            break;
                                        case 'Cancelled':
                                            $statusColor = 'bg-red-500';
                                            break;
                                        default:
                                            $statusColor = 'bg-gray-300';
                                    }
                                @endphp
                                <div class="w-full">
                                    <div class="flex items-center gap-4 mb-[10px]">
                                        <p class="text-sm font-medium  text-neutral-900">
                                            #ORD-{{ $order->order_code }}
                                        </p>
                                        <div
                                            class="flex justify-center items-center px-2.5 py-[5px] rounded-[20px] {{ $statusColor }}">
                                            <p class="text-[10px] text-left text-white">{{ $order->order_status }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <p class="text-sm  text-neutral-500">{{ $order->store->name }}</p>
                                        @forelse ($order->products as $product)
                                            <p class="text-[13px] font-medium text-neutral-700">
                                                {{ $product?->service?->name }}
                                            </p>
                                        @empty
                                            <p class="text-[13px] font-medium text-neutral-700">
                                                No service found
                                            </p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <div class="w-full mb-6">

                                @foreach ($order->products as $product)
                                    <div class="flex items-center mb-[10px] justify-between">
                                        <p class="text-sm  text-neutral-500">{{ $product->name }} x
                                            {{ $product->pivot->quantity }}</p>
                                        <span class="text-sm font-medium text-neutral-700">
                                            {{ $currency }}{{ number_format($product->pivot->quantity * ($product->discount_price ?? $product->price), 2) }}
                                        </span>

                                    </div>
                                @endforeach

                            </div>
                            <div class="w-full flex items-center ">
                                <div class="flex-1">
                                    <p class="text-[13px] font-medium text-neutral-700 mb-[8px]">Pickup Time</p>
                                    <span class="text-sm  text-neutral-700 flex items-center gap-[5px]">
                                        <img src="../assets/icons/calendar-clock.svg" alt="">
                                        {{ \Carbon\Carbon::parse($order->pick_date)->format('M d, Y') }} -
                                        {{ \Carbon\Carbon::parse($order->pick_hour)->format('h:i A') }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-[13px] font-medium text-neutral-700 mb-[8px]">Delivery Time</p>
                                    <span class="text-sm  text-neutral-700 flex items-center gap-[5px]">
                                        <img src="../assets/icons/truck.svg"
                                            alt="">{{ \Carbon\Carbon::parse($order->delivery_date)->format('M d, Y') }}
                                        -
                                        {{ \Carbon\Carbon::parse($order->delivery_hour)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="right w-1/3 flex flex-col justify-center h-full">
                            <div class="right-content">
                                <p class="text-[13px] font-medium text-neutral-700 mb-1">Total Amount</p>
                                <h4 class="text-2xl font-bold  text-neutral-700 mb-1">
                                    {{ $currency ?? '$' }}{{ $order->payable_amount }}</h4>
                                <p class="text-sm  text-neutral-500 mb-6">{{ $order->payment_type }}</p>
                                <a href="{{ route('order-detail', $order->slug) }}"
                                    class=" cursor-pointer flex text-center gap-2 text-xs text-neutral-700 font-medium w-full h-[40px] justify-center items-center border-[1.5px] border-neutral-200 rounded-xl">
                                    <img class="w-3 h-3" src="../assets/icons/eye.svg" alt="">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full bg-white p-10 rounded-3xl text-center">
                        <p class="text-lg font-medium text-neutral-700 mb-2">
                            No orders found
                        </p>
                        <p class="text-sm text-neutral-500">
                            You don’t have any orders for this status yet.
                        </p>
                    </div>
                @endforelse



                <!-- details box 2-->
                {{-- <div class="box w-full bg-white p-6 rounded-3xl flex gap-6">
                    <div class="left w-2/3 pr-6 border-r border-neutral-200">
                        <div class="flex items-center gap-[15px] mb-6">
                            <div
                                class="w-14 h-14 flex justify-center items-center flex-[0_0_auto] border border-gray-100 rounded-lg p-1">
                                <img class="object-contain w-full h-full" src="../assets/images/stores/store-1.png"
                                    alt="">
                            </div>
                            <div class="w-full">
                                <div class="flex items-center gap-4 mb-[10px]">
                                    <p class="text-sm font-medium  text-neutral-900">
                                        #ORD-2847
                                    </p>
                                    <div
                                        class="flex justify-center items-center px-2.5 py-[5px] rounded-[20px] bg-[#34c759]">
                                        <p class="text-[10px] text-left text-white">Delivered</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <p class="text-sm  text-neutral-500">CleanPro Express</p>
                                    <p class="text-[13px] font-medium text-neutral-700">Dry Cleaning</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full mb-6">
                            <div class="flex items-center mb-[10px] justify-between">
                                <p class="text-sm  text-neutral-500">Business Suit x 1</p>
                                <span class="text-sm font-medium text-neutral-700">$45.00</span>
                            </div>
                            <div class="flex items-center mb-[10px] justify-between">
                                <p class="text-sm  text-neutral-500">Dress Shirt x 3</p>
                                <span class="text-sm font-medium text-neutral-700">$24.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm  text-neutral-500">Silk Tie x 1</p>
                                <span class="text-sm font-medium text-neutral-700">$6.00</span>
                            </div>
                        </div>
                        <div class="w-full flex items-center ">
                            <div class="flex-1">
                                <p class="text-[13px] font-medium text-neutral-700 mb-[8px]">Pickup Time</p>
                                <span class="text-sm  text-neutral-700 flex items-center gap-[5px]">
                                    <img src="../assets/icons/calendar-clock.svg" alt="">Oct 24, 2025 - 10:00 AM
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="text-[13px] font-medium text-neutral-700 mb-[8px]">Delivery Time</p>
                                <span class="text-sm  text-neutral-700 flex items-center gap-[5px]">
                                    <img src="../assets/icons/truck.svg" alt="">Oct 26, 2025 - 2:00 PM
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="right w-1/3 flex flex-col justify-center h-full">
                        <div class="right-content">
                            <p class="text-[13px] font-medium text-neutral-700 mb-1">Total Amount</p>
                            <h4 class="text-2xl font-bold  text-neutral-700 mb-1">$45.00</h4>
                            <p class="text-sm  text-neutral-500 mb-6">Credit Card</p>
                            <button
                                class="flex text-center gap-2 text-xs text-neutral-700 font-medium w-full h-[40px] justify-center items-center border-[1.5px] border-neutral-200 rounded-xl mb-[10px]">
                                <img class="w-3 h-3" src="../assets/icons/eye.svg" alt="">
                                View Details
                            </button>
                            <button
                                class="flex text-center gap-2 text-xs text-neutral-700 font-medium w-full h-[40px] justify-center items-center border-[1.5px] border-neutral-200 rounded-xl mb-[10px]">
                                <img class="w-3 h-3" src="../assets/icons/download-icon.svg" alt="">
                                Invoice
                            </button>
                            <button
                                class="flex text-center gap-2 text-xs text-neutral-700 font-medium w-full h-[40px] justify-center items-center border-[1.5px] border-neutral-200 rounded-xl">
                                <img class="w-3 h-3" src="../assets/icons/restock.svg" alt="">
                                Reorder
                            </button>
                        </div>
                    </div>
                </div> --}}

                <!-- details box 3-->
                {{-- <div class="box w-full bg-white p-6 rounded-3xl flex gap-6">
                    <div class="left w-2/3 pr-6 border-r border-neutral-200">
                        <div class="flex items-center gap-[15px] mb-6">
                            <div
                                class="w-14 h-14 flex justify-center items-center flex-[0_0_auto] border border-gray-100 rounded-lg p-1">
                                <img class="object-contain w-full h-full" src="../assets/images/stores/store-1.png"
                                    alt="">
                            </div>
                            <div class="w-full">
                                <div class="flex items-center gap-4 mb-[10px]">
                                    <p class="text-sm font-medium  text-neutral-900">
                                        #ORD-2847
                                    </p>
                                    <div
                                        class="flex justify-center items-center px-2.5 py-[5px] rounded-[20px] bg-[#fbbc04]">
                                        <p class="text-[10px] text-left text-white">Out For Delivery</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <p class="text-sm  text-neutral-500">CleanPro Express</p>
                                    <p class="text-[13px] font-medium text-neutral-700">Dry Cleaning</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full mb-6">
                            <div class="flex items-center mb-[10px] justify-between">
                                <p class="text-sm  text-neutral-500">Business Suit x 1</p>
                                <span class="text-sm font-medium text-neutral-700">$45.00</span>
                            </div>
                            <div class="flex items-center mb-[10px] justify-between">
                                <p class="text-sm  text-neutral-500">Dress Shirt x 3</p>
                                <span class="text-sm font-medium text-neutral-700">$24.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm  text-neutral-500">Silk Tie x 1</p>
                                <span class="text-sm font-medium text-neutral-700">$6.00</span>
                            </div>
                        </div>
                        <div class="w-full flex items-center ">
                            <div class="flex-1">
                                <p class="text-[13px] font-medium text-neutral-700 mb-[8px]">Pickup Time</p>
                                <span class="text-sm  text-neutral-700 flex items-center gap-[5px]">
                                    <img src="../assets/icons/calendar-clock.svg" alt="">Oct 24, 2025 - 10:00 AM
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="text-[13px] font-medium text-neutral-700 mb-[8px]">Delivery Time</p>
                                <span class="text-sm  text-neutral-700 flex items-center gap-[5px]">
                                    <img src="../assets/icons/truck.svg" alt="">Oct 26, 2025 - 2:00 PM
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="right w-1/3 flex flex-col justify-center h-full">
                        <div class="right-content">
                            <p class="text-[13px] font-medium text-neutral-700 mb-1">Total Amount</p>
                            <h4 class="text-2xl font-bold  text-neutral-700 mb-1">$45.00</h4>
                            <p class="text-sm  text-neutral-500 mb-6">Credit Card</p>
                            <button
                                class="flex text-center gap-2 text-xs text-neutral-700 font-medium w-full h-[40px] justify-center items-center border-[1.5px] border-neutral-200 rounded-xl">
                                <img class="w-3 h-3" src="../assets/icons/eye.svg" alt="">
                                View Details
                            </button>
                        </div>
                    </div>
                </div> --}}

                <!-- details box 4-->
                {{-- <div class="box w-full bg-white p-6 rounded-3xl flex gap-6">
                    <div class="left w-2/3 pr-6 border-r border-neutral-200">
                        <div class="flex items-center gap-[15px] mb-6">
                            <div
                                class="w-14 h-14 flex justify-center items-center flex-[0_0_auto] border border-gray-100 rounded-lg p-1">
                                <img class="object-contain w-full h-full" src="../assets/images/stores/store-1.png"
                                    alt="">
                            </div>
                            <div class="w-full">
                                <div class="flex items-center gap-4 mb-[10px]">
                                    <p class="text-sm font-medium  text-neutral-900">
                                        #ORD-2847
                                    </p>
                                    <div
                                        class="flex justify-center items-center px-2.5 py-[5px] rounded-[20px] bg-[#F15B5B]">
                                        <p class="text-[10px] text-left text-white">Cancelled</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <p class="text-sm  text-neutral-500">CleanPro Express</p>
                                    <p class="text-[13px] font-medium text-neutral-700">Dry Cleaning</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full mb-6">
                            <div class="flex items-center mb-[10px] justify-between">
                                <p class="text-sm  text-neutral-500">Business Suit x 1</p>
                                <span class="text-sm font-medium text-neutral-700">$45.00</span>
                            </div>
                            <div class="flex items-center mb-[10px] justify-between">
                                <p class="text-sm  text-neutral-500">Dress Shirt x 3</p>
                                <span class="text-sm font-medium text-neutral-700">$24.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm  text-neutral-500">Silk Tie x 1</p>
                                <span class="text-sm font-medium text-neutral-700">$6.00</span>
                            </div>
                        </div>
                        <div class="w-full flex items-center ">
                            <div class="flex-1">
                                <p class="text-[13px] font-medium text-neutral-700 mb-[8px]">Pickup Time</p>
                                <span class="text-sm  text-neutral-700 flex items-center gap-[5px]">
                                    <img src="../assets/icons/calendar-clock.svg" alt="">Oct 24, 2025 - 10:00 AM
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="text-[13px] font-medium text-neutral-700 mb-[8px]">Delivery Time</p>
                                <span class="text-sm  text-neutral-700 flex items-center gap-[5px]">
                                    <img src="../assets/icons/truck.svg" alt="">Oct 26, 2025 - 2:00 PM
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="right w-1/3 flex flex-col justify-center h-full">
                        <div class="right-content">
                            <p class="text-[13px] font-medium text-neutral-700 mb-1">Total Amount</p>
                            <h4 class="text-2xl font-bold  text-neutral-700 mb-1">$45.00</h4>
                            <p class="text-sm  text-neutral-500 mb-6">Credit Card</p>
                            <button
                                class="flex text-center gap-2 text-xs text-neutral-700 font-medium w-full h-[40px] justify-center items-center border-[1.5px] border-neutral-200 rounded-xl">
                                <img class="w-3 h-3" src="../assets/icons/eye.svg" alt="">
                                View Details
                            </button>
                        </div>
                    </div>
                </div> --}}

                <!-- details box 5-->
                {{-- <div class="box w-full bg-white p-6 rounded-3xl flex gap-6">
                    <div class="left w-2/3 pr-6 border-r border-neutral-200">
                        <div class="flex items-center gap-[15px] mb-6">
                            <div
                                class="w-14 h-14 flex justify-center items-center flex-[0_0_auto] border border-gray-100 rounded-lg p-1">
                                <img class="object-contain w-full h-full" src="../assets/images/stores/store-1.png"
                                    alt="">
                            </div>
                            <div class="w-full">
                                <div class="flex items-center gap-4 mb-[10px]">
                                    <p class="text-sm font-medium  text-neutral-900">
                                        #ORD-2847
                                    </p>
                                    <div
                                        class="flex justify-center items-center px-2.5 py-[5px] rounded-[20px] bg-[#34C759]">
                                        <p class="text-[10px] text-left text-white">Delivered</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <p class="text-sm  text-neutral-500">CleanPro Express</p>
                                    <p class="text-[13px] font-medium text-neutral-700">Dry Cleaning</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-full mb-6">
                            <div class="flex items-center mb-[10px] justify-between">
                                <p class="text-sm  text-neutral-500">Business Suit x 1</p>
                                <span class="text-sm font-medium text-neutral-700">$45.00</span>
                            </div>
                            <div class="flex items-center mb-[10px] justify-between">
                                <p class="text-sm  text-neutral-500">Dress Shirt x 3</p>
                                <span class="text-sm font-medium text-neutral-700">$24.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-sm  text-neutral-500">Silk Tie x 1</p>
                                <span class="text-sm font-medium text-neutral-700">$6.00</span>
                            </div>
                        </div>
                        <div class="w-full flex items-center ">
                            <div class="flex-1">
                                <p class="text-[13px] font-medium text-neutral-700 mb-[8px]">Pickup Time</p>
                                <span class="text-sm  text-neutral-700 flex items-center gap-[5px]">
                                    <img src="../assets/icons/calendar-clock.svg" alt="">Oct 24, 2025 - 10:00 AM
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="text-[13px] font-medium text-neutral-700 mb-[8px]">Delivery Time</p>
                                <span class="text-sm  text-neutral-700 flex items-center gap-[5px]">
                                    <img src="../assets/icons/truck.svg" alt="">Oct 26, 2025 - 2:00 PM
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="right w-1/3 flex flex-col justify-center h-full">
                        <div class="right-content">
                            <p class="text-[13px] font-medium text-neutral-700 mb-1">Total Amount</p>
                            <h4 class="text-2xl font-bold  text-neutral-700 mb-1">$45.00</h4>
                            <p class="text-sm  text-neutral-500 mb-6">Credit Card</p>
                            <button
                                class="flex text-center gap-2 text-xs text-neutral-700 font-medium w-full h-[40px] justify-center items-center border-[1.5px] border-neutral-200 rounded-xl mb-[10px]">
                                <img class="w-3 h-3" src="../assets/icons/eye.svg" alt="">
                                View Details
                            </button>
                            <button
                                class="flex text-center gap-2 text-xs text-neutral-700 font-medium w-full h-[40px] justify-center items-center border-[1.5px] border-neutral-200 rounded-xl mb-[10px]">
                                <img class="w-3 h-3" src="../assets/icons/download-icon.svg" alt="">
                                Invoice
                            </button>
                            <button
                                class="flex text-center gap-2 text-xs text-neutral-700 font-medium w-full h-[40px] justify-center items-center border-[1.5px] border-neutral-200 rounded-xl">
                                <img class="w-3 h-3" src="../assets/icons/restock.svg" alt="">
                                Reorder
                            </button>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
@endsection
@push('web-scripts')
    <script></script>
@endpush
