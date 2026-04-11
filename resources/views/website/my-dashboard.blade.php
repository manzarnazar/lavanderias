@extends('website.layout.app')
@section('content')
    <!-- manage addresses area -->
    <section class="max-w-2lg mx-auto pt-[60px] pb-[80px] px-4 xl:px-0 space-y-5 md:space-y-10">

        <div class="w-full">
            <p class="text-2xl md:text-4xl font-semibold text-left text-gray-700">
                My Dashboard
            </p>
            <p class="text-base md:text-xl text-left text-gray-500">
                Manage your orders, addresses, and preferences
            </p>
        </div>


        <div class="w-full grid grid-cols-12 gap-6">
            @include('website.layout.partials.dashboard-sidebar')

            <div class=" col-span-12 lg:col-span-8 flex flex-col gap-6">
                <!-- widgets -->
                <div class="flex justify-between items-center gap-6 flex-nowrap overflow-x-auto scrollbar-hide">
                    <!-- widget 1 -->
                    <div class="p-4 flex justify-between items-start gap-4 w-full min-w-52 rounded-xl  bg-white">
                        <div class="flex flex-col justify-between">
                            <p class="text-base text-gray-700">Total Orders</p>
                            <p class="text-[28px] font-bold text-gray-700">{{ $customer->customer->orders->count() }}
                            </p>

                        </div>
                        <div class="h-16 w-16 bg-mint-50 flex items-center justify-center rounded-full">
                            <img src="../assets/icons/calendar.svg" alt="" class="w-6 h-6">
                        </div>
                    </div>

                    <!-- widget 2 -->
                    <div class="p-4 flex justify-between items-start gap-4 w-full min-w-52 rounded-xl  bg-white">
                        <div class="flex flex-col justify-between">
                            <p class="text-base text-gray-700">In Progress</p>
                            <p class="text-[28px] font-bold text-gray-700">
                                {{ $customer->customer->orders()->where('order_status', 'Processing')->count() }}
                            </p>

                        </div>
                        <div class="h-16 w-16 bg-aqua-50 flex items-center justify-center rounded-full">
                            <img src="../assets/icons/clock-blue.svg" alt="" class="w-6 h-6">
                        </div>
                    </div>

                    <!-- widget 3 -->
                    <div class="p-4 flex justify-between items-start gap-4 w-full min-w-52 rounded-xl  bg-white">
                        <div class="flex flex-col justify-between">
                            <p class="text-base text-gray-700">Completed</p>
                            <p class="text-[28px] font-bold text-gray-700">
                                {{ $customer->customer->orders()->where('order_status', 'Delivered')->count() }}
                            </p>

                        </div>
                        <div class="h-16 w-16 bg-mint-50 flex items-center justify-center rounded-full">
                            <img src="../assets/icons/check-green.svg" alt="" class="w-6 h-6">
                        </div>
                    </div>
                </div>

                <!-- Recent Orders-->
                <div class="rounded-3xl p-6 bg-white shadow-sm flex flex-col gap-[15px]">
                    <div class="flex justify-between items-center">
                        <p class="text-base font-semibold text-left text-neutral-700">
                            Recent Orders
                        </p>
                        <a href="{{ route('my-orders') }}">
                            <p class="text-sm font-medium text-left text-neutral-700">View All</p>
                        </a>

                    </div>


                    @foreach ($customer->customer->orders()->latest()->take(3)->get() as $order)
                        <!-- card 1 -->
                        <div class="p-3 flex justify-between items-start rounded-xl gap-4 border border-neutral-200">
                            <div class="w-14 h-14 bg-mint-50  justify-center items-center rounded hidden sm:flex">
                                <img src="../assets/icons/calendar.svg" alt="" class="w-6 h-6">
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
                            <div class="flex-1 h-full">
                                <div class="flex justify-between items-center">
                                    <div class="w-12 h-12 bg-mint-50  justify-center items-center rounded flex  sm:hidden">
                                        <img src="../assets/icons/calendar.svg" alt="" class="w-6 h-6">
                                    </div>
                                    <div class="block sm:hidden">
                                        <div
                                            class="flex justify-center items-center px-2.5 py-[5px] rounded-[20px]  {{ $statusColor }}">
                                            <p class="text-[8px] sm:text-[10px] text-left text-white">
                                                {{ $order->order_status }}</p>
                                        </div>
                                        <p class="text-[10px] sm:text-xs font-medium text-right text-neutral-500">
                                            {{ \Carbon\Carbon::parse($order->pick_date)->format('M d, Y') }} </p>
                                        <p class="text-xs sm:text-sm font-semibold text-right text-gray-900">
                                            {{ $currency }}{{ $order->payable_amount }}</p>
                                    </div>
                                </div>
                                <p class="text-sm font-medium  text-neutral-900">
                                    #ORD-{{ $order->order_code }}
                                </p>


                                <p class="text-sm  text-neutral-500">{{ $order->store->name }}</p>
                                <div class="flex items-center text-xs text-neutral-700 gap-3">
                                    <div class="flex flex-col">
                                        @forelse ($order->products as $product)
                                            <span class="text-[13px] font-medium">
                                                {{ $product?->service?->name ?? 'No Service' }}
                                            </span>
                                        @empty
                                            <span class="text-[13px] font-medium">No service found</span>
                                        @endforelse
                                    </div>

                                    <div>
                                        <span class="text-xs text-neutral-500">
                                            {{  $order->products->sum(fn($product) => $product->pivot->quantity) }} Items
                                        </span>
                                    </div>
                                </div>

                            </div>
                            <div class="hidden sm:block">
                                <div
                                    class="flex justify-center items-center px-2.5 py-[5px] rounded-[20px] {{ $statusColor }}">
                                    <p class="text-[10px] text-left text-white">{{ $order->order_status }}</p>
                                </div>

                                <p class="text-xs font-medium text-right text-neutral-500">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</p>
                                <p class="text-sm font-semibold text-right text-gray-900">
                                    {{ $currency }}{{ $order->payable_amount }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                </div>


                <!-- saved address -->
                <div class="rounded-3xl p-6 bg-white shadow-sm flex flex-col gap-[15px]">
                    <div class="flex justify-between items-center">
                        <p class="text-base font-semibold text-left text-neutral-700">
                            Saved Addresses
                        </p>
                        <a href="{{ route('manage-addresses') }}">
                            <p class="text-sm font-medium text-left text-neutral-700">View All</p>
                        </a>

                    </div>

                    @foreach ($customer->customer->addresses as $address)
                        <div class="p-3 sm:p-6 flex justify-start items-start gap-5 rounded-xl border border-gray-200">
                            <div class="w-14 h-14 bg-mint-50 justify-center items-center rounded hidden sm:flex">
                                <img src="../assets/icons/home.svg" alt="" class="w-6 h-6">
                            </div>
                            <div class="flex flex-col gap-1 items-start">

                                <div class="flex justify-between items-start sm:hidden w-full">
                                    <div class="w-12 h-12 bg-mint-50 justify-center items-center rounded flex ">
                                        <img src="../assets/icons/home.svg" alt="" class="w-6 h-6">
                                    </div>


                                </div>

                                <div class="flex justify-start items-center gap-2 mb-3">
                                    <p class="text-base font-medium text-left text-gray-900">{{ $address->address_name }}
                                    </p>
                                    @if ($address->is_default == 1)
                                        <span
                                            class="hidden sm:inline-flex justify-start items-center gap-2 bg-mint-50 px-2 py-1 rounded-full">
                                            <img src="../assets/icons/star.svg" alt="">
                                            <p class="text-xs text-left text-[#32d3a0]">Default</p>
                                        </span>
                                    @endif
                                </div>


                                <p class="text-sm text-left text-gray-700 flex justify-start items-center gap-1">
                                    <img src="../assets/icons/map-pin-gray.svg" alt="" class="h-[14px] w-[14px]">
                                    {{ $address->road_no ?? $address->area }}
                                </p>
                                @if ($address->phone_number)
                                    <p class="text-sm text-left text-gray-500  flex justify-start items-center gap-1">
                                        <img src="../assets/icons/phone-gray.svg" alt="" class="h-[14px] w-[14px]">
                                        {{ $address->phone_number }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
@push('web-scripts')
@endpush
