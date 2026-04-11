@extends('website.layout.app')
@section('content')
    <!-- Services area -->
    <section class="pt-[60px] pb-[100px] px-4 xl:px-0 bg-neutral-50">
        <div class="max-w-2lg  mx-auto grid grid-cols-1 md:grid-cols-12 gap-[24px]">

            <!-- Left Column -->
            <div class="col-span-12 md:col-span-12 lg:col-span-8">
                <div class="p-3 md:p-6 rounded-3xl w-full bg-white">
                    <!-- Service Name -->
                    @if (!$serviceData->isEmpty())
                        <div class="pb-[15px] space-y-[15px] border-b border-neutral-200">
                            <p class="text-base font-semibold text-start text-neutral-700">{{ $serviceData[0]->name }}</p>

                            <div class="relative flex items-center">

                                <!-- Left Arrow -->
                                <button id="scrollLeft" style="background: #e1e3e5"
                                    class="absolute left-0 z-10 h-10 w-10 flex items-center justify-center rounded  hover:opacity-100">
                                    <i class="fa-solid fa-angle-left"></i></button>

                                <div id="variantScroll"
                                    class="variant-scroll flex items-center gap-3 overflow-x-auto whitespace-nowrap scrollbar-hide px-12 cursor-grab">
                                    @forelse ($serviceData[0]->variants->where('store_id', $store->id) as $variant)
                                        <button
                                            class="variant-button transition-all duration-150 h-10 px-3 md:px-5 rounded-xl group hover:bg-gradient-to-bl hover:from-mint-600 hover:via-mint-600 hover:to-mint-200 border-[1.5px] border-gray-200 hover:border-transparent {{ $loop->first ? 'bg-mint-600' : '' }}"
                                            data-variant="{{ $variant->id }}">
                                            <p
                                                class="text-[10px] md:text-xs font-medium text-center transition-all duration-150 text-gray-500 whitespace-nowrap">
                                                {{ $variant->name }}</p>
                                        </button>
                                    @empty
                                        <p class="text-sm text-neutral-500">No variants available for this service.</p>
                                    @endforelse
                                </div>

                                <!-- Right Arrow -->
                                <button id="scrollRight" style="background: #e1e3e5"
                                    class="absolute right-0 z-10 h-10 w-10 flex items-center justify-center rounded hover:opacity-100"><i
                                        class="fa-solid fa-angle-right"></i></button>
                            </div>
                        </div>
                    @endif

                    <!-- Products Section -->
                    <div class="pt-4 flex flex-col gap-6">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                            <p class="text-base font-semibold text-start text-neutral-700">Select your product</p>

                            <div>
                                <label for="search"
                                    class="flex justify-start items-center gap-3 px-4 rounded-xl w-[235px] border-[1.5px] border-neutral-100 flex-1 h-12 md:h-[40px]">
                                    <img src="../assets/icons/search-icon.svg" alt="">
                                    <input type="text" name="search" id="search" placeholder="Search services ..."
                                        class="outline-none text-xs text-neutral-400" required="">
                                </label>
                            </div>
                        </div>

                        @php
                            $service = $serviceData->first();

                        @endphp

                        @php
    // Get the first service
    $service = $serviceData->first();
@endphp

