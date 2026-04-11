@extends('website.layout.app')
@section('content')
    <!-- breadcrumb -->
    <section
        class="rs-breadcrumb-area bg-[#1A7058] h-[260px] w-full bg-[url('../assets/images/header/breadcrumb.png')] bg-cover bg-center flex flex-col items-center justify-center text-center">
        <div class="rs-breadcrumb-content">
            <h1
                class="rs-breadcrumb-title mb-[5px] sm:mb-[10px] text-[26px] md:text-[30px]  md:text-4xl text-white font-semibold leading-[140%]">
                Get In Touch
            </h1>
            <div class="rs-breadcrumb-top-content">
                <a href="{{ route('home') }}" class="text-base md:text-lg text-white font-normal leading-[100%]">Home / </a>
                <a href="#" class="text-base md:text-lg text-white font-normal leading-[100%]">Contact </a>

            </div>
        </div>
    </section>

    <!-- manage addresses area -->
    <section class="max-w-2lg mx-auto pt-[60px] pb-[80px] px-4 xl:px-0 grid grid-cols-12 gap-6">
        <div class="col-span-12 md:col-span-4 flex flex-col gap-4">
            @php
                $contentData = json_decode($setting->content, true);
            @endphp


            <!-- card 1 -->
            @if (!empty($contentData['phone_no']))
                <div class="flex flex-col p-4 rounded-3xl transition-all duration-200 shadow-sm gap-[15px] h-fit bg-white">
                    <div>
                        <div class="h-14 w-14 flex justify-center items-center bg-mint-50 rounded">
                            <img src="../assets/icons/phone.svg" alt="" class="transition-all duration-200 w-6 h-6">
                        </div>
                    </div>
                    <div class="flex flex-col justify-start items-start self-stretch relative gap-[5px]">
                        <p class="text-lg font-semibold text-left text-gray-700">
                            Phone
                        </p>
                        @foreach ($contentData['phone_no'] as $phone)
                            <p class="text-sm text-left text-gray-500">{{ $phone }}</p>
                        @endforeach

                        </p>
                    </div>
                </div>
            @endif


            <!-- card 2 -->
            @if (!empty($contentData['email']))
                <div class="flex flex-col p-4 rounded-3xl transition-all duration-200 shadow-sm gap-[15px] h-fit bg-white">
                    <div>
                        <div class="h-14 w-14 flex justify-center items-center bg-mint-50 rounded">
                            <img src="../assets/icons/envelop.svg" alt=""
                                class="transition-all duration-200 w-6 h-6">
                        </div>
                    </div>
                    <div class="flex flex-col justify-start items-start self-stretch relative gap-[5px]">
                        <p class="text-lg font-semibold text-left text-gray-700">
                            Email
                        </p>
                        @foreach ($contentData['email'] as $email)
                            <p class="text-sm text-left text-gray-500">{{ $email }}</p>
                        @endforeach
                    </div>
                </div>
            @endif


            <!-- card 3 -->
            @if (!empty($contentData['office_address']))
                <div class="flex flex-col p-4 rounded-3xl transition-all duration-200 shadow-sm gap-[15px] h-fit bg-white">
                    <div>
                        <div class="h-14 w-14 flex justify-center items-center bg-mint-50 rounded">
                            <img src="../assets/icons/map-pin.svg" alt=""
                                class="transition-all duration-200 w-6 h-6">
                        </div>
                    </div>
                    <div class="flex flex-col justify-start items-start self-stretch relative gap-[5px]">
                        <p class="text-lg font-semibold text-left text-gray-700">
                            Office
                        </p>
                        <p class="text-sm text-left text-gray-500">{{ $contentData['office_address'] }}
                        </p>
                    </div>
                </div>
            @endif

            <!-- card 4 -->
            @if (!empty($contentData['business']))
            <div class="flex flex-col p-4 rounded-3xl transition-all duration-200 shadow-sm gap-[15px] h-fit bg-white">
                <div>
                    <div class="h-14 w-14 flex justify-center items-center bg-mint-50 rounded">
                        <img src="../assets/icons/clock.svg" alt="" class="transition-all duration-200 w-6 h-6">
                    </div>
                </div>
                <div class="flex flex-col justify-start items-start self-stretch relative gap-[5px]">
                    <p class="text-lg font-semibold text-left text-gray-700">
                        Business Hours
                    </p>
                    @foreach ($contentData['business'] as $business)
                    <p class="text-sm text-left text-gray-500">{{$business}}
                    </p>
                    @endforeach
                </div>
            </div>
            @endif


        </div>
        <div class="col-span-12 md:col-span-8 space-y-6">
            <form action="{{ route('contact-us') }}" method="post" class="rounded-3xl p-3 sm:p-6 bg-white shadow-sm">
                @csrf
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-neutral-700 text-lg font-semibold">Send Us A Message</h3>
                </div>

                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label for="name" class="text-base font-medium text-left text-neutral-700 required">Your
                            Name</label>
                        <input type="text" id="name" name="name" placeholder="John Doe"
                            class="w-full h-[40px] mt-2.5 border border-neutral-100 p-3 rounded-xl text-sm focus:outline-mint-100 " />
                            @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                    </div>
                    <div>
                        <label for="email" class="text-base font-medium text-left text-neutral-700 required">Email
                            Address</label>
                        <input type="text" id="email" name="email" placeholder="john.doe@email.com"
                            class="w-full h-[40px] mt-2.5 border border-neutral-100 p-3 rounded-xl text-sm focus:outline-mint-100 " />
                            @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                    </div>
                    <div>
                        <label for="name" class="text-base font-medium text-left text-neutral-700 required">Your
                            Phone Number</label>
                        <input type="text" id="phone_number" name="phone_number" placeholder="John Doe"
                            class="w-full h-[40px] mt-2.5 border border-neutral-100 p-3 rounded-xl text-sm focus:outline-mint-100 " />
                            @error('phone_number')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <div>
                        <label for="subject"
                            class="text-base font-medium text-left text-neutral-700 required">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="How can we help you?"
                            class="w-full h-[40px] mt-2.5 border border-neutral-100 p-3 rounded-xl text-sm focus:outline-mint-100 " />
                            @error('subject')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                    </div>
                </div>


                <div class="mb-6">
                    <div>
                        <label for="message"
                            class="text-base font-medium text-left text-neutral-700 required">Message</label>
                        <textarea name="message" id="message" cols="30" rows="6" placeholder="How can we help you?"
                            class="w-full mt-2.5 border border-neutral-100 p-3 rounded-xl text-sm focus:outline-mint-100 " id=""></textarea>
                            @error('message')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                    </div>
                </div>


                <button type="submit"
                    class="flex justify-center items-center w-full max-w-[344px] h-12 overflow-hidden px-5 py-3.5 rounded-xl bg-mint-600">
                    <p class="text-sm font-semibold text-center text-white">
                        Send Message
                    </p>
                </button>
            </form>


            <div class="bg-mint-50 w-full h-fit overflow-hidden rounded-3xl p-5 md:p-10">
                <div class="space-y-[10px]">
                    <p class="text-lg md:text-2xl font-semibold text-left text-gray-700">
                        Looking for quick answers?
                    </p>

                    <p class="text-base md:text-lg text-left text-gray-500">
                        Checkout our FAQ page for answers to common questions.
                    </p>
                </div>
                <div class="mt-6">
                    <a class="btn_outline !bg-white cursor-pointer" href="{{ route('faq') }}">
                        <p>Visit FAQ</p>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
