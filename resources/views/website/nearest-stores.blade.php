@extends('website.layout.app')
@section('content')
    <!-- breadcrumb -->
    <section
        class="rs-breadcrumb-area bg-[#1A7058] h-[260px] w-full bg-[url('../assets/images/header/breadcrumb.png')] bg-cover bg-center flex flex-col items-center justify-center text-center">
        <div class="rs-breadcrumb-content">
            <h1
                class="rs-breadcrumb-title mb-[5px] sm:mb-[10px] text-[26px] md:text-[30px]  md:text-4xl text-white font-semibold leading-[140%]">
                Find Stores Near You!
            </h1>
            <div class="rs-breadcrumb-top-content">
                <a href="{{ route('home') }}" class="text-base md:text-lg text-white font-normal leading-[100%]">Home / </a>
                <a href="#" class="text-base md:text-lg text-white font-normal leading-[100%]">Stores</a>
            </div>
        </div>
    </section>

    <!-- Services area -->
    <section class="rs-services-section pt-[60px] pb-[100px] px-4 xl:px-0 bg-neutral-50">
        <div class="rs-services-area max-w-2lg  mx-auto grid grid-cols-1 md:grid-cols-12 gap-[24px]">

            <div class="rs-find-store-area col-span-12">
                <div class="rs-services-right-top flex items-center justify-between mb-6 flex-wrap gap-4">
                    <p class="text-lg font-normal leading-[100%] text-neutral-500">Found {{ count($nearestStores) }} Stores
                    </p>
                </div>
                <div class="rs-services-wrapper grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">

                    @php
                        $user = auth()->user();
                        if ($user && Auth::user()->hasRole('customer')) {
                            $customer = auth()->user()->customer;
                            $favouriteStores = $customer->favouriteStore;
                        }

                    @endphp



                    @foreach ($nearestStores as $store)
                        <div class="rs-services-box rounded-3xl overflow-hidden bg-white">
                            <div class="rs-services-thumb overflow-hidden relative">
                                <img src="{{ $store['store']->banner?->file }}" class="object-cover w-full border-b"
                                    style="height: 250px !important;overflow:hidden" alt="Store Banner">

                                @if (auth()->check() && Auth::user()->hasRole('customer'))
                                    @php
                                        $isFavourite = $favouriteStores->contains('id', $store['store']->id);
                                    @endphp

                                    @if (auth()->check() && Auth::user()->hasRole('customer'))
                                        <a href="javascript:void(0)"
                                            class="love-icon p-[2px] w-[28px] h-[28px] bg-gray-300 backdrop-blur-[4px] bg-opacity-[.3] absolute top-[10px] right-[10px] flex items-center justify-center cursor-pointer rounded-lg"
                                            data-store-slug="{{ $store['store']->slug }}"
                                            data-is-favourite="{{ $isFavourite ? '1' : '0' }}"
                                            onclick="toggleFavourite(this)">

                                            <img class="w-[13px] h-[14px] mt-[2px]"
                                                src="{{ $isFavourite ? asset('assets/icons/love-icon.svg') : asset('assets/icons/heart.svg') }}"
                                                alt="">
                                        </a>
                                    @endif
                                @endif

                            </div>
                            <div class="rs-services-content p-4">
                                <div class="flex justify-start items-center gap-4 mb-[15px]">
                                    <div
                                        class="w-12 h-12 flex justify-center items-center border border-gray-100 rounded-lg p-1">
                                        <img src="{{ $store['store']->logo?->file }}" class="object-contain w-full h-full">
                                    </div>

                                    <div class="space-y-1">
                                        <p class="text-base font-semibold text-left text-neutral-900">
                                            {{ $store['store']->name }}
                                        </p>



                                        @php
                                            $avgRating = round($store['store']->ratings->avg('rating') * 2) / 2;
                                        @endphp

                                        <div class="flex justify-start items-center gap-1">
                                            <!-- always one star -->
                                            <img src="../assets/icons/star.svg" alt="Star">

                                            <!-- dynamic rating number -->
                                            <span class="text-sm font-medium text-left text-neutral-800">
                                                {{ $avgRating }}
                                            </span>

                                            <!-- order / review count -->
                                            <span class="text-sm text-left text-neutral-500">
                                                ({{ count($store['store']->orders) }})
                                            </span>
                                        </div>

                                    </div>
                                </div>


                               <p class="text-[13px] text-neutral-500 font-medium leading-[140%] mb-[15px]
                                        line-clamp-2 overflow-hidden"
                                    style="-webkit-box-orient: vertical; display: -webkit-box;">
                                    {{ $store['store']->description }}
                                </p>

                                <div class="flex gap-[5px] items-center pb-[15px] mb-[15px] border-b border-b-neutral-100">
                                    @foreach ($store['store']->services->take(3) as $service)
                                        <span
                                            class="text-xs text-neutral-500 font-normal leading-[24px] h-6 inline-block px-2 bg-neutral-50 rounded-sm truncate">
                                            {{ $service->name }}
                                        </span>
                                    @endforeach

                                    @if ($store['store']->services->count() > 3)
                                        <span
                                            class="text-xs text-neutral-500 font-normal leading-[24px] h-6 inline-block px-2 bg-neutral-100 rounded-sm">
                                            +{{ $store['store']->services->count() - 3 }}
                                        </span>
                                    @endif
                                </div>

                                <div class="flex flex-wrap items-center gap-[15px] mb-[15px]">
                                    <span
                                        class="text-[13px] text-neutral-500 font-normal leading-[140%] flex items-center gap-[5px] w-[44%]"><img
                                            class="w-[12px] h-[14px]" src="../assets/icons/location.svg"
                                            alt="">{{ $store['distance'] }}
                                        km</span>

                                    <span
                                        class="text-[13px] text-neutral-500 font-normal leading-[140%] flex items-center gap-[5px] w-[44%]"><img
                                            class="w-[12px] h-[14px]" src="../assets/icons/clock-gray.svg"
                                            alt="">{{ $store['store']->service_time ?? 'N/A' }}
                                        hours</span>
                                    <span
                                        class="text-[13px] text-neutral-500 font-normal leading-[140%] flex items-center gap-[5px] w-[44%]">
                                        <img class="w-[12px] h-[14px]" src="../assets/icons/truck.svg" alt="">

                                        @if ($store['store']->delivery_charge == 0)
                                            Free Delivery
                                        @else
                                            {{ $currency }} {{ $store['store']->delivery_charge }} (Delivery Fee)
                                        @endif
                                    </span>

                                    <span
                                        class="text-[13px] text-neutral-500 font-normal leading-[140%] flex items-center gap-[5px] w-[44%]"><img
                                            class="w-[12px] h-[14px]" src="../assets/icons/dolar.svg" alt="">Min
                                        {{ $currency }}{{ $store['store']->min_order_amount }}</span>
                                </div>
                                @php
                                    $url =
                                        request()->route('service') == null
                                            ? route('store-services', $store['store']->slug)
                                            : route('variant-services', request()->route('service'));

                                @endphp

                                <form action="{{ $url }}">
                                    <div class="rs-services-bottom-content flex items-center">
                                        @if ($url == route('store-services', $store['store']->slug))
                                            {{-- <input type="hidden" name="service_slug" value="{{ request()->route('service') }}"> --}}
                                        @else
                                            <input type="hidden" name="store_slug" value="{{ $store['store']->slug }}">
                                        @endif
                                        <button type="submit" <button type="submit"
                                            class="flex items-center justify-center gap-[10px]
                                                h-[48px] w-[332px]
                                                bg-white text-mint-600
                                                text-xs font-semibold leading-[133%]
                                                border border-neutral-100
                                                rounded-xl ml-auto
                                                hover:bg-mint-600 hover:text-white
                                                transition-colors duration-200">
                                            Book Now
                                            <img src="../assets/icons/green-right-arrow.svg" alt="">
                                        </button>



                                    </div>
                                </form>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
    </section>
@endsection
@push('web-scripts')
    <script>
        function toggleFavourite(element) {
    const storeSlug = element.getAttribute('data-store-slug');
    const url = "{{ route('favourite-store', ['store' => ':slug']) }}".replace(':slug', storeSlug);

    const heartIcon = "{{ asset('assets/icons/heart.svg') }}";
    const loveIcon = "{{ asset('assets/icons/love-icon.svg') }}";

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => res.json())
    .then(data => {

        // Toast
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'success',
            title: data.message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Update heart icon immediately
        const img = element.querySelector('img');
        img.src = data.is_favourite ? loveIcon : heartIcon;
        element.setAttribute('data-is-favourite', data.is_favourite ? '1' : '0');

        // **Update header favourite count in real-time**
        const countElement = document.getElementById('fav-count');
        if (countElement) {
            countElement.innerText = data.total_favourites;
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', 'Something went wrong!', 'error');
    });
}

    </script>
@endpush
