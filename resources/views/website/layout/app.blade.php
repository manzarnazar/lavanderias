<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $appSetting?->name ?? 'Ready Laundry' }}</title>
    <!-- Inter font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:wght@100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- accordion cdn  -->


    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>


    <script src="../tailwind.config.js"></script>

    <!-- custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/service.css">

    <link rel="shortcut icon" type="image/x-icon" href="{{ $appSetting?->websiteFaviconPath ?? './assets/logo/Logo.svg' }}">

    <link rel="stylesheet" href="../css/home.css">


</head>

<body>

    @include('website.layout.partials.header')
    @include('website.layout.partials.sidebar')

    <main style="background: #F9FAFB">
        <form id="locationForm" method="POST" action="">
            @csrf
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">
        </form>
        @yield('content')
    </main>

    @include('website.layout.partials.footer')


    @stack('web-scripts')

    <script src="../js/order-details.js"></script>
    <script src="../js/sidebar.js"></script>
    <script src="../js/modal.js"></script>
    <script src="../js/select-dropdown.js"></script>
    <script src="{{ asset('web/js/sweet-alert.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        if ({{ session('success') ? 'true' : 'false' }}) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            })
        } else if ({{ session('error') ? 'true' : 'false' }}) {
            Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                title: '{{ session('error') }}',
                showConfirmButton: false,
                timer: 2000,
            })
        }


        document.addEventListener('DOMContentLoaded', async function() {
            if (!("geolocation" in navigator)) {
                console.log("Geolocation not supported by this browser.");
                return;
            }

            try {
                // Check the permission state
                const permission = await navigator.permissions.query({
                    name: 'geolocation'
                });

                const requestLocation = () => {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            document.getElementById("latitude").value = position.coords.latitude;
                            document.getElementById("longitude").value = position.coords.longitude;
                        },
                        function(error) {
                            console.error("Error getting location:", error.message);
                            if (error.code === 1) { // PERMISSION_DENIED
                                alert(
                                    "Location permission denied. Please enable location in your browser settings to submit your location."
                                );
                            }
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );
                };

                if (permission.state === 'granted') {
                    requestLocation();
                } else if (permission.state === 'prompt') {
                    requestLocation();
                } else if (permission.state === 'denied') {
                    const lastDenied = localStorage.getItem('locationDeniedTime');
                    const now = Date.now();

                    // Check if 1 hour has passed (3600000 ms)
                    if (!lastDenied || now - lastDenied > 3600000) {
                        alert(
                            "You previously denied location access. Please enable it in your browser settings to submit your location."
                        );

                        // Store the current time
                        localStorage.setItem('locationDeniedTime', now);
                    }
                    // alert(
                    //     "You previously denied location access. Please enable it in your browser settings to submit your location."
                    // );
                }

                permission.onchange = () => {
                    console.log("Geolocation permission state changed to:", permission.state);
                    if (permission.state === 'granted') requestLocation();
                };

            } catch (err) {
                console.error("Permissions API not supported, fallback to direct prompt.", err);
                navigator.geolocation.getCurrentPosition(console.log, console.error);
            }
        });

        let selectedServiceSlug = null;
        let store_slug = null;

        document.addEventListener("click", function(e) {
            const btn = e.target.closest(".getLocationBtn");
            if (!btn) return;
            e.preventDefault();

            selectedServiceSlug = btn.dataset.service || null;
            store_slug = btn.dataset.store || null;

            const form = document.getElementById("locationForm");

            let storeInput = form.querySelector("input[name='store_slug']");
            let csrfInput = form.querySelector("input[name='_token']");
            let latitudeInput = form.querySelector("input[name='latitude']");
            let longitudeInput = form.querySelector("input[name='longitude']");

            if (selectedServiceSlug && store_slug) {
                form.method = "GET";
                form.action = "/variant-services/";
                form.action +=
                    `${encodeURIComponent(selectedServiceSlug)}?store_slug=${encodeURIComponent(store_slug)}`;
                if (!storeInput) {
                    storeInput = document.createElement("input");
                    storeInput.type = "hidden";
                    storeInput.name = "store_slug";
                    form.appendChild(storeInput);
                }
                storeInput.value = store_slug || '';
                if (csrfInput) {
                    form.removeChild(
                        csrfInput);
                }
                if (latitudeInput) {
                    form.removeChild(latitudeInput);
                }
                if (longitudeInput) {
                    form.removeChild(longitudeInput);
                }
            } else if (selectedServiceSlug) {
                form.action = `/services/${selectedServiceSlug}`;
            } else {
                form.action = `/nearest-stores?latitude=${latitudeInput}&longitude=${longitudeInput}`;
                form.method = "GET";
            }

            form.submit();
        });

    </script>




    <script>



document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('goToCartForm');
    const cartItems = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');
    const goBtn = document.getElementById('goBtn');

    // Currency dynamically
    const currency = localStorage.getItem('currency') || '$';

    // Active store slug from localStorage
    const store = localStorage.getItem('activeStoreSlug');
    const cart = store ? JSON.parse(localStorage.getItem(`cart_${store}`)) || [] : [];

    // Update header cart count
    if (cart.length > 0) {
        const totalQty = cart.reduce((acc, item) => acc + (item.qty || 1), 0);
        cartCount.textContent = totalQty;
        cartCount.classList.remove('hidden');
    } else {
        cartCount.classList.add('hidden');
    }

    // Render cart items
    if (cart.length === 0) {
        goBtn.style.display = 'none';
        cartItems.innerHTML = `<p class="text-sm text-neutral-500 text-center py-6">Your cart is empty</p>`;
        return;
    }

    const firstItem = cart[0];
    if (!firstItem.service_slug || !firstItem.store_slug) {
        goBtn.style.display = 'none';
        cartItems.innerHTML = `<p class="text-sm text-neutral-500 text-center py-6">Cart item data is missing or invalid.</p>`;
        return;
    }

    cartItems.innerHTML = cart.map(item => `
        <div class="flex justify-between items-center gap-3 py-3 border-b last:border-0">
            <div>
                <p class="text-sm font-semibold text-neutral-700">${item.name ?? 'Service'}</p>
                <p class="text-xs text-neutral-500">Qty: ${item.qty ?? 1}</p>
            </div>
            <p class="text-sm font-medium text-neutral-700">${currency}${(item.price ?? 0).toFixed(2)}</p>
        </div>
    `).join('');

    goBtn.style.display = 'block';

    form.action = `/variant-services/${encodeURIComponent(firstItem.service_slug)}?store_slug=${encodeURIComponent(firstItem.store_slug)}`;

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        window.location.href = form.action;
    });
});


    </script>


</body>

</html>
