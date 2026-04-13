@extends('website.layout.app')
@section('content')
    <style>
        .payment-gateway-label.selected {
            border: 2px solid #4CAF50;
        }

        .payment-gateway-label {
            border: 1px solid #5bc05f;
        }

        .rs-delivery-address-box {
            transition: .3s linear;
        }

        .rs-delivery-address-box i {
            transition: .3s linear;
            flex: 0 0 auto;
            position: relative;
        }

        input[name="address"]:checked+label.rs-delivery-address-box {
            background: #f0fdf4;
            border-color: var(--color-mint-400);
        }

        input[name="address"]:checked+label.rs-delivery-address-box i {
            border: 1.50px solid var(--color-mint-400);
        }

        input[name="address"]:checked+label.rs-delivery-address-box i::before {
            position: absolute;
            content: '';
            width: 12px;
            height: 12px;
            background: var(--color-mint-600);
            border-radius: 100%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @media (max-width: 767px) {
            input[name="address"]:checked+label.rs-delivery-address-box i::before {
                width: 9px;
                height: 9px;
            }
        }
    </style>

    <section id="role" class="modal_container">

        <div onclick="toggleModal('role')" class="modal_backdrop"></div>

        <!-- modal content -->
        <div class="modal_content">
            <form action="{{ route('delivery-address') }}" method="post" class="rs-manage-addresses-form">
                @csrf
                <input type="hidden" name="store_slug" value="{{ request('store') }}">
                <div class="flex justify-between items-center mb-[30px]">
                    <h3 class="text-neutral-700 text-lg font-semibold">Add New Address</h3>
                    <button type="button" onclick="toggleModal('role')"
                        class="transition-transform duration-300 hover:rotate-90">
                        <img src="../assets/icons/close.svg" alt="">
                    </button>
                </div>
                <div class="mb-4">
                    <label class="text-neutral-700 text-base font-medium">Label</label>
                    <input type="text" name="address_name" placeholder="Example : Home, Office ..."
                        class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                    @error('address_name')
                        <span class="text-danger" style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="text-neutral-700 text-base font-medium">Street Address</label>
                    <input type="text" name="road_no" placeholder="123 Lovely Road, Apt 6B"
                        class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                    @error('road_no')
                        <span class="text-danger" style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                <div class=" mb-4">
                    <label class="text-neutral-700 text-base font-medium">City</label>
                    <input type="text" name="area" placeholder="New York"
                        class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                    @error('area')
                        <span class="text-danger" style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="text-neutral-700 text-base font-medium">ZIP Code</label>
                    <input type="text" name="post_code" placeholder="10003"
                        class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                    @error('post_code')
                        <span class="text-danger" style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-[30px]">
                    <label class="text-neutral-700 text-base font-medium">Phone Number</label>
                    <input type="text" name="phone_number" placeholder="+1 (555) 545-5421"
                        class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                    @error('phone_number')
                        <span class="text-danger" style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit"
                    class="rs-add-new-address-btn text-sm bg-linear-to-r from-cyan-500 to-blue-500 text-white h-[48px] text-center leading-[48px] w-[100%] rounded-xl">
                    Save Address
                </button>
            </form>
        </div>
    </section>
    <!-- breadcrumb -->
    <section
        class="rs-breadcrumb-area bg-[#1A7058] h-[260px] w-full bg-[url('../assets/images/header/breadcrumb.png')] bg-cover bg-center flex flex-col items-center justify-center text-center">
        <div class="rs-breadcrumb-content">
            <h1
                class="rs-breadcrumb-title mb-[5px] sm:mb-[10px] text-[26px] md:text-[30px]  md:text-4xl text-white font-semibold leading-[140%]">
                Checkout
            </h1>
            <div class="rs-breadcrumb-top-content">
                <a href="{{ route('home') }}" class="text-base md:text-lg text-white font-normal leading-[100%]">Home / </a>
                <a href="#" class="text-base md:text-lg text-white font-normal leading-[100%]">Cart / </a>
                <a href="#" class="text-base md:text-lg text-white font-normal leading-[100%]">Checkout</a>
            </div>
        </div>
    </section>

    <!-- Order Details area -->
    {{-- <form action="">
        @csrf --}}
    <section class="rs-order-details-section pt-[60px] pb-[80px] px-4 xl:px-0 bg-neutral-50">
        <div class="rs-order-details-area max-w-2lg mx-auto flex flex-col lg:flex-row gap-[23.5px]">
            <!-- Left Column -->
            <div class="rs-checkout-left-area w-full lg:w-2/3">
                <div class="rs-delivery-address-area bg-white p-4 md:p-6 rounded-3xl mb-6">
                    <div class="rs-delivery-address-content">
                        <h3
                            class="rs-delivery-address-title text-sm md:text-base text-neutral-700 font-semibold leading-[140%] mb-[15px]">
                            Delivery Address
                        </h3>
                        @foreach ($addresses as $index => $address)
                            <input type="radio" name="address" id="address_{{ $index }}" class="sr-only peer"
                                data-address="{{ $address->address_name }}" data-address-id="{{ $address->id }}">
                            <label for="address_{{ $index }}"
                                class="rs-delivery-address-box peer-checked:active border-[1px] border-neutral-200 rounded-xl px-[16px] py-[11px] flex gap-[7.5px] items-center mb-[15px] pl-3 md:pl-4">
                                <i
                                    class="address w-[18px] md:w-[24px] h-[18px] md:h-[24px] block border-[1px] border-neutral-300 rounded-[100%]"></i>
                                <div class="rs-delivery-info">
                                    <h5 class="text-[13px] md:text-sm text-neutral-700 font-semibold leading-[100%]">
                                        {{ $address->address_name }}
                                    </h5>
                                    <span
                                        class="text-xs text-neutral-600 font-normal leading-[140%]">{{ $address->road_no }}</span>
                                </div>
                            </label>
                        @endforeach

                        <button type="button" onclick="toggleModal('role')"
                            class="rs-delivery-info-btn text-xs md:text-sm text-neutral-500 font-semibold flex gap-[10px] justify-center items-center border-[1px] border-neutral-200 rounded-xl h-[40px] md:h-[48px] w-full">
                            <i class="fa-solid fa-plus"></i>
                            Add New Address
                        </button>
                    </div>
                </div>
                <div class="rs-order-items-area bg-white p-3 md:p-6 rounded-3xl mb-6 space-y-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm md:text-base font-semibold text-left text-neutral-700 mb-[1px]">
                                Order Items
                            </p>

                        </div>

                    </div>


                    <!-- cards -->
                    <div class="p-3 bg-white flex flex-col gap-4 rounded-xl border border-gray-200">

                        <!-- section container -->
                        <div for="card-1-container">
                            <div
                                class="checkout-card flex items-stretch justify-between gap-[10px] md:gap-4 border-b border-neutral-200 peer-checked:border-mint-600 peer-checked:bg-mint-50 pb-4 px-0 relative">

                                <label for="card-1-container"
                                    class="w-9 h-9 md:w-12 md:h-12 rounded overflow-hidden border border-neutral-100">
                                    <img src="{{ $store->banner?->file }}" alt=""
                                        class="w-full h-full object-cover">

                                </label>
                                <label for="card-1-container" class="flex-1 flex flex-col justify-between">
                                    <div class="space-y-1">
                                        <p class="text-xs md:text-base font-semibold text-left text-neutral-900">
                                            {{ $store->name }}
                                        </p>

                                        <div class="flex justify-start items-center gap-1">
                                            <img src="../assets/icons/star-gold.svg" alt="" class="">
                                            @php
                                                $avgRating = round($store->ratings->avg('rating') * 2) / 2;
                                            @endphp
                                            <span
                                                class="text-xs md:text-sm font-medium text-left text-neutral-800">{{ $avgRating }}</span>
                                            <span
                                                class="text-xs md:text-sm text-left text-neutral-500">({{ count($store->orders) }})</span>
                                            <span
                                                class="flex justify-center items-center flex-grow-0 flex-shrink-0 w-fit md:w-[60px] h-5 gap-3 p-2 rounded-[25px] bg-[#e8fbf4]">
                                                <p class="text-[10px] md:text-xs text-left text-mint-600 cart_total">

                                                </p>
                                            </span>
                                        </div>
                                    </div>
                                </label>
                                <label for="card-1-container" class="flex flex-col justify-between items-end">
                                    <p class="text-xs md:text-sm text-right text-gray-500">Store Subtotal</p>
                                    <p class="text-[13px] md:text-base font-semibold text-right text-mint-600"
                                        id="subtotalCart">{{ $currency }}0.00</p>

                                </label>
                            </div>
                        </div>
                        <div id="card-container">

                        </div>
                    </div>
                </div>
            </div>


            <!-- Right Column -->
            <div class="rs-checkout-right-area w-full lg:w-1/3">
                <form id="checkoutForm">

                    <div class="p-6 px-4 rounded-3xl  bg-white w-full flex flex-col gap-4">
                        <div>
                            <p class="text-sm md:text-base font-semibold text-left text-neutral-700">
                                Order Summery
                            </p>
                        </div>

                        <p class="text-[13px] md:text-sm font-medium text-left text-neutral-500">Selected Items</p>

                        <!-- cards -->
                        <div class="space-y-4 pb-4 border-b border-neutral-200">
                        </div>
                        <div style="margin-top: -10px" class="text-sm" id="product_items">

                        </div>

                        <div class="flex flex-col items-start gap-2">
                            <div class="w-full">
                                <label for="pickup_schadule"
                                    class="text-[13px] md:text-sm text-neutral-700 font-semibold leading-[100%] ">Pickup
                                    Schedule</label>
                                <input type="date" id="pickup_schadule" name="pick_date"
                                    class="p-3 rounded-md border-[1.5px] border-neutral-100 flex-1 h-12 md:h-[40px] w-full text-xs text-neutral-600 font-normal leading-[140%] mt-1 outline-none" />
                                <div class="text-sm" id="pickup_schadule_error"></div>
                            </div>

                            <div class="w-full">
                                <label for="pickup_time"
                                    class="text-[13px] md:text-sm text-neutral-700 font-semibold leading-[100%] ">Select
                                    Time</label>

                                <select name="pick_hour" id="pick_hour"
                                    class="select-dropdown p-3 rounded-md border-[1.5px] border-neutral-100 flex-1 h-12 md:h-[40px] w-full text-xs text-neutral-600 font-normal leading-[140%] mt-1 appearance-none outline-none disabled:bg-slate-100">
                                    <option value="">--Select Pickup Slot--</option>
                                </select>
                                <div class="text-sm" id="pickup_hour_error"></div>
                            </div>


                            <div class="w-full">
                                <label for="delivery_schadule"
                                    class="text-[13px] md:text-sm text-neutral-700 font-semibold leading-[100%] ">Delivery
                                    Schedule</label>

                                <input type="date" id="delivery_schadule" name="delivery_date"
                                    class="p-3 rounded-md border-[1.5px] border-neutral-100 flex-1 h-12 md:h-[40px] w-full text-xs text-neutral-600 font-normal leading-[140%] mt-1 outline-none" />
                                <div class="text-sm" id="delivery_schadule_error"></div>
                            </div>


                            <div class="w-full">
                                <label for="delivery_time"
                                    class="text-[13px] md:text-sm text-neutral-700 font-semibold leading-[100%] ">Select
                                    Time</label>

                                <select name="delivery_hour" id="delivery_hour"
                                    class="select-dropdown p-3 rounded-md border-[1.5px] border-neutral-100 flex-1 h-12 md:h-[40px] w-full text-xs text-neutral-600 font-normal leading-[140%] mt-1 appearance-none outline-none disabled:bg-slate-100">
                                    <option value="">--Select Delivery Slot--</option>
                                </select>
                                <div class="text-sm" id="delivery_hour_error"></div>
                            </div>
                        </div>

                        <!-- promo code -->
                        <div class="pb-4 border-b border-neutral-200 flex flex-col gap-[10px]">
                            <div class="flex justify-start items-center gap-1">
                                <img src="../assets/icons/tag.svg" alt="">
                                <p class="text-[13px] text-left text-[#2bbe90]">Promo Code</p>
                            </div>
                            <div class="promo-code flex flex-row justify-between gap-3">
                                <label for="promo_code"
                                    class="form-control flex justify-start items-center px-4 py-3 rounded border-[1.5px] border-neutral-100 flex-1 h-12 md:h-[40px]">
                                    <input type="text" name="promo_code" id="promo_code"
                                        placeholder="Enter promo code"
                                        class="form-control outline-none text-xs text-neutral-400">
                                </label>
                                <div id="promoButton" style="display: none">
                                    <button type="submit" id="promo-form"
                                        class="w-auto lg:w-fit h-12 md:h-[40px] relative rounded text-xs font-medium text-center text-neutral-500 border-[1.5px] border-neutral-200  px-6">Apply</button>
                                </div>

                            </div>

                            <div style="margin-top: -10px" class="text-sm" id="promo-response">

                            </div>
                        </div>

                        <!-- Subtotal -->
                        <div class="pb-4 border-b border-neutral-200 flex flex-col gap-[10px]">
                            <div class="flex justify-between items-center">
                                <p class="text-[13px] md:text-sm font-medium text-left text-neutral-500">Subtotal</p>
                                <p class="text-[13px] md:text-base text-left text-neutral-700" id="subtotal">
                                    {{ $currency }}0.00</p>
                                <input type="hidden" name="subtotal" id="subTotalInput">
                            </div>
                            <input type="hidden" name="store_slug" value="{{ request('store') }}">
                            <div class="flex justify-between items-center">
                                <p class="text-[13px] md:text-sm font-medium text-left text-neutral-500">Delivery Fee</p>
                                <input type="hidden" id="delivery" name="delivery_fee"
                                    value="{{ $store->delivery_charge ?? 0 }}">
                                <p id="delivery_fee" class="text-[13px] md:text-base text-left text-neutral-700">
                                    {{ $store->delivery_charge == 0 ? 'Free' : $currency . $store->delivery_charge }}
                            </div>
                            <div class="flex justify-between items-center">
                                <p class="text-[13px] md:text-sm font-medium text-left text-neutral-500">Discount</p>
                                <p id="discount" class="text-[13px] md:text-base text-left text-neutral-700">
                                    {{ $currency }}0.00</p>
                                <input type="hidden" name="discount" id="discountInput">
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="pb-4 border-b border-neutral-200">
                            <div class="flex justify-between items-center p-2.5 rounded-lg bg-mint-50">
                                <p class="text-base md:text-lg font-semibold text-left text-neutral-700">Total</p>
                                <p class="text-base md:text-lg font-semibold text-left text-mint-700" id="total">
                                    {{ $currency }}0.00</p>
                                <input type="hidden" name="total" id="totalInput">
                            </div>
                        </div>
                        <div class="pb-4 border-b border-neutral-200 flex justify-start items-start gap-2">
                            <img src="../assets/icons/map-pin.svg" alt="">
                            <p id="delivery-to-address"
                                class="text-[13px] md:text-[13px] md:text-sm font-medium text-left text-gray-500">
                                Delivering
                                to
                                <br>
                            </p>
                        </div>
                        <div class="text-sm" id="selected_address_error"></div>
                        <input type="hidden" id="selected_address_id" name="address_id">

                        <div class="pb-4  border-b border-neutral-200 space-y-4">
                            <div class="flex justify-start items-start gap-2">
                                <img src="../assets/icons/credit-card-green.svg" alt="">
                                <p class="text-[13px] md:text-sm font-medium text-left text-gray-500">Payment Method</p>
                            </div>
                            <input type="hidden" id="selected_gateway" name="payment_mode" value="">
                            <div class="space-y-[10px]">
                                <div class="p-2 flex justify-start items-center gap-4 relative px-4 py-2">
                                    <label for="cod" class="h-5 w-5">
                                        <input type="radio" class="sr-only peer" name="payment" id="cod">
                                        <img type="label" for="cod" name="payment"
                                            src="../assets/icons/radio-unchecked.svg" alt=""
                                            class="block peer-checked:hidden relative z-10">
                                        <img type="label" for="cod" name="payment"
                                            src="../assets/icons/radio-checked.svg" alt=""
                                            class="hidden peer-checked:block relative z-10">
                                        <div
                                            class="absolute top-0 right-0 w-full h-full bg-transparent peer-checked:bg-mint-50 z-0 transition-all duration-200 border-[1.5px] border-gray-200  rounded-lg">
                                        </div>
                                    </label>
                                    <label for="cod"
                                        class="text-[13px] md:text-sm font-medium text-left text-neutral-700 md:text-gray-500  relative z-10">Cash
                                        On
                                        Delivery</label>
                                </div>
                                <div class="flex justify-start items-center gap-4 relative px-4 py-2">
                                    <label for="card" class="h-5 w-5">
                                        <input type="radio" class="sr-only peer" name="payment" id="card">
                                        <img type="label" for="card" name="payment"
                                            src="../assets/icons/radio-unchecked.svg" alt=""
                                            class="block peer-checked:hidden  relative z-10">
                                        <img type="label" for="card" name="payment"
                                            src="../assets/icons/radio-checked.svg" alt=""
                                            class="hidden peer-checked:block  relative z-10">
                                        <div
                                            class="absolute top-0 right-0 w-full h-full bg-transparent peer-checked:bg-mint-50 z-0 transition-all duration-200 border-[1.5px] border-gray-200 rounded-lg">
                                        </div>
                                    </label>
                                    <label for="card"
                                        class="text-[13px] md:text-sm font-medium text-left text-neutral-700 md:text-gray-500 relative z-10">Credit/Debit
                                        Card
                                    </label>
                                </div>

                                <div class="credit-wrapper hidden mt-3 mx-1">
                                    <div class="credit rounded flex flex-wrap gap-2">
                                        <input type="hidden" name="payment_method" value="" id="payment_method">
                                        @foreach ($gateways as $gateway)
                                            <div class="flex justify-center items-center client_payment_box px-1">
                                                <label
                                                    class="payment-gateway-label cursor-pointer rounded border border-gray-200 p-2 hover:border-blue-400 transition"
                                                    data-name="{{ $gateway->name }}">
                                                    <img src="{{ $gateway->logo }}" class="rounded object-contain"
                                                        style="height:2rem" alt="{{ $gateway->name }}">
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                        <button type="button" id="checkoutButton"
                            class="flex justify-center items-center h-10 md:h-12 relative overflow-hidden px-5 py-3.5 rounded-xl bg-mint-600 gap-[5px]">
                            <img class="w-[14px] h-[14px] md:w-6 md:h-6" src="../assets/icons/shield-check.svg"
                                alt="">
                            <p class="flex-grow-0 flex-shrink-0 text-xs md:text-base font-semibold text-center text-white">
                                Place Order
                            </p>
                        </button>


                        <p class="text-[11px] md:text-xs text-center text-gray-400">
                            By placing this order, you agree to our Terms &#x26; Conditions
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <iframe id="webView"></iframe>
    {{-- </form> --}}
@endsection





@push('web-scripts')
    <script>
        const currency = @json($currency);
        const pickToDeliveryGap = @json($appSetting['pick_to_delivery_gap'] ?? 1);
        const storeSlug = @json(request('store'));
        const cards = JSON.parse(localStorage.getItem(`cart_${storeSlug}`)) || [];

        document.getElementById('delivery_schadule').disabled = true;

        document.addEventListener("DOMContentLoaded", function() {
            const cardContainer = document.getElementById('card-container');
            const subtotalElement = document.getElementById('subtotal');
            const subtotalCartElement = document.getElementById('subtotalCart');
            const deliveryFeeElement = document.getElementById('delivery_fee');
            const discountElement = document.getElementById('discount');
            const delivery = document.getElementById("delivery");
            const totalElement = document.getElementById('total');
            const promoElement = document.getElementById('promo_code');
            const promoButton = document.getElementById('promoButton');
            const totalItemsElement = document.querySelector('.cart_total');

            let totalItems = 0;
            let subtotal = 0;
            let deliveryFee = parseFloat(delivery.value) || 0;

            if (cards.length > 0) {
                cards.forEach(card => {
                    const itemTotal = card.price * card.qty;
                    subtotal += itemTotal;
                    totalItems += card.qty;

                    const cardElement = document.createElement('div');
                    cardElement.classList.add('mb-2');
                    cardElement.innerHTML = `
                        <div class="checkout-card flex items-center justify-between gap-[10px] md:gap-4 rounded-xl border border-neutral-200 p-3 relative overflow-hidden">
                            <label for="card_${card.id}" class="flex items-center">
                                <input id="card_${card.id}" name="card_${card.id}" type="checkbox" class="peer sr-only card-check" data-id="${card.id}">
                                <img src="../assets/icons/unchecked.svg" class="w-4 h-4 md:w-5 md:h-5 peer-checked:hidden relative z-10" alt="">
                                <img src="../assets/icons/checked.svg" class="w-4 h-4 md:w-5 md:h-5 hidden peer-checked:block relative z-10" alt="">
                                <div class="absolute top-0 right-0 w-full h-full peer-checked:bg-mint-50 peer-checked:border-mint-600 z-0"></div>
                            </label>
                            <label for="card_${card.id}" class="flex-1 flex flex-col justify-center relative z-10">
                                <div class="space-y-[6px]">
                                    <p class="text-xs md:text-sm font-medium text-neutral-900">${card.name}</p>
                                    <div class="flex justify-start items-center">
                                        <p class="text-xs md:text-sm text-left text-neutral-500">Quantity: ${card.qty}</p>
                                        <p class="text-[11px] md:text-sm text-left text-mint-600 flex items-center gap-2 ml-3">
                                            <span class="inline-block w-1.5 h-1.5 bg-mint-600 rounded-full"></span>
                                            ${currency}${card.price.toFixed(2)} each
                                        </p>
                                    </div>
                                </div>
                            </label>
                            <label for="card_${card.id}" class="flex flex-col justify-center items-end gap-[6px] relative z-10">
                                <p class="text-sm font-semibold text-right text-neutral-900 md:text-mint-600">
                                    ${currency}${itemTotal.toFixed(2)}
                                </p>
                                <p class="text-sm text-right text-neutral-500 hidden md:block">Total</p>
                            </label>
                        </div>
                    `;
                    cardContainer.appendChild(cardElement);

                    const checkbox = cardElement.querySelector(`#card_${card.id}`);
                    checkbox.addEventListener('change', function() {
                        const card = getCardById(this.dataset.id);
                        if (!card || card.id == null) {
                            console.warn("Invalid cart item - missing id:", this.dataset.id);
                            this.checked = false;
                            return;
                        }

                        if (this.checked) {
                            if (!selectedCards.some(item => item.id === card.id)) {
                                selectedCards.push(card);
                            }
                        } else {
                            selectedCards = selectedCards.filter(item => item.id !== card.id);
                        }
                        updateSelectedItems();
                        updateSubtotal();
                        updateTotal();
                    });
                });

                if (totalItemsElement) {
                    totalItemsElement.textContent = `${totalItems} item`;
                }

                subtotalCartElement.textContent = `${currency}${subtotal.toFixed(2)}`;
                deliveryFeeElement.textContent = deliveryFee === 0 ? 'Free' : `${currency}${deliveryFee.toFixed(2)}`;

                updateSubtotal();
                updateTotal();
                applyStoredDiscount();
            } else {
                cardContainer.innerHTML = "<p>No items in your cart.</p>";
            }
        });

        let selectedCards = [];
        let subtotal = 0;

        function getCardById(id) {
            return cards.find(card => card.id == id);  // == to handle string vs number
        }

        function updateSelectedItems() {
            const selectedCardDiv = document.querySelector('.space-y-4.pb-4.border-b.border-neutral-200');
            selectedCardDiv.innerHTML = '';
            selectedCards.forEach(card => {
                const selectedCardElement = document.createElement('div');
                selectedCardElement.classList.add('p-3', 'flex', 'justify-between', 'items-center', 'gap-2', 'rounded-lg', 'border', 'border-neutral-200');
                selectedCardElement.innerHTML = `
                    <div class="flex-1">
                        <p class="text-xs md:text-sm font-medium text-neutral-500">${card.name}</p>
                        <p class="text-xs md:text-sm text-left text-neutral-500">${card.qty} item</p>
                    </div>
                    <p class="text-[13px] font-medium text-right text-[#006CBA]">${currency}${(card.price * card.qty).toFixed(2)}</p>
                `;
                selectedCardDiv.appendChild(selectedCardElement);
            });
        }

        function updateSubtotal() {
            subtotal = selectedCards.reduce((sum, card) => sum + (card.price * card.qty), 0);
            const subtotalElement = document.getElementById('subtotal');
            const subtotalInput = document.getElementById('subTotalInput');
            if (subtotalElement) {
                subtotalElement.textContent = `${currency}${subtotal.toFixed(2)}`;
                subtotalInput.value = subtotal.toFixed(2);
            }
        }

        function updateTotal() {
            const discountValue = parseFloat(localStorage.getItem(`couponDiscount_${storeSlug}`)) || 0;
            const delivery = document.getElementById("delivery");
            const deliveryFee = parseFloat(delivery.value) || 0;
            const totalElement = document.getElementById('total');
            const totalInput = document.getElementById('totalInput');
            const discountElement = document.getElementById('discount');
            const discountInput = document.getElementById('discountInput');

            let total = subtotal + deliveryFee - discountValue;

            if (totalElement) {
                totalElement.textContent = `${currency}${total.toFixed(2)}`;
                totalInput.value = total.toFixed(2);
            }

            if (discountElement) {
                discountElement.textContent = discountValue > 0 ? `${currency}${discountValue.toFixed(2)}` : `${currency}0.00`;
            }
            if (discountInput) {
                discountInput.value = discountValue.toFixed(2);
            }
        }

        function applyStoredDiscount() {
            const savedDiscount = parseFloat(localStorage.getItem(`couponDiscount_${storeSlug}`)) || 0;
            const savedPromoCode = localStorage.getItem(`promoCode_${storeSlug}`) || '';

            if (savedDiscount > 0) {
                document.getElementById('promo_code').value = savedPromoCode;
                document.getElementById('promoButton').querySelector('button').textContent = 'Remove';
                document.getElementById('promoButton').querySelector('button').classList.remove('text-neutral-500', 'border-neutral-200');
                document.getElementById('promoButton').querySelector('button').classList.add('text-white', 'bg-red-500', 'border-transparent');
            } else {
                document.getElementById('promo_code').value = '';
            }

            updateTotal();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const addressRadios = document.querySelectorAll('input[name="address"]');
            const deliveryText = document.getElementById('delivery-to-address');
            const selectedAddressInput = document.getElementById('selected_address_id');

            addressRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const selectedAddress = this.getAttribute('data-address');
                    const selectedAddressId = this.getAttribute('data-address-id');
                    deliveryText.innerHTML = `Delivering to <br>${selectedAddress}`;
                    selectedAddressInput.value = selectedAddressId;
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const labels = document.querySelectorAll('.payment-gateway-label');
            const paymentMethodInput = document.getElementById('payment_method');
            labels.forEach(label => {
                label.addEventListener('click', function() {
                    labels.forEach(l => l.classList.remove('selected'));
                    this.classList.add('selected');
                    paymentMethodInput.value = this.dataset.name;
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const cardRadio = document.getElementById('card');
            const codRadio = document.getElementById('cod');
            const creditWrapper = document.querySelector('.credit-wrapper');

            function updatePaymentUI() {
                if (cardRadio.checked) {
                    creditWrapper.classList.remove('hidden');
                    document.getElementById('payment_method').value = '';
                } else {
                    creditWrapper.classList.add('hidden');
                    document.getElementById('payment_method').value = '';
                }
            }

            updatePaymentUI();
            cardRadio.addEventListener('change', updatePaymentUI);
            codRadio.addEventListener('change', updatePaymentUI);
        });

        document.addEventListener("DOMContentLoaded", function() {
            let today = new Date().toISOString().split("T")[0];
            document.getElementById("pickup_schadule").setAttribute("min", today);
        });

        document.getElementById('pickup_schadule').addEventListener('change', function() {
            let date = this.value;
            fetch(`/pick-schedule?date=${date}&store_slug=${storeSlug}`)
                .then(res => res.json())
                .then(response => {
                    let select = document.getElementById('pick_hour');
                    select.innerHTML = '<option value="">--Select Pickup Slot--</option>';
                    response.slots.forEach(slot => {
                        let opt = document.createElement("option");
                        opt.value = slot.hour;
                        opt.text = slot.title;
                        select.appendChild(opt);
                    });
                });
        });

        document.getElementById("pickup_schadule").addEventListener("change", function() {
            let pickupDate = new Date(this.value);
            pickupDate.setDate(pickupDate.getDate() + pickToDeliveryGap);
            document.getElementById('delivery_schadule').disabled = false;
            let nextDayDate = new Date(pickupDate);
            nextDayDate.setDate(nextDayDate.getDate() + 1);
            let nextDay = nextDayDate.toISOString().split("T")[0];
            document.getElementById("delivery_schadule").setAttribute("min", nextDay);
        });

        document.getElementById('delivery_schadule').addEventListener('change', function() {
            let date = this.value;
            fetch(`/delivery-schedule?date=${date}&store_slug=${storeSlug}`)
                .then(res => res.json())
                .then(response => {
                    let select = document.getElementById('delivery_hour');
                    select.innerHTML = '<option value="">--Select Delivery Slot--</option>';
                    response.slots.forEach(slot => {
                        let opt = document.createElement("option");
                        opt.value = slot.hour;
                        opt.text = slot.title;
                        select.appendChild(opt);
                    });
                });
        });

        document.getElementById('promo-form').addEventListener('click', function(e) {
            e.preventDefault();
            const button = this;
            const promoResponse = document.getElementById('promo-response');
            let cart = JSON.parse(localStorage.getItem(`cart_${storeSlug}`)) || [];

            if (cart.length === 0) {
                promoResponse.innerHTML = '<span class="text-red-500">Please add products to your cart before applying a promo code.</span>';
                return;
            }

            const promoCodeInput = document.getElementById('promo_code').value.trim();
            const isApplied = !!localStorage.getItem(`couponDiscount_${storeSlug}`);

            if (!isApplied) {
                if (!promoCodeInput) {
                    promoResponse.innerHTML = '<span class="text-red-500">Please enter a promo code.</span>';
                    return;
                }

                $.ajax({
                    url: '{{ route('validate-coupon') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        promo_code: promoCodeInput,
                        store_slug: storeSlug
                    },
                    success: function(response) {
                        if (response.data.status === 'success') {
                            const discount = parseFloat(response.data.discount);
                            localStorage.setItem(`couponDiscount_${storeSlug}`, discount);
                            localStorage.setItem(`promoCode_${storeSlug}`, promoCodeInput);

                            document.getElementById('discount').textContent = `${currency}${discount.toFixed(2)}`;
                            updateTotal();

                            promoResponse.innerHTML = '<span class="text-green-500">Coupon applied successfully!</span>';

                            button.textContent = 'Remove';
                            button.classList.remove('text-neutral-500', 'border-neutral-200');
                            button.classList.add('text-white', 'bg-red-500', 'border-transparent');
                        } else {
                            promoResponse.innerHTML = '<span class="text-red-500">Invalid promo code!</span>';
                        }
                    },
                    error: function() {
                        promoResponse.innerHTML = '<span class="text-red-500">Something went wrong!</span>';
                    }
                });
            } else {
                localStorage.removeItem(`couponDiscount_${storeSlug}`);
                localStorage.removeItem(`promoCode_${storeSlug}`);

                document.getElementById('promo_code').value = '';
                document.getElementById('discount').textContent = `${currency}0.00`;
                promoResponse.innerHTML = '';

                updateTotal();

                button.textContent = 'Apply';
                button.classList.remove('text-white', 'bg-red-500', 'border-transparent');
                button.classList.add('text-neutral-500', 'border-neutral-200');
            }
        });

        document.getElementById('checkoutButton').addEventListener('click', function() {
            submitform();
        });



        const submitform = () => {
            const form = document.getElementById('checkoutForm');
            const formData = new FormData(form);
            let data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            // ────────────────────────────────────────────────
            // Fixed part - prevent undefined.id crash
            data.products = selectedCards
                .filter(product => product && typeof product === 'object' && product.id != null)
                .map(product => ({
                    id: product.id,
                    quantity: product.qty ?? 1,
                    price: product.price ?? 0
                }));

            // Debug - remove in production if you want
            console.log("selectedCards:", selectedCards);
            console.log("products sent to server:", data.products);
            // ────────────────────────────────────────────────

            let hasError = false;

            if (data.products.length === 0) {
                if (selectedCards.length > 0) {
                    document.getElementById('product_items').innerHTML = '<span class="text-red-500">No valid products selected (some items missing ID)</span>';
                } else {
                    document.getElementById('product_items').innerHTML = '<span class="text-red-500">Product must be selected</span>';
                }
                hasError = true;
            }
            if (!data.pick_date) {
                document.getElementById('pickup_schadule_error').innerHTML = '<span class="text-red-500">Pick date must be selected</span>';
                hasError = true;
            }
            if (!data.pick_hour) {
                document.getElementById('pickup_hour_error').innerHTML = '<span class="text-red-500">Pick hour must be selected</span>';
                hasError = true;
            }
            if (!data.delivery_date) {
                document.getElementById('delivery_schadule_error').innerHTML = '<span class="text-red-500">Delivery date must be selected</span>';
                hasError = true;
            }
            if (!data.delivery_hour) {
                document.getElementById('delivery_hour_error').innerHTML = '<span class="text-red-500">Delivery hour must be selected</span>';
                hasError = true;
            }
            if (!data.address_id) {
                document.getElementById('selected_address_error').innerHTML = '<span class="text-red-500">Selected address must be selected</span>';
                hasError = true;
            }

            if (hasError) return;

            $.ajax({
                url: "{{ route('orders-web') }}",
                method: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    ...data,
                },
                success: (response) => {
                    const paymentType = response.data.order.payment_type;
                    const order = response.data.order;

                    if (paymentType === 'payfast') {
                        const amount = order.total_amount * 100;
                        const payfastId = response.data.payfast_client_id;
                        const payfastSecret = response.data.payfast_client_secret;
                        const transactionData = {
                            merchant_id: payfastId,
                            merchant_key: payfastSecret,
                            amount: amount,
                            item_name: 'Order Payment',
                            email_address: '{{ auth()->user()->email }}',
                            cancel_url: '{{ route('payment.cancel') }}',
                            notify_url: '{{ route('payment.notify') }}',
                        };
                        const payfastUrl = `https://sandbox.payfast.co.za/eng/process?${$.param(transactionData)}`;
                        openPopupWindow(payfastUrl, 'PayFast Payment', order);
                    } else if (['stripe', 'razorpay', 'paystack', 'orangepay'].includes(paymentType)) {
                        const paymentUrl = response.data.payment_url;
                        openPopupWindow(paymentUrl, 'WebViewPopup', order);
                    } else if (paymentType === 'cash') {
                        localStorage.removeItem(`cart_${storeSlug}`);
                        localStorage.removeItem(`couponDiscount_${storeSlug}`);
                        localStorage.removeItem(`promoCode_${storeSlug}`);
                        window.location.href = "{{ route('order-details') }}";
                    }
                },
                error: (xhr, status, error) => {
                    console.error('AJAX Request Error:', error);
                },
            });
        };


        const openPopupWindow = (url) => {
    const winWidth = 700;
    const winHeight = 700;
    const left = (screen.width - winWidth) / 2;
    const top = (screen.height - winHeight) / 2;
    const popup = window.open(
        url,
        "_blank",
        `resizable,width=${winWidth},height=${winHeight},top=${top},left=${left}`
    );

    if (!popup) {
        alert("Popup blocked! Please allow popups.");
        return;
    }

    let paymentCompleted = false;

    const intervalID = setInterval(() => {
        //  popup closed detect ALWAYS works
        if (popup.closed) {
            clearInterval(intervalID);

            if (!paymentCompleted) {
                handlePaymentCancel();
            }
            return;
        }

        //  only try cross-origin safe code
        try {
            const path = popup.location.pathname;

            if (path.includes("payment/success")) {
                paymentCompleted = true;
                clearInterval(intervalID);
                window.postMessage({ status: 'success' }, '*');
                popup.close();
            } else if (path.includes("payment/cancel") || path.includes("payment/fail")) {
                paymentCompleted = false;
                clearInterval(intervalID);
                popup.close();

                if (path.includes("payment/fail")) {
                    Swal.fire({
                        icon: "error",
                        title: "Payment Failed!",
                        text: "Your payment process was unsuccessful. Please try again.",
                    });
                }

                handlePaymentCancel();
            }
        } catch (e) {
            // cross-origin, ignore
        }
    }, 500);

    popup.focus();
};


        window.addEventListener('message', function(event) {
            if (event.data?.status === 'success') {
                  localStorage.removeItem(`cart_${storeSlug}`);
        localStorage.removeItem(`couponDiscount_${storeSlug}`);
        localStorage.removeItem(`promoCode_${storeSlug}`);
                window.location.href = "{{ route('order-details') }}";
            }
        });

            function handlePaymentCancel(orderId) {
                Swal.fire({
                    icon: "warning",
                    title: "Payment Cancelled",
                    text: "You closed or cancelled the payment process.",
                    confirmButtonText: "OK"
                });

                $.post("{{ route('payment.cancel') }}", {
                    _token: "{{ csrf_token() }}",
                    order_id: orderId
                }).done(function(){
                    window.location.href = "{{ route('order-details') }}";
                });
            }


    </script>
@endpush
