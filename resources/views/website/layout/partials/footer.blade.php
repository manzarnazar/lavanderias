<!-- get started -->
<section class="bg-mint-600 bottom-0 ">

    @php
        $getStarted = $webSettings->firstWhere('key', 'get_started')->decoded_value ?? null;
    @endphp
    <div
        class="max-w-2lg mx-auto py-[60px] px-4 xl-1:px-0 flex flex-col lg:flex-row justify-center lg:justify-between items-center gap-6 lg:gap-0">
        <div class="flex-1">
            <p class="text-[32px] font-semibold text-center lg:text-left text-white ">
                {!! $getStarted->title ?? '' !!}
            </p>
            <p class="text-lg text-center lg:text-left text-neutral-50">
                {{ $getStarted->sub_title ?? '' }}
            </p>
        </div>
        <div class="flex flex-col md:flex-row justify-center items-center gap-4">
            <button class="btn_solid_white_lg getLocationBtn">
                <p>Book Now</p>

                <img src="../assets/icons/arrow-left-green.svg" alt="">
            </button>


            <a class="btn_outline_white_lg curson-pointer" href="{{ route('register-vendor') }}">
                <p>Become a Partner</p>
            </a>
        </div>
    </div>
</section><!-- footer section  -->
<footer class=" bg-no-repeat bg-cover" style="background-image: url('../assets/images/footer/footer-bg.png');">

    <!-- top footer  -->
    <div class="relative overflow-hidden">
        <div class="max-w-2lg mx-auto pt-[60px] relative z-10 px-4 xl-1:px-0">
            @php
                $footer = $webSettings->firstWhere('key', 'footer')->decoded_value ?? null;
                $footerLogoPath = Str::startsWith($footer->footer_logo, 'assets/')
                    ? asset($footer->footer_logo)
                    : asset('storage/' . $footer->footer_logo);
            @endphp

            <!-- links section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 lg:gap-[65px] relative z-10">
                <!-- logo section -->
                <div class="space-y-[30px]">
                    <img src="{{ $footerLogoPath }}" alt=""
                        class=" w-auto h-14 md:w-auto md:h-[51.58px] object-contain">
                    <p class="text-base text-left text-gray-300 font-normal ">
                        {!! $footer->footer_title !!}</p>
                </div>

                <div class="flex flex-col gap-6">
                    <p class="text-lg font-medium text-left text-neutral-50">Quick Links</p>
                    <a href="{{ route('home') }}" class="flex justify-start items-center gap-1">
                        <p class="text-base text-left text-neutral-400">Home</p>
                    </a>
                    <a href="{{ route('all-services') }}" class="flex justify-start items-center gap-1">
                        <p class="text-base text-left text-neutral-400">Our Services</p>
                    </a>
                    <a href="{{ route('home') }}#how-it-works" class="flex justify-start items-center gap-1">
                        <p class="text-base text-left text-neutral-400">How It Works</p>
                    </a>

                    <a href="{{ route('register-vendor') }}" class="flex justify-start items-center gap-1">
                        <p class="text-base text-left text-neutral-400">Partner Vendors</p>
                    </a>

                </div>


                <div class="flex flex-col gap-6">
                    <p class="text-lg font-medium text-left text-neutral-50">Support</p>
                    <a href="{{route('faq')}}" class="flex justify-start items-center gap-1">
                        <p class="text-base text-left text-neutral-400">FAQ</p>
                    </a>
                    <a href="{{route('terms.condition')}}" class="flex justify-start items-center gap-1">
                        <p class="text-base text-left text-neutral-400">Terms & Conditions</p>
                    </a>
                    <a href="{{route('privacy.policy')}}" class="flex justify-start items-center gap-1">
                        <p class="text-base text-left text-neutral-400">Privacy Policy</p>
                    </a>
                    <a href="{{ route('contact') }}" class="flex justify-start items-center gap-1">
                        <p class="text-base text-left text-neutral-400">Contact Us</p>
                    </a>
                </div>

                <div>
                    <div class="flex flex-col gap-6">
                        <p class="self-stretch justify-center text-white text-lg font-semibold  leading-relaxed">
                            Contact Us</p>
                        <div class="self-stretch inline-flex justify-start items-start gap-2 cursor-pointer">

                            <img src="../assets/icons/phone.svg" alt="" class="w-4 h-4 mt-1.5">
                            <p class="flex-1 text-base text-left text-neutral-300">
                                {{ $footer->contact_us->phone_number ?? '' }}</p>
                        </div>
                        <div class="self-stretch inline-flex justify-start items-start gap-2 cursor-pointer">

                            <img src="../assets/icons/map-pin.svg" alt="" class="w-4 h-4 mt-1.5">
                            <p class="flex-1 text-base text-left text-neutral-300">
                                {{ $footer->contact_us->address ?? '' }}</p>
                        </div>
                    </div>



                    <div class="mt-[30px] space-y-6">
                        <p class="text-base font-medium text-left text-gray-50">
                            Follow Us
                        </p>


                        <div class="flex justify-start items-center gap-[10px]">
                            @foreach ($footer->follow_us as $follow)
                                @php
                                    $iconPath = Str::startsWith($follow->icon, 'assets/')
                                        ? asset($follow->icon)
                                        : asset('storage/' . $follow->icon);
                                @endphp
                                <a href="{{ $follow->link }}"
                                    class="h-12 w-12 relative rounded-xl border border-white/[0.06] flex items-center justify-center transition-all duration-200 hover:bg-mint-600">
                                    <img src="{{$iconPath}}" alt="">
                                </a>
                            @endforeach


                        </div>
                    </div>
                </div>

            </div>


            <div
                class="flex flex-col sm:flex-row items-center justify-between py-6 border-t border-white/10 relative z-10">
                <p class="text-xs sm:text-sm text-white/40 font-normal">{{$footer->footer_left_side_text ?? ''}}
                </p>
                <p class="text-xs sm:text-sm text-white/40 font-normal">© {{ date('Y') }} {{$footer->footer_right_side_text ?? ''}}</p>
            </div>

            <!-- glows -->
            <div class="w-[400px] h-[400px] absolute -top-[70%] left-[10%] bg-mint-600 rounded-full blur-[150px]">
            </div>
            <div class="w-[400px] h-[400px] absolute -bottom-[50%] left-[30%] bg-mint-600 rounded-full blur-[150px]">
            </div>

        </div>

    </div>
</footer>
