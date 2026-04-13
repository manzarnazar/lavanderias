@extends('website.layout.app')
@section('content')
    <style>
        .service-item.active {
            background: #eff6ff;
            border-radius: 6px;
        }
        .rs-breadcrumb-area {
            background-color: #005AA0 !important;
        }
        .rs-services-box:hover {
            border-color: #006CBA !important;
        }
        .rs-services-box:hover .getLocationBtn {
            background: #006CBA !important;
            border-color: #006CBA !important;
            color: #ffffff !important;
        }
        .rs-services-box:hover .getLocationBtn img {
            filter: brightness(0) invert(1);
        }
    </style>
    <!-- breadcrumb -->
    <section
        class="rs-breadcrumb-area bg-[#005AA0] h-[260px] w-full bg-[url('../assets/images/header/breadcrumb.png')] bg-cover bg-center flex flex-col items-center justify-center text-center">
        <div class="rs-breadcrumb-content">
            <h1
                class="rs-breadcrumb-title mb-[5px] sm:mb-[10px] text-[26px] md:text-[30px]  md:text-4xl text-white font-semibold leading-[140%]">
                All Services
            </h1>
            <div class="rs-breadcrumb-top-content">
                <a href="{{ route('home') }}" class="text-base md:text-lg text-white font-normal leading-[100%]">Home / </a>
                <a href="#" class="text-base md:text-lg text-white font-normal leading-[100%]">Services</a>
            </div>
        </div>
    </section>

    <!-- Services area -->
    <section class="rs-services-section pt-[60px] pb-[100px] px-4 xl:px-0 bg-neutral-50">
        <div class="rs-services-area max-w-2lg  mx-auto grid grid-cols-1 md:grid-cols-12 gap-[24px]">

            <!-- Left Column -->
            <div class="rs-services-left-area hidden lg:block col-span-12 md:col-span-4 lg:col-span-3">
                <div class="rs-services-filter-area bg-white py-6 px-4 rounded-xl">
                    <h3
                        class="rs-services-filter-title text-sm md:text-base text-neutral-900 font-semibold leading-[140%] mb-4">
                        Filter by
                    </h3>
                    <input type="hidden" id="store-slug" value="{{ $store->slug }}">

                    <form action="#" class="mb-4">
                        <label class="text-xs font-medium leading-[120%] text-neutral-700">Search</label>
                        <div class="relative flex items-center">
                            <img class="absolute rs-filter-search-icon" src="../assets/icons/search-icon.svg"
                                alt="">
                            <input id="service-search"
                                class="promo-input filter-input border-[1.50px] mt-[5px] border-neutral-100 leading-[41px] rounded-xl h-[40px] w-full px-[16px] pl-[42px]"
                                type="text" placeholder="Search services ...">
                        </div>
                    </form>
                    <div class="rs-services-cta">
                        <h4 class="text-xs font-medium leading-[120%] text-neutral-700 mb-[10px]">
                            Service
                        </h4>

                        <ul>
                            <ul>
                                <li class="service-item active">
                                    <a href="#" class="truncate service-filter " data-name="all">
                                        All Services
                                    </a>
                                </li>
                                @foreach ($serviceData as $data)
                                    <li class="service-item">
                                        <a href="#" class="truncate service-filter" data-name="{{ $data->slug }}">
                                            {{ $data->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="rs-services-right-area col-span-12 md:col-span-12 lg:col-span-9">
                <div class="rs-services-right-top flex items-center justify-between mb-6 flex-wrap gap-4">
                    <p id="services-count" class="text-lg font-normal leading-[100%] text-neutral-500">Showing
                        {{ count($serviceData) > 0 ? count($serviceData) : 0 }} services</p>
                    <!-- filter sidebar opening button  -->
                    <button
                        class="h-10 w-10 border border-primary2-600 rounded p-1 flex justify-center items-center ml-auto block lg:hidden"
                        onclick="toggleFilterSidebar()">
                        <i class="fa-solid fa-filter"></i>
                    </button>

                </div>
                <div
                    id="services-wrapper"class="rs-services-wrapper grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-6">

                    @foreach ($serviceData as $data)
                        <div class="rs-services-box rounded-3xl overflow-hidden bg-white">
                            <div class="rs-services-thumb overflow-hidden relative" style="width:16.5rem; height:12rem">
                                <img class="object-cover w-full"
                                    src="{{ asset($data->thumbnailPath) ?? '../assets/images/service/service-01.jpg' }}"
                                    alt="">

                            </div>
                            <div class="rs-services-content p-4">

                                <h3 class="text-sm text-neutral-900 font-semibold leading-[140%] mb-[15px] truncate">
                                    {{ $data->name }}
                                </h3>
                                <p class="text-[13px] text-neutral-500 font-medium leading-[140%] mb-[15px] line-clamp-2">
                                    {{ $data->description }}
                                </p>

                                <p class="text-xs text-neutral-500 font-normal leading-[100%] mb-[15px]">{{ $store->name }}
                                </p>

                                 <div  class="rs-services-bottom-content flex items-center justify-end">
                                    <button type="submit" data-service="{{ $data->slug }}" data-store={{ request()->route('store')}}
                                        class="getLocationBtn flex items-center justify-center gap-[10px] ml-auto text-xs text-mint-600 font-semibold leading-[133%] h-[32px] w-[110px] border-[1.50px] border-neutral-100 rounded-xl">Book
                                        Now <img src="../assets/icons/green-right-arrow.svg" alt=""></button>
                                </div>
                            </div>
                        </div>
                    @endforeach



                </div>
            </div>
        </div>

    </section>


@endsection
@push('web-scripts')
    <script>
        const input = document.getElementById('service-search');
        const wrapper = document.getElementById('services-wrapper');
        const counter = document.getElementById('services-count');
        const storeSlug = document.getElementById('store-slug').value;
        const assetBaseUrl = "{{ asset('') }}";
        let timer;

        // reusable function
        function performSearch(query) {

         fetch(`/search-services/${storeSlug}?q=${query}`)

                .then(res => res.json())
                .then(data => {

                    counter.textContent = `Showing ${data.length} services`;

                    let html = "";
                    if (data.length === 0) {
                        html = `<p class="text-neutral-500 text-sm col-span-3">No services found</p>`;
                    } else {
                        data.forEach(service => {
                            html += `
                            <div class="rs-services-box rounded-3xl overflow-hidden bg-white">
                                <div class="rs-services-thumb  overflow-hidden relative" style="width:16.5rem; height:12rem">
                                    <img
                                        class="object-contain w-full"
                                        src="${service.thumbnail_path}"
                                        alt=""
                                    >
                                </div>

                                <div class="rs-services-content p-4">
                                    <h3 class="text-sm text-neutral-900 font-semibold leading-[140%] mb-[15px] truncate">
                                        ${service.name}
                                    </h3>
                                    <p class="text-[13px] text-neutral-500 font-medium leading-[140%] mb-[15px] line-clamp-2">
                                        ${service.description ?? ""}
                                    </p>
                                    <p class="text-xs text-neutral-500 font-normal leading-[100%] mb-[15px]">
                                        ${service.store_name ?? ""}
                                    </p>
                                    <div class="rs-services-bottom-content flex items-center">
                                        <button type="button" data-service="${service.slug}" data-store={{ request()->route('store')}} class="getLocationBtn flex items-center justify-center gap-[10px] ml-auto text-xs text-mint-600 font-semibold leading-[133%] h-[32px] w-[110px] border-[1.50px] border-neutral-100 rounded-xl">Book
                                        Now <img src="../assets/icons/green-right-arrow.svg" alt=""></button>
                                    </div>
                                </div>
                            </div>
                        `;
                        });
                    }

                    wrapper.innerHTML = html;
                });
        }

        input.addEventListener('keyup', function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                performSearch(this.value.trim());
            }, 300);
        });


        document.querySelectorAll('.service-filter').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                const name = this.dataset.name;

                document.querySelectorAll('.service-item').forEach(li => {
                    li.classList.remove('active');
                });

                this.closest('.service-item').classList.add('active');

                if (name === 'all') {
                    input.value = '';
                    performSearch('');
                } else {
                    input.value = '';
                    performSearch(name);
                }
            });
        });
    </script>
@endpush