@if ($service && $service->variants->isNotEmpty())
    @foreach ($service->variants->where('store_id', $store->id) as $variant)
        <div class="variant-products grid grid-cols-1 md:grid-cols-2 gap-4"
             data-variant="{{ $variant->id }}"
             style="{{ $loop->first ? '' : 'display:none;' }}">

            @if ($variant->products->isNotEmpty())
                @foreach ($variant->products->where('is_active', 1)->where('store_id', $store->id) as $product)
                    <!-- Product Card -->
                    <div class="p-3 flex flex-col gap-7 rounded-xl border border-neutral-200 bg-white">
                        <div class="space-y-[10px]">
                            <p class="text-sm font-semibold text-left text-neutral-700">{{ $product->name }}</p>
                            <div class="flex justify-start items-center relative">
                                <p class="text-lg font-bold text-start text-mint-600">
                                    {{ $currency }}{{ $product->discount_price ?? $product->price }}
                                </p>
                                <p class="text-xs font-medium text-start text-neutral-500">/items</p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">

                            <!-- Add to Cart Button -->
                            <button class="add-to-cart-btn w-[350px] h-12 bg-mint-600 text-white rounded-xl text-base"
                                onclick='addToCart({
                                    id: {{ $product->id }},
                                    name: "{{ $product->name }}",
                                    price: {{ $product->discount_price ?? $product->price }},
                                    qty: 1,
                                    service_slug: "{{ $service->slug }}",
                                    store_slug: "{{ $store->slug }}"
                                })'
                                id="add-btn-{{ $product->id }}">
                                <i class="text-2xl not-italic">+</i> Add Item
                            </button>

                            <!-- Counter + In Cart -->
                            <div class="cart-controls hidden flex items-center gap-4 w-full justify-between"
                                id="controls-{{ $product->id }}">
                                <div class="counter_container w-[110px] h-8 px-4"
                                     id="card-{{ $product->id }}-counter">
                                    <button onclick="counterController('card-{{ $product->id }}-counter','decrease',{{ $product->id }})">−</button>
                                    <div class="current_count">1</div>
                                    <button onclick="counterController('card-{{ $product->id }}-counter','increase',{{ $product->id }})">+</button>
                                </div>
                                <span class="px-2 py-[5px] bg-mint-600 text-white text-[10px] rounded-[20px]">In Cart</span>
                            </div>

                        </div>
                    </div>
                @endforeach
            @else
                <!-- Empty state message -->
                <div class="col-span-1 md:col-span-2 p-6 text-center text-neutral-500 border border-dashed border-neutral-300 rounded-xl">
                    <p class="text-sm">No products available for this variant.</p>
                </div>
            @endif

        </div>
    @endforeach
@else
    <!-- No variants message -->
    <div class="py-10 text-center">
        <p class="text-sm text-neutral-500">There is no variant available</p>
    </div>
