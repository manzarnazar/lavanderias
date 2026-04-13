@extends('website.layout.app')
@section('content')
    <style>
        .qr-code svg {
            height: 5rem;
            width: 5rem
        }

        .qr-code {
            width: 6rem;
        }
    </style>

    <!-- header section -->
    <section style="padding-top:4rem">
        <div class="grid grid-cols-1 lg:grid-cols-2 max-w-2lg mx-auto  gap-[50px] lg:gap-[100px]  px-4 xl-1:px-0">
            <div class="flex flex-col justify-center order-2 md:order-1">
                @php
                    $header = $webSettings->firstWhere('key', 'header')->decoded_value ?? null;

                    // strip tags & remove &nbsp;
                    $title = strip_tags($header->title ?? '');
                    $title = str_replace('&nbsp;', ' ', $title);

                    // find position of "and" (case-insensitive)
                    $pos = stripos($title, 'and');

                    if ($pos !== false) {
                        // split at "and"
                        $lineOne = trim(substr($title, 0, $pos + 3)); // include "and"
                        $lineTwo = trim(substr($title, $pos + 3));
                    } else {
                        // if "and" not found, put everything in line 1
                        $lineOne = $title;
                        $lineTwo = '';
                    }
                @endphp





                <div class="space-y-3 md:space-y-5">
                    <div class="flex justify-center lg:justify-start">
                        <div
                            class="flex justify-center items-center gap-1 w-fit bg-mint-50 px-2 p-2 border-[1.5px] border-mint-200 rounded-[25px]">
                            <img src="./assets/icons/star.svg" alt="">
                            <p class="text-[10px] sm:text-xs font-medium text-left text-mint-700">
                                Trusted by {{ $customers->count() }}+ Happy Customers
                            </p>
                        </div>
                    </div>

                    <p class="text-2xl md:text-5xl text-center lg:text-left leading-tight space-y-1">
                        @if ($lineOne)
                            <span class="font-bold text-gray-900">
                                {{ $lineOne }}
                            </span>
                        @endif

                        @if ($lineTwo)
                            <span class="font-playfair">
                                {{ $lineTwo }}
                            </span>
                        @endif
                    </p>


                    <p class="text-lg md:text-2xl text-center lg:text-left text-neutral-500">
                        {{ $header->description ?? '' }}
                    </p>
                </div>

                <div class="space-y-6 mt-4 md:mt-9">
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start items-center gap-4">
                        <a class="btn_solid getLocationBtn cursor-pointer">
                            <p>Find Stores Near You</p>
                        </a>
                    </div>

                    <div
                        class="flex flex-col md:flex-row justify-center lg:justify-start items-start md:items-center gap-2 md:gap-5">
                        <div class="flex justify-start items-center gap-2">
                            <img src="./assets/icons/calendar.svg" alt="" class="w-4 h-4">
                            <p class="text-sm md:text-base text-left text-neutral-500">Same-day service</p>
                        </div>

                        <div class="flex justify-start items-center gap-2">
                            <img src="./assets/icons/map-pin.svg" alt="" class="w-4 h-4">
                            <p class="text-sm md:text-base text-left text-neutral-500">{{ $shops->count() }}+
                                vendors</p>
                        </div>
                    </div>
                </div>
            </div>
            @php
                $headerPath = Str::startsWith($header->header_img, 'assets/')
                    ? asset($header->header_img)
                    : asset('storage/' . $header->header_img);
            @endphp
            <div class="relative flex justify-center lg:justify-end order-1 md:order-2">
                <img src="{{ $headerPath }}" alt="" class="">

                <div class="absolute left-0 bottom-0 -translate-y-10">
                    <div class="flex flex-col justify-center items-center w-[189px] h-32 gap-2.5 p-[15px] rounded-xl bg-white/90 backdrop-blur-md overflow-hidden"
                        style="box-shadow: 0px 16px 32px -4px rgba(199,246,229,0.2), 0px 4px 4px -4px rgba(66,224,169,0.1);">
                        <div
                            class="flex flex-col justify-center items-center flex-grow-0 flex-shrink-0 w-[122px] gap-[5px]">
                            @php
                                if ($ratings->sum('rating') == 0) {
                                    $avgRating = 0;
                                    $fullStars = 0;
                                    $halfStar = 0;
                                } else {
                                    $avgRating = round(($ratings->sum('rating') / $ratings->count()) * 2) / 2;
                                    $fullStars = floor($avgRating);
                                    $halfStar = $avgRating - $fullStars == 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                }

                            @endphp
                            <div class="flex justify-start items-center w-fit h-4 relative gap-0.5">
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <img src="../assets/icons/star.svg" alt="Full Star">
                                @endfor
                                @if ($halfStar)
                                    <img src="../assets/icons/star-half.svg" alt="Half Star">
                                @endif
                            </div>

                            <div class="flex justify-start items-center self-stretch flex-grow-0 flex-shrink-0 gap-1.5">
                                <div class="flex justify-start items-center flex-grow-0 flex-shrink-0 relative gap-[3px]">

                                    <p class="flex-grow-0 flex-shrink-0 text-sm font-medium text-left text-gray-800">
                                        {{ $avgRating }}</p>
                                    <p class="flex-grow-0 flex-shrink-0 text-sm text-left text-gray-500">
                                        ({{ $ratings->count() }}+ reviews)</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex justify-start items-center flex-grow-0 flex-shrink-0 w-[110px] h-[43px] space-x-[-8px] overflow-hidden relative">
                            @if (!empty($header->trusted_client_image_group))
                                @foreach ($header->trusted_client_image_group as $img)
                                    @php
                                        if (!$img->img) {
                                            $imgPath = asset('assets/images/documents.png');
                                        } elseif (Str::startsWith($img->img, ['http://', 'https://'])) {
                                            $imgPath = $img->img;
                                        } elseif (Str::startsWith($img->img, 'assets/')) {
                                            $imgPath = asset($img->img);
                                        } else {
                                            $imgPath = asset('storage/' . $img->img);
                                        }
                                    @endphp

                                    <img class="w-[42px] h-[42px] object-cover border border-white rounded-full"
                                        src="{{ $imgPath }}" alt="Half Star">
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- permium services -->
    <section class="section_container max-w-2lg mx-auto px-4 xl-1:px-0">
        @php
            $premiumService = $webSettings->firstWhere('key', 'premium_services')->decoded_value ?? null;
        @endphp
        <header class="heading_section">
            <p>{!! $premiumService->title ?? '' !!}</p>
            <p>{{ $premiumService->sub_title ?? '' }}</p>
        </header>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl-1:grid-cols-4 gap-6">

            <!-- card -->

            @foreach ($services as $service)
                <div
                    class="flex flex-col p-4 rounded-3xl transition-all duration-200 outline outline-transparent outline-1 hover:outline-offset-2 hover:outline-mint-600 hover:shadow-xl hover:shadow-mint-100 bg-white shadow-sm">
                    <div>
                        <div class="icon_labels">
                            <img src="{{ asset($service->thumbnailPath) }}" alt="">
                        </div>
                    </div>
                    <div class="space-y-[15px] my-[15px]">
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $service->name ?? '' }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $service->description ?? '' }}
                        </p>

                        <ul class="list-disc list-inside space-y-[10px]">
                            @php
                                $minPrice = null;

                                foreach ($service->variants as $variant) {
                                    foreach ($variant->products as $product) {
                                        if ($minPrice === null || $product->price < $minPrice) {
                                            $minPrice = $product->price;
                                        }
                                    }
                                }
                            @endphp


                            @foreach ($service->additionalServices as $additional)
                                <li class="text-xs text-left text-gray-500 marker:text-mint-500">
                                    {{ $additional->title ?? '' }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="flex justify-between border-t pt-5 border-gray-100 mt-auto">
                        <div class="w-full">
                            <p class="text-[10px] font-medium text-left text-gray-700">
                                Starts From
                            </p>
                            <p class="text-base font-semibold text-left text-mint-600">
                                {{ $currency }}{{ number_format($minPrice, 2) }}/Item
                            </p>
                        </div>

                        <button data-service="{{ $service->slug }}"
                            class=" getLocationBtn w-full flex justify-center items-center gap-2 px-3 py-2 rounded-lg transition-all duration-150 border-[1.5px] border-gray-100 group hover:bg-gradient-to-t from-mint-200 to-mint-700">
                            <p
                                class="transition-all duration-150 text-xs font-semibold text-center text-mint-600 group-hover:text-white">
                                Book Service
                            </p>
                            <img src="./assets/icons/arrow-left-green.svg"
                                class="w-[10px] h-[10px] group-hover:hidden block">
                            <img src="./assets/icons/arrow-left.svg" class="w-[10px] h-[10px] group-hover:block hidden">
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- premium laundry services -->
    <section
        class="section_container max-w-2lg mx-auto px-4 xl-1:px-0 py-10  xl-1:rounded-3xl bg-gradient-to-tr from-mint-900 to-black relative overflow-hidden">
        @php
            $experienceService = $webSettings->firstWhere('key', 'experience_services')->decoded_value ?? null;
        @endphp
        <div class="absolute top-0 right-0 w-full h-full bg-no-repeat bg-cover bg-center opacity-50 z-0"
            style="background-image: url('./assets/images/common/bg.png');"></div>
        <header class="relative z-10 space-y-[10px]">
            <p class="text-2xl md:text-[32px] text-center text-white">
                {!! $experienceService->title ?? ('' ?? '') !!}
            </p>
            <p class=" text-sm md:text-base text-center text-neutral-50">
                {{ $experienceService->sub_title ?? '' }}
            </p>
        </header>
        <div class="relative z-10 flex flex-col sm:flex-row justify-center items-center gap-4">
            <button class="btn_solid getLocationBtn">
                <p>Get Started Now</p>
                <img src="./assets/icons/arrow-left.svg" alt="">
            </button>
            {{-- <button class="btn_outline">
                <p>Learn More</p>
            </button> --}}
        </div>
    </section>

    <!-- how it works -->
    <section id="how-it-works"
        class="max-w-2lg mx-auto px-4 xl-1:px-0 grid grid-cols-1 md:grid-cols-2 gap-5 xl:gap-[66px] mb-10">
        @php
            $howItWork = $webSettings->firstWhere('key', 'how_it_works')->decoded_value ?? null;
        @endphp
        <div class="space-y-5 xl:space-y-10 order-2 md:order-1">
            <div class="space-y-1 md:space-y-[10px]">
                <p class="text-center md:text-start text-lg xl:text-2xl text-neutral-500">How It Works</p>
                <p class="text-center  md:text-start text-2xl xl:text-[32px] font-semibold  text-neutral-900">
                    {!! $howItWork->title ?? '' !!}</p>
            </div>
            <!-- cards -->
            <div class="space-y-4">
                @foreach ($howItWork->work_steps as $step)
                    <div class="w-full p-4 space-y-4 rounded-2xl shadow-lg shadow-mint-100 bg-white">
                        <button class="h-9 w-14 relative gap-2.5  rounded-[56px] bg-mint-600">
                            <p class="text-lg font-semibold text-center text-white">{{ $step->number ?? '' }}</p>
                        </button>
                        <div>
                            <p class="text-lg font-semibold text-gray-800">{!! $step->title ?? '' !!}</p>
                            <p class="text-sm text-left text-neutral-500">{{ $step->sub_title ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex items-end order-1 md:order-2">
            @php
                $rightSideImage = Str::startsWith($howItWork->right_side_img, 'assets/')
                    ? asset($howItWork->right_side_img)
                    : asset('storage/' . $howItWork->right_side_img);
            @endphp
            <img src="{{ $rightSideImage }}" alt="" class="min-h-[90%] w-full object-cover rounded-3xl">
        </div>
    </section>

    <!-- top rated stores -->
    <section class="section_container max-w-2lg mx-auto px-4 xl-1:px-0">
        <header class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="space-y-4">
                <div class="flex justify-center md:justify-start">
                    <div
                        class="flex justify-center items-center gap-1 w-fit bg-mint-50 px-2 p-2 border-[1.5px] border-mint-200 rounded-[25px]">
                        <img src="./assets/icons/star.svg" alt="">
                        <p class="text-[10px] sm:text-xs font-medium text-left text-mint-700"> Trusted by
                            {{ $customers->count() }}+ Happy Customers</p>
                    </div>
                </div>
                <div class="heading_section_2">
                    <p>Top Rated <span>Stores</span></p>
                    <p>Choose from our verified partner stores near you</p>
                </div>
            </div>
            <button
                class="getLocationBtn px-4 py-3 md:px-5 md:py-3.5 rounded-xl flex justify-center items-center gap-3 border-[1.5px] border-gray-200">
                <p class="text-xs md:text-base font-semibold text-center text-gray-500">View All Stores</p>
                <img class="h-3 w-3" src="./assets/icons/arrow-left-gray.svg" alt="">
            </button>
        </header>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl-1:grid-cols-4 gap-6">
            @if (!$topStores->isEmpty())
                @foreach ($topStores as $store)
                    <div
                        class="flex flex-col p-4 rounded-3xl transition-all duration-200 outline outline-transparent outline-1 hover:outline-offset-2 hover:outline-mint-600  bg-white">
                        <div class="flex justify-start gap-4">
                            <div class="w-12 h-12 flex justify-center items-center border border-gray-100 rounded-lg p-1">
                                <img class="object-contain w-full h-full" src="{{ $store->banner?->file }}"
                                    alt="">
                            </div>
                            <div class="space-y-1">
                                <p class="text-base font-semibold text-left text-neutral-900">{{ $store->name }}</p>
                                @php
                                    $avgRating = round($store->ratings->avg('rating') * 2) / 2;
                                    $fullStars = floor($avgRating);
                                    $halfStar = $avgRating - $fullStars == 0.5;
                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                @endphp

                                <div class="flex justify-start items-center gap-1">
                                    {{-- Always show a single full star --}}
                                    <img src="../assets/icons/star.svg" alt="Star" class="h-4 w-4">

                                    {{-- Display the average rating number --}}
                                    <span
                                        class="text-sm font-medium text-left text-neutral-800">{{ $avgRating }}</span>

                                    {{-- Display order count --}}
                                    <span class="text-sm text-left text-neutral-500">({{ count($store->orders) }})</span>
                                </div>

                            </div>
                        </div>
                        <div class=" my-[15px] flex items-center gap-1">

                            @foreach ($store->services->take(3) as $service)
                                <span
                                    class="text-xs text-neutral-500 font-normal leading-[24px] h-6 inline-block px-2 bg-neutral-50 rounded-sm truncate">{{ $service->name ?? '' }}</span>
                            @endforeach
                        </div>
                        <div class="flex flex-col border-t border-gray-100 mt-auto">

                            <div class="flex flex-wrap items-center gap-[15px] mb-[15px]">
                                <span
                                    class="text-[13px] text-neutral-500 font-normal leading-[140%] flex items-center gap-[5px] w-[44%]">
                                    <img class="w-[12px] h-[14px]" src="../assets/icons/location.svg" alt="img">
                                    <span class="flex items-center">
                                      <span class="max-w-[75px] truncate">
                                          {{ $store->address->area ?? 'N/A' }}
                                      </span>
                                      <span class="flex w-[5px] h-[5px] bg-[#6b7280] rounded-full ml-[5px] mr-[5px]"></span>
                                        <span class="whitespace-nowrap">
                                            {{ $store->distance ? number_format($store->distance, 2) . ' km' : 'N/A' }}
                                        </span>
                                    </span>

                                </span>

                                <span
                                    class="text-[13px] text-neutral-500 font-normal leading-[140%] flex items-center justify-end gap-[5px] w-[44%]"><img
                                        class="w-[12px] h-[14px]" src="../assets/icons/clock-gray.svg"
                                        alt="">{{ $store->service_time ?? 'N/A' }}
                                    hours</span>


                            </div>

                            <a href="{{ route('store-services', $store->slug) }}"
                                class=" w-full flex justify-center items-center gap-2 px-3 py-2 rounded-lg transition-all duration-150 border-[1.5px] border-mint-600 group hover:bg-mint-600">
                                <p
                                    class="transition-all duration-150 text-xs font-semibold text-center text-mint-600 group-hover:text-white">
                                    View Details</p>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-xs md:text-base font-semibold text-center text-gray-500">No store available</p>
            @endif
        </div>
    </section>

    <!-- Build on trust -->
    <section class="section_container max-w-2lg mx-auto px-4 xl-1:px-0">
        @php
            $buildOnTrust = $webSettings->firstWhere('key', 'build_on_trust')->decoded_value ?? null;
        @endphp
        <header class="heading_section">
            <p>{!! $buildOnTrust->title ?? '' !!}</p>
            <p>{{ $buildOnTrust->sub_title ?? '' }}</p>
        </header>
        <div class="grid grid-cols-2 md:grid-cols-3 xl-1:grid-cols-4 gap-6">
            @foreach ($buildOnTrust->sample as $sample)
                <div class="flex items-center flex-col justify-start">
                    <div
                        class="bg-gradient-to-tr from-mint-600 to-mint-700 p-4 sm:p-5 rounded-2xl shadow-lg shadow-mint-200">
                        @php
                            if (!$sample->icon) {
                                $imgPath = asset('assets/images/documents.png');
                            } elseif (Str::startsWith($sample->icon, ['http://', 'https://'])) {
                                $imgPath = $sample->icon;
                            } elseif (Str::startsWith($sample->icon, 'assets/')) {
                                $imgPath = asset($sample->icon);
                            } else {
                                $imgPath = asset('storage/' . $sample->icon);
                            }
                        @endphp
                        <img class="h-5 w-5 sm:h-8 sm:w-8" src="{{ $imgPath }}" alt="">
                    </div>
                    <p class="text-sm sm:text-base font-semibold text-center text-neutral-800 mb-[10px] mt-[24px]">
                        {!! $sample->title ?? '' !!}</p>
                    <p class="text-xs sm:text-sm text-center text-neutral-500">{{ $sample->description ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- promise of perfection -->
    <section
        class="section_container max-w-2lg mx-auto px-4 xl-1:px-0   grid grid-cols-1 md:grid-cols-2 relative overflow-hidden">
        @php
            $ourPromise = $webSettings->firstWhere('key', 'our_promise')->decoded_value ?? null;
            $backgroundPath = Str::startsWith($ourPromise->background_image, 'assets/')
                ? asset($ourPromise->background_image)
                : asset('storage/' . $ourPromise->background_image);
            $sidePath = Str::startsWith($ourPromise->side_image, 'assets/')
                ? asset($ourPromise->side_image)
                : asset('storage/' . $ourPromise->side_image);
        @endphp
        <div class="relative z-10 flex justify-center md:justify-end items-center order-2 md:order-1">
            <img src="{{ $sidePath }}" alt="">
        </div>
        <div
            class="flex flex-col justify-center items-center md:items-start relative z-10 md:mt-14 order-1 md:order-2 pt-4 md:pt-0">
            <div class="space-y-[10px]">
                <p class="text-2xl lg:text-[32px] text-start font-semibold text-white">{!! $ourPromise->title ?? '' !!}</p>
                <p class="text-base lg:text-xl text-left text-neutral-50">{{ $ourPromise->sub_title ?? '' }}</p>
            </div>
            <div class="flex flex-col md:flex-row justify-start items-center gap-4 mt-4 md:mt-10">
                <button class=" getLocationBtn btn_solid">
                    <p>Book Your Order</p>
                    <img src="./assets/icons/arrow-left.svg" alt="">
                </button>
                {{-- <button class="btn_outline">
                    <p>Learn More</p>
                </button> --}}
            </div>
        </div>

        <div class="absolute bottom-0 right-0 w-full h-full md:h-[80%] z-0 bg-mint-900 md:rounded-3xl bg-no-repeat bg-cover bg-center"
            style="background-image: url('{{ asset($backgroundPath) }}');"></div>
    </section>

    <style>
        .join-network-card .icon_labels {
            background: #dbeafe !important;
        }
        .join-network-card .icon_labels img {
            /* Force blue icon tint even if uploaded assets are green */
            filter: brightness(0) saturate(100%) invert(25%) sepia(95%) saturate(2139%) hue-rotate(194deg) brightness(93%) contrast(101%);
        }
        .join-network-card:hover .icon_labels {
            background: #60a5fa !important;
        }
        .join-network-card:hover .icon_labels img {
            filter: brightness(0) invert(1) !important;
        }
    </style>
    <section class="bg-cover bg-no-repeat relative py-[50px] md:py-[100px]"
        style="background-image: url('./assets/images/stores-network/bg.png');">
        <div class="absolute top-0 right-0 w-full h-full z-0 bg-[#006CBA]/5"></div>
        <section
            class=" max-w-2lg mx-auto px-4 xl-1:px-0 relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-[70px]">
            <div class="flex flex-col justify-center">
                @php
                    $joinOurNetwork = $webSettings->firstWhere('key', 'join_our_network')->decoded_value ?? null;
                @endphp
                <div>
                    <p class="headline text-left">{!! $joinOurNetwork->title ?? '' !!}</p>
                    <p class="text-base lg:text-lg font-medium text-left text-neutral-700">
                        {{ $joinOurNetwork->description ?? '' }}</p>
                </div>
                <ul class="pt-6 pb-10 space-y-4">
                    @foreach ($joinOurNetwork->lists as $list)
                        <li class="flex justify-start items-center gap-[10px]">
                            <img src="./assets/icons/check-solid.svg" alt="" class="h-[18px] w-[18px]">
                            <p class="text-base text-left text-gray-600">{{ $list->list ?? '' }} </p>
                        </li>
                    @endforeach
                </ul>

                <div class="flex flex-col md:flex-row justify-start items-center gap-[15px]">
                    <a class="btn_solid cursor-pointer" href="{{ route('register-vendor') }}">
                        <p>Start Your Application</p>
                        <img src="./assets/icons/arrow-left.svg" alt="">
                    </a>
                    {{-- <button class="btn_outline !bg-white">
                        <p>Learn More</p>
                    </button> --}}
                </div>
            </div>

            <div class="hidden lg:grid grid-cols-2 gap-7">
                @foreach ($joinOurNetwork->facilities as $index => $facility)
                    @if ($index % 2 === 0)
                        <div class="grid grid-cols-1 gap-7">
                            @php
                                if (!$facility->icon) {
                                    $imgPath = asset('assets/images/documents.png');
                                } elseif (Str::startsWith($facility->icon, ['http://', 'https://'])) {
                                    $imgPath = $facility->icon;
                                } elseif (Str::startsWith($facility->icon, 'assets/')) {
                                    $imgPath = asset($facility->icon);
                                } else {
                                    $imgPath = asset('storage/' . $facility->icon);
                                }
                            @endphp
                            <div
                                class="join-network-card flex flex-col p-4 rounded-3xl transition-all duration-200 group  shadow-sm gap-[15px] h-fit bg-white hover:bg-mint-700">
                                <div>
                                    <div class="icon_labels group-hover:bg-mint-300">
                                        <img src="{{ $imgPath }}" alt=""
                                            class="transition-all duration-200 group-hover:brightness-0 group-hover:invert">
                                    </div>
                                </div>
                                <div class="space-y-[10px]">
                                    <p class="text-base font-semibold text-left text-neutral-900 group-hover:text-white">
                                        {!! $facility->title !!}</p>
                                    <p class="text-sm text-left text-neutral-500 group-hover:text-white">
                                        {{ $facility->description }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 gap-7 mt-5">
                            @php
                                if (!$facility->icon) {
                                    $imgPath = asset('assets/images/documents.png');
                                } elseif (Str::startsWith($facility->icon, ['http://', 'https://'])) {
                                    $imgPath = $facility->icon;
                                } elseif (Str::startsWith($facility->icon, 'assets/')) {
                                    $imgPath = asset($facility->icon);
                                } else {
                                    $imgPath = asset('storage/' . $facility->icon);
                                }
                            @endphp
                            <div
                                class="join-network-card flex flex-col p-4 rounded-3xl transition-all duration-200 group  shadow-sm gap-[15px] h-fit bg-white hover:bg-mint-700">
                                <div>
                                    <div class="icon_labels group-hover:bg-mint-300">
                                        <img src="{{ $imgPath }}"
                                            alt=""class="transition-all duration-200 group-hover:brightness-0 group-hover:invert">
                                    </div>
                                </div>
                                <div class="space-y-[10px]">
                                    <p class="text-base font-semibold text-left text-neutral-900 group-hover:text-white">
                                        {!! $facility->title ?? '' !!}</p>
                                    <p class="text-sm text-left text-neutral-500 group-hover:text-white">
                                        {{ $facility->description ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="grid lg:hidden grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-7">
                @foreach ($joinOurNetwork->facilities as $index => $facility)
                    @php
                        if (!$facility->icon) {
                            $imgPath = asset('assets/images/documents.png');
                        } elseif (Str::startsWith($facility->icon, ['http://', 'https://'])) {
                            $imgPath = $facility->icon;
                        } elseif (Str::startsWith($facility->icon, 'assets/')) {
                            $imgPath = asset($facility->icon);
                        } else {
                            $imgPath = asset('storage/' . $facility->icon);
                        }
                    @endphp
                    <div
                        class="join-network-card flex flex-col p-4 rounded-3xl transition-all duration-200 group  shadow-sm gap-[15px] h-fit bg-white hover:bg-mint-700">
                        <div>
                            <div class="icon_labels group-hover:bg-mint-300">
                                <img src="{{ $imgPath }}" alt=""
                                    class="transition-all duration-200 group-hover:brightness-0 group-hover:invert">
                            </div>
                        </div>
                        <div class="space-y-[10px]">
                            <p class="text-base font-semibold text-left text-neutral-900 group-hover:text-white">
                                {!! $facility->title ?? '' !!}</p>
                            <p class="text-sm text-left text-neutral-500 group-hover:text-white">
                                {{ $facility->description ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="section_container max-w-2lg mx-auto px-4 xl-1:px-0 relative z-10">
            @php
                $takeWith = $webSettings->firstWhere('key', 'take_with_you')->decoded_value ?? null;
            @endphp
            <header class="heading_section">
                <p>{!! $takeWith->title !!}</p>
                <p>{{ $takeWith->sub_title }}</p>
            </header>
            <div class="rounded-[44px] grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-12 overflow-hidden"
                style="background: linear-gradient(to right, #006CBA 0%, #006CBA 6.25%, #33d3a0 12.5%, #36d3a0 18.75%, #3bd49f 25%, #43d59e 31.25%, #4cd69c 37.5%, #58d79a 43.75%, #65d997 50%, #73dc93 56.25%, #82df8e 62.5%, #92e387 68.75%, #a2e77f 75%, #b3ec74 81.25%, #c5f265 87.5%, #d7f84f 93.75%, #e9ff26 100%);">
                <div class=" p-5 ">
                    <div class="h-full w-full p-3 md:p-6 rounded-3xl flex flex-col gap-6 justify-between bg-white">
                        <div class="flex justify-start items-center gap-2 md:gap-4">
                            @php
                                $iconPath = Str::startsWith($takeWith->take_info[0]->icon, 'assets/')
                                    ? asset($takeWith->take_info[0]->icon)
                                    : asset('storage/' . $takeWith->take_info[0]->icon);
                            @endphp
                            <div
                                class="w-[56px] h-[56px] flex justify-center items-center border border-gray-100 bg-mint-600 rounded-lg p-2">
                                <img class="object-contain w-full h-full" src="{{ $iconPath }}" alt="">
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-sm sm:text-lg font-semibold text-left text-neutral-900">
                                    {!! $takeWith->take_info[0]->title ?? '' !!}</p>
                                <p class="text-[10px] sm:text-sm text-left text-gray-500">
                                    {{ $takeWith->take_info[0]->sub_title ?? '' }}</p>
                            </div>
                        </div>

                        <div class="flex-1 pb-6 space-y-4 border-t-0 border-r-0 border-b border-l-0 border-gray-100">
                            @foreach ($takeWith->infos as $info)
                                @php
                                    $infoIconPath = Str::startsWith($info->icon, 'assets/')
                                        ? asset($info->icon)
                                        : asset('storage/' . $info->icon);
                                @endphp
                                <div class="flex justify-start items-center gap-[10px]">
                                    <div class="bg-mint-50 h-10 w-10 rounded-full flex justify-center items-center">
                                        <img src="{{ $infoIconPath }}" alt="">
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm sm:text-[15px] font-medium text-left text-neutral-600">
                                            {{ $info->title ?? '' }}</p>
                                        <p class="text-[10px] sm:text-sm text-left text-neutral-400">
                                            {{ $info->sub_title ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-start items-center gap-2 sm:gap-[30px]">
                            <div class="space-y-4">
                                <p class="text-base font-semibold text-left text-neutral-900">{!! $takeWith->title !!}</p>
                                <div class="flex justify-start items-center gap-[10px]">
                                    <div class="flex justify-start items-center ms-2">
                                        @foreach ($takeWith->image_group as $img)
                                            @php
                                                $imgPath = Str::startsWith($img->img, 'assets/')
                                                    ? asset($img->img)
                                                    : asset('storage/' . $img->img);
                                            @endphp
                                            <img src="{{ $imgPath }}" alt=""
                                                class="w-9 h-9 overflow-hidden rounded-[25px] -ml-2">
                                        @endforeach
                                    </div>
                                    <div class="space-y-1">
                                        <div
                                            class="flex flex-col justify-center items-center flex-grow-0 flex-shrink-0 w-[122px] gap-[5px]">
                                            @php
                                                if ($ratings->sum('rating') == 0) {
                                                    $avgRating = 0;
                                                    $fullStars = 0;
                                                    $halfStar = 0;
                                                } else {
                                                    $avgRating =
                                                        round(($ratings->sum('rating') / $ratings->count()) * 2) / 2;
                                                    $fullStars = floor($avgRating);
                                                    $halfStar = $avgRating - $fullStars == 0.5;
                                                    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                                }

                                            @endphp
                                            <div class="flex justify-start items-center w-fit h-4 relative gap-0.5">
                                                @for ($i = 0; $i < $fullStars; $i++)
                                                    <img src="../assets/icons/star.svg" alt="Full Star">
                                                @endfor
                                                @if ($halfStar)
                                                    <img src="../assets/icons/star-half.svg" alt="Half Star">
                                                @endif
                                            </div>
                                            <div
                                                class="flex justify-start items-center self-stretch flex-grow-0 flex-shrink-0 gap-1.5">
                                                <div
                                                    class="flex justify-start items-center flex-grow-0 flex-shrink-0 relative gap-[3px]">
                                                    <p
                                                        class="flex-grow-0 flex-shrink-0 text-sm font-medium text-left text-gray-800">
                                                        {{ $avgRating }}</p>
                                                    <p class="flex-grow-0 flex-shrink-0 text-sm text-left text-gray-500">
                                                        ({{ $ratings->count() }}+ reviews)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @foreach ($takeWith->button_group as $button)
                                <div class="bg-mint-600 p-1.5 rounded-lg space-y-0.5 qr-code">
                                    <div class="h-auto w-full bg-white ">
                                        {{ QrCode::size(120)->generate($button->link ?? '') }}</div>
                                    <p class="text-xs text-sm text-white text-center">{{ $button->name ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-[10px]">
                            @foreach ($takeWith->button_group as $button)
                                <a href="{{ $button->link }}"
                                    class="bg-gradient-to-bl from-mint-600 via-mint-600 to-mint-200 hover:from-mint-700 hover:via-mint-500 hover:to-mint-300 transition-all duration-150  flex justify-center items-center h-10 sm:h-12 w-full rounded-xl px-2 gap-1.5">
                                    <img src="./assets/icons/download-icon-white.svg" alt=""
                                        class="h-3 w-3 sm:h-4 sm:w-4">
                                    <p class="text-xs sm:text-sm font-semibold text-center text-white">
                                        {{ $button->name ?? '' }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @php
                    $sidePath = Str::startsWith($takeWith->right_side_image, 'assets/')
                        ? asset($takeWith->right_side_image)
                        : asset('storage/' . $takeWith->right_side_image);
                @endphp
                <div class="flex items-end justify-center">
                    <img src="{{ $sidePath }}" alt="" class="bg-contain ">
                </div>
            </div>
        </section>
    </section>
@endsection
@push('web-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;


                    if (!window.location.search.includes('lat')) {
                        window.location.href = `/?lat=${lat}&lng=${lng}`;
                    }
                });
            }
        });
    </script>
@endpush