@endif

                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-span-12 lg:col-span-4">
                <div class="p-6 px-4 rounded-3xl  bg-white w-full flex flex-col gap-4">
                    <div>
                        <p class="text-sm md:text-base font-semibold text-left text-neutral-700">
                            Your Basket
                        </p>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b border-neutral-200">
                        <p class="text-[13px] md:text-sm font-medium text-left text-neutral-500">Selected Items</p>

                        <span id="item-count"
                            class="relative px-2.5 py-[5px] rounded-[20px] bg-mint-600 text-[10px] text-left text-white">
                            0 items
                        </span>

                    </div>

                    <div id="basket-items" class="flex flex-col items-start gap-4 pb-4 border-b border-neutral-200"></div>



                    <!-- promo code -->
                    <div class="pb-4 border-b border-neutral-200 flex flex-col gap-[10px]">
                        <div class="flex justify-start items-center gap-1">
                            <img src="../assets/icons/tag.svg" alt="">
                            <p class="text-[13px] text-left text-[#2bbe90]">Promo Code</p>
                        </div>
                        <form class="promo-code flex flex-row justify-between gap-3">
                            <label for="promo_code"
                                class="flex justify-start items-center px-4 rounded-xl border-[1.5px] border-neutral-100 flex-1 h-12 md:h-[40px]">
                                <input type="text" name="promo_code" id="promo_code" placeholder="Enter promo code"
                                    class="outline-none text-xs text-neutral-400" required="">

                            </label>

                            <button type="submit" id="promo-form"
                                class="w-auto lg:w-fit h-12 md:h-[40px] relative rounded-xl text-xs font-medium text-center text-neutral-500 border-[1.5px] border-neutral-200  px-6">Apply
                            </button>

                        </form>
                        <div style="margin-top: -10px" class="text-sm" id="promo-response">

                        </div>

                    </div>


                    <!-- subtotal -->
                    <div class="pb-4 border-b border-neutral-200 flex flex-col gap-[10px]">
                        <div class="flex justify-between items-center">
                            <p class="text-[13px] md:text-sm font-medium text-left text-neutral-500">Subtotal</p>
                            <p id="subtotal" class="text-[13px] md:text-base text-left text-neutral-700">
                                {{ $currency }}0.00</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-[13px] md:text-sm font-medium text-left text-neutral-500">Delivery Fee
                            </p>
                            <input type="hidden" id="delivery" name="delivery_fee"
                                value="{{ $deliveryCharge->delivery_charge ?? 0 }}">
                            <p id="delivery_fee" class="text-[13px] md:text-base text-left text-neutral-700">
                                {{ $deliveryCharge?->delivery_charge == null ? 'Free' : $currency . $deliveryCharge->delivery_charge }}
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-[13px] md:text-sm font-medium text-left text-neutral-500">Discount</p>
                            <p id="discount" class="text-[13px] md:text-base text-left text-neutral-700">
                                {{ $currency }}0.00</p>
                        </div>
                    </div>

                    <div class="pb-4 border-b border-neutral-200 ">
                        <div class="flex justify-between items-center p-2.5 rounded-lg bg-mint-50">
                            <p class="text-base md:text-lg font-semibold text-left text-neutral-700">Total</p>
                            <p id="total" class="text-base md:text-lg font-semibold text-left text-mint-700">
                                {{ $currency }}0.00</p>
                        </div>
                        <div id="minOrderWarning" class="text-red-500 text-sm mt-2 hidden">
                            Minimum order amount is {{ $currency }}{{ $store->min_order_amount }}
                        </div>

                    </div>


                    @if (Auth::check())
                        <a href="{{ route('checkout', request('store_slug')) }}" id="placeOrderBtn"
                            class=" flex justify-center items-center h-10 md:h-12 relative overflow-hidden px-5 py-3.5 rounded-xl bg-mint-600 gap-[5px]">
                            <img class="w-[14px] h-[14px] md:w-6 md:h-6" src="../assets/icons/shield-check.svg"
                                alt="">
                            <p class="flex-grow-0 flex-shrink-0 text-xs md:text-base font-semibold text-center text-white">
                                Proceed To Checkout
                            </p>
                        </a>
                    @else
                        <a href="{{ route('sign-in') }}" id="placeOrderBtn"
                            class="place-order flex justify-center items-center h-10 md:h-12 relative overflow-hidden px-5 py-3.5 rounded-xl bg-mint-600 gap-[5px]">
                            <img class="w-[14px] h-[14px] md:w-6 md:h-6" src="../assets/icons/shield-check.svg"
                                alt="">
                            <p class="flex-grow-0 flex-shrink-0 text-xs md:text-base font-semibold text-center text-white">
                                Proceed To Checkout
                            </p>
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection
<style>
    .disabled-link {
        pointer-events: none;
        /* click blocked */
        opacity: 0.5;
        /* faded look */
        cursor: not-allowed;
        /* shows not-allowed cursor */
    }
    .disabled-link {
    pointer-events: none;
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
@push('web-scripts')
    <script>
        //variant scroll bar start
        const scrollContainer = document.getElementById('variantScroll');
        const scrollLeftBtn = document.getElementById('scrollLeft');
        const scrollRightBtn = document.getElementById('scrollRight');

        const scrollAmount = 200;

        scrollLeftBtn.addEventListener('click', () => {
            scrollContainer.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        scrollRightBtn.addEventListener('click', () => {
            scrollContainer.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        const slider = document.querySelector('.variant-scroll');
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('cursor-grabbing');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('cursor-grabbing');
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('cursor-grabbing');
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });

        //variant scroll bar end

        const currency = @json($currency);
        const storeSlug = @json(request('store_slug'));
        const serviceSlug = @json(request()->segment(2));
        const searchInput = document.getElementById('search');
        const variantButtons = document.querySelectorAll('.variant-button');
        const variantProductsContainers = document.querySelectorAll('.variant-products');



        variantButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const variantId = this.dataset.variant;

                variantButtons.forEach(b => b.classList.remove('bg-mint-600'));
                this.classList.add('bg-mint-600');

                variantProductsContainers.forEach(container => {
                    container.style.display = container.dataset.variant == variantId ? 'grid' :
                        'none';
                });

                searchInput.value = '';
                filterProducts('');
            });
        });

        // Search function
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            filterProducts(query);
        });

        function filterProducts(query) {

            const allContainers = document.querySelectorAll('.variant-products');


            allContainers.forEach(container => {
                const products = container.querySelectorAll(
                    ':scope > div');

                let hasVisibleProduct = false;

                products.forEach(product => {
                    const name = product.querySelector('p.text-sm')?.textContent.toLowerCase() || '';
                    if (name.includes(query)) {
                        product.style.display = 'flex';
                        hasVisibleProduct = true;
                    } else {
                        product.style.display = 'none';
                    }
                });

                // const isActiveVariant = container.style.display === 'grid';
                // if (query) {
                //     container.style.display = hasVisibleProduct ? 'grid' : 'none';
                // } else {
                //     container.style.display = isActiveVariant ? 'grid' : 'none';
                // }
            });
        }


        function counterController(counterId, action, productId) {
            const container = document.getElementById(counterId);
            if (!container) return;

            const counterCount = container.querySelector('.current_count');

            // Get active store slug from localStorage
            const storeSlug = localStorage.getItem('activeStoreSlug');
            if (!storeSlug) {
                console.error("Store slug missing in counterController!");
                return;
            }

            const cartKey = `cart_${storeSlug}`;
            let cart = JSON.parse(localStorage.getItem(cartKey)) || [];

            const index = cart.findIndex(item => item.id == productId);
            if (index === -1) return;

            if (action === 'increase') {
                cart[index].qty += 1;
            } else if (action === 'decrease') {
                cart[index].qty -= 1;

                // Remove item if qty <= 0
                if (cart[index].qty <= 0) {
                    cart.splice(index, 1);
                    document.getElementById('add-btn-' + productId)?.classList.remove('hidden');
                    document.getElementById('controls-' + productId)?.classList.add('hidden');
                }
            }

            // Update counter only if item still exists
            if (cart[index]) {
                counterCount.innerText = cart[index].qty;
            } else {
                counterCount.innerText = 1;
            }

            // Save cart back to localStorage
            localStorage.setItem(cartKey, JSON.stringify(cart));

            // Re-render basket and header
            renderCartUI();
            renderHeaderCart();
        }




        // -------------------- CART SYSTEM --------------------

        let cart = JSON.parse(localStorage.getItem(`cart_${storeSlug}`)) || [];

        function saveCart() {
            localStorage.setItem(`cart_${storeSlug}`, JSON.stringify(cart));
        }



        // -------------------- RENDER CART --------------------
        let discountAmount = 0;




        function renderCartUI() {
            const storeSlug = localStorage.getItem('activeStoreSlug');
            if (!storeSlug) return;

            const cartKey = `cart_${storeSlug}`;
            const cart = JSON.parse(localStorage.getItem(cartKey)) || [];

            const basketList = document.getElementById("basket-items");
            const itemCount = document.getElementById("item-count");
            const subtotalField = document.getElementById("subtotal");
            const deliveryField = document.getElementById("delivery_fee");
            const delivery = document.getElementById("delivery");
            const totalField = document.getElementById("total");
            const placeOrderBtn = document.getElementById('placeOrderBtn');

            if (!basketList || !itemCount || !subtotalField || !totalField) return;

            basketList.innerHTML = "";

            let subtotal = 0;
            let totalItems = 0;
            let deliveryFee = delivery ? parseFloat(delivery.value) || 0 : 0;

            cart.forEach((item, index) => {
                const itemTotal = item.qty * item.price;
                subtotal += itemTotal;
                totalItems += item.qty;

                basketList.innerHTML += `
                <div class="flex justify-between items-center w-full">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">${item.name}</p>
                        <p class="text-xs text-neutral-500">${item.qty} × ${currency}${item.price}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <p class="text-base font-medium text-mint-600">${currency}${itemTotal.toFixed(2)}</p>
                        <i class="fa fa-trash remove-item" style="color:red" data-index="${index}"></i>
                    </div>
                </div>
            `;
            });

            itemCount.textContent = `${totalItems} items`;
            subtotalField.textContent = `${currency}${subtotal.toFixed(2)}`;
            if (deliveryField) deliveryField.textContent = `${currency}${deliveryFee.toFixed(2)}`;


            const total = subtotal - discountAmount + deliveryFee;
            totalField.textContent = `${currency}${total.toFixed(2)}`;

            const minOrderAmount = parseFloat(@json($store->min_order_amount));


            const minOrderWarning = document.getElementById('minOrderWarning');
            if (subtotal < minOrderAmount) {
                minOrderWarning.classList.remove('hidden');
                if (placeOrderBtn) placeOrderBtn.classList.add('disabled-link');
            } else {
                minOrderWarning.classList.add('hidden');
                if (placeOrderBtn) placeOrderBtn.classList.remove('disabled-link');
            }


            if (placeOrderBtn) {
                if (cart.length === 0 || subtotal < minOrderAmount) {
                    placeOrderBtn.classList.add('disabled-link');
                } else {
                    placeOrderBtn.classList.remove('disabled-link');
                }
            }
        }





        function renderHeaderCart() {
            const headerCount = document.getElementById('cartCount');
            const headerItems = document.getElementById('cartItems');

            if (!headerCount || !headerItems) return;

            const cartData = JSON.parse(localStorage.getItem(`cart_${storeSlug}`)) || [];

            let totalQty = 0;
            headerItems.innerHTML = ''; // clear existing

            cartData.forEach((item, index) => {
                totalQty += item.qty;

                headerItems.innerHTML += `
            <div class="flex justify-between items-center gap-3 py-3 border-b last:border-0">
                <div>
                    <p class="text-sm font-semibold text-neutral-700">${item.name}</p>
                    <p class="text-xs text-neutral-500">Qty: ${item.qty}</p>
                </div>
                <p class="text-sm font-medium text-neutral-700">${currency}${(item.price * item.qty).toFixed(2)}</p>

            </div>
        `;
            });

            // Update cart badge
            if (totalQty > 0) {
                headerCount.textContent = totalQty;
                headerCount.classList.remove('hidden');
            } else {
                headerCount.classList.add('hidden');
                headerItems.innerHTML = '<p class="text-sm text-neutral-500 text-center py-6">Cart is empty</p>';
            }
        }

        renderCartUI();
        renderHeaderCart();
        updateCartUIOnLoad();



        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("add-to-cart")) {

                const productId = e.target.dataset.id;

                const counterId = `card-${productId}-counter`;

                const qtyEl = document.querySelector(`#${counterId} .current_count`);
                const qty = parseInt(qtyEl.innerText);

                const product = {
                    id: e.target.dataset.id,
                    name: e.target.dataset.name,
                    price: parseFloat(e.target.dataset.price),
                    qty: 1,
                    store_slug: storeSlug,
                    service_slug: serviceSlug
                };

                addToCart(product);
            }
        });

        renderCartUI();
        renderHeaderCart();



        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-item")) {
                const storeSlug = localStorage.getItem('activeStoreSlug');
                if (!storeSlug) return;

                const cartKey = `cart_${storeSlug}`;
                let cart = JSON.parse(localStorage.getItem(cartKey)) || [];

                const index = parseInt(e.target.dataset.index);
                if (!isNaN(index)) {
                    // Get removed item ID
                    const removedItemId = cart[index].id;

                    // Remove item from cart
                    cart.splice(index, 1);

                    // Save updated cart
                    localStorage.setItem(cartKey, JSON.stringify(cart));

                    // Reset Add button & counter for this product
                    const addBtn = document.getElementById(`add-btn-${removedItemId}`);
                    const controlsDiv = document.getElementById(`controls-${removedItemId}`);
                    if (addBtn && controlsDiv) {
                        addBtn.classList.remove('hidden'); // Show Add button
                        controlsDiv.classList.add('hidden'); // Hide counter + In Cart
                        const counterCount = controlsDiv.querySelector('.current_count');
                        if (counterCount) counterCount.innerText = 1; // reset counter
                    }

                    // Re-render cart and header
                    renderCartUI();
                    renderHeaderCart();
                }
            }
        });



        document.getElementById('promo-form').addEventListener('click', function(e) {
            e.preventDefault();
            const button = this;
            const promoResponse = document.getElementById('promo-response');
            let cart = JSON.parse(localStorage.getItem(`cart_${storeSlug}`)) || [];

            if (cart.length === 0) {
                promoResponse.innerHTML =
                    '<span class="text-red-500">Please add products to your cart before applying a promo code.</span>';
                return;
            }

            const promoCodeInput = document.getElementById('promo_code').value.trim();
            const isApplied = localStorage.getItem(`couponDiscount_${storeSlug}`) ? true : false;

            if (!isApplied) {
                // ---- APPLY COUPON ----
                if (!promoCodeInput) {
                    promoResponse.innerHTML =
                        '<span class="text-red-500">Please enter a promo code.</span>';
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
                        if (response.data.status == 'success') {
                            const discount = parseFloat(response.data.discount);
                            localStorage.setItem(`couponDiscount_${storeSlug}`, discount);
                            localStorage.setItem(`promoCode_${storeSlug}`, promoCodeInput);

                            applyDiscount(discount);

                            $('#discount').text('{{ $currency }}' + discount.toFixed(2));
                            promoResponse.innerHTML =
                                '<span class="text-green-500">Coupon applied successfully!</span>';

                            // Change button to Remove
                            button.textContent = 'Remove';
                            button.classList.remove('text-neutral-500', 'border-neutral-200');
                            button.classList.add('text-white', 'bg-red-500', 'border-transparent');

                        } else {
                            promoResponse.innerHTML =
                                '<span class="text-red-500">Invalid promo code!</span>';
                        }
                    },
                    error: function() {
                        promoResponse.innerHTML =
                            '<span class="text-red-500">Something went wrong!</span>';
                    }
                });
            } else {
                // ---- REMOVE COUPON ----
                localStorage.removeItem(`couponDiscount_${storeSlug}`);
                localStorage.removeItem(`promoCode_${storeSlug}`);

                discountAmount = 0;
                renderCartUI();
                renderHeaderCart();

                $('#promo_code').val('');
                $('#discount').text('{{ $currency }}0.00');
                promoResponse.innerHTML =
                    '';

                // Change button back to Apply
                button.textContent = 'Apply';
                button.classList.remove('text-white', 'bg-red-500', 'border-transparent');
                button.classList.add('text-neutral-500', 'border-neutral-200');
            }
        });



        function applyDiscount(discount) {
            discountAmount = discount;
            renderCartUI();
            renderHeaderCart();
        }

        window.onload = function() {
            applyStoredDiscount();
        };

        function updateCartUIOnLoad() {
            cart.forEach(item => {
                const addBtn = document.getElementById('add-btn-' + item.id);
                const controlsDiv = document.getElementById('controls-' + item.id);

                if (addBtn && controlsDiv) {
                    addBtn.classList.add('hidden'); // Hide Add button
                    controlsDiv.classList.remove('hidden'); // Show counter + In Cart
                }

                // Update counter value
                const counterCount = controlsDiv?.querySelector('.current_count');
                if (counterCount) {
                    counterCount.innerText = item.qty;
                }
            });
        }


        function applyStoredDiscount() {
            const savedDiscount = localStorage.getItem(`couponDiscount_${storeSlug}`);
            const savedPromoCode = localStorage.getItem(`promoCode_${storeSlug}`);
            const promoButton = document.getElementById('promo-form');
            const promoResponse = document.getElementById('promo-response');

            if (savedDiscount) {
                applyDiscount(parseFloat(savedDiscount));
                $('#discount').text('{{ $currency }}' + parseFloat(savedDiscount).toFixed(2));
            } else {
                applyDiscount(0);
                $('#discount').text('{{ $currency }}0.00');
            }

            if (savedPromoCode) {
                $('#promo_code').val(savedPromoCode);
            } else {
                $('#promo_code').val('');
            }


            if (savedDiscount && promoButton) {
                promoButton.textContent = 'Remove';
                promoButton.classList.remove('text-neutral-500', 'border-neutral-200');
                promoButton.classList.add('text-white', 'bg-red-500', 'border-transparent');
            } else if (promoButton) {
                promoButton.textContent = 'Apply';
                promoButton.classList.remove('text-white', 'bg-red-500', 'border-transparent');
                promoButton.classList.add('text-neutral-500', 'border-neutral-200');
                promoResponse.innerHTML = '';
            }
        }


        // Listen for the "Place Order" button click
        document.querySelector('.place-order').addEventListener('click', function(event) {
            event.preventDefault();

            const currentUrl = window.location.href;

            localStorage.setItem('lastRoute', currentUrl);

            window.location.href = "{{ route('sign-in') }}";
        });





        function addToCart(product) {
            const store = product.store_slug || localStorage.getItem('activeStoreSlug');
            if (!store) {
                console.error("Store slug missing!");
                return;
            }

            const cartKey = `cart_${store}`;
            let cart = JSON.parse(localStorage.getItem(cartKey)) || [];

            const index = cart.findIndex(item => item.id == product.id);
            if (index !== -1) {
                cart[index].qty += 1;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    qty: product.qty || 1,
                    service_slug: product.service_slug || '',
                    store_slug: store
                });
            }

            localStorage.setItem('activeStoreSlug', store);
            localStorage.setItem(cartKey, JSON.stringify(cart));

            document.getElementById('add-btn-' + product.id)?.classList.add('hidden');
            document.getElementById('controls-' + product.id)?.classList.remove('hidden');

            renderCartUI();
            renderHeaderCart();
        }



        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('goToCartForm');
            const cartItems = document.getElementById('cartItems');
            const cartCount = document.getElementById('cartCount');
            const goBtn = document.getElementById('goBtn');


            if (!cartItems || !cartCount || !goBtn || !form) return;

            const store = localStorage.getItem('activeStoreSlug');
            const cart = store ? JSON.parse(localStorage.getItem(`cart_${store}`)) || [] : [];

            // Header cart count
            if (cart.length > 0) {
                const totalQty = cart.reduce((acc, item) => acc + (item.qty || 1), 0);
                cartCount.textContent = totalQty;
                cartCount.classList.remove('hidden');
            } else {
                cartCount.classList.add('hidden');
            }

            if (cart.length === 0) {
                goBtn.style.display = 'none';
                cartItems.innerHTML = `<p class="text-sm text-neutral-500 text-center py-6">Your cart is empty</p>`;
                return;
            }

            // Validate first item slugs
            const firstItem = cart[0];
            if (!firstItem.service_slug || !firstItem.store_slug) {
                goBtn.style.display = 'none';
                cartItems.innerHTML =
                    `<p class="text-sm text-neutral-500 text-center py-6">Cart item data is missing or invalid.</p>`;
                return;
            }

            // Render cart items
            cartItems.innerHTML = cart.map(item => `
        <div class="flex justify-between items-center gap-3 py-3 border-b last:border-0">
            <div>
                <p class="text-sm font-semibold text-neutral-700">${item.name ?? 'Service'}</p>
                <p class="text-xs text-neutral-500">Qty: ${item.qty ?? 1}</p>
            </div>
            <p class="text-sm font-medium text-neutral-700">₹${item.price ?? 0}</p>
        </div>
    `).join('');

            goBtn.style.display = 'block';

            // Form action dynamic set
            form.action =
                `/variant-services/${encodeURIComponent(firstItem.service_slug)}?store_slug=${encodeURIComponent(firstItem.store_slug)}`;

            // Go to Cart click
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                window.location.href = form.action;
            });
        });
    </script>
@endpush
