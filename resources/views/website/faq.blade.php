@extends('website.layout.app')
@section('content')
    <div class="space-y-[60px]"> <!-- breadcrumb -->
        <section
            class="rs-breadcrumb-area bg-[#1A7058] h-[260px] w-full bg-[url('../assets/images/header/breadcrumb.png')] bg-cover bg-center flex flex-col items-center justify-center text-center">
            <div class="rs-breadcrumb-content">
                <h1
                    class="rs-breadcrumb-title mb-[5px] sm:mb-[10px] text-[26px] md:text-[30px]  md:text-4xl text-white font-semibold leading-[140%]">
                    Frequently Asked Questions
                </h1>
                <div class="rs-breadcrumb-top-content">
                    <a href="{{ route('home') }}" class="text-base md:text-lg text-white font-normal leading-[100%]">Home / </a>
                    <a href="#" class="text-base md:text-lg text-white font-normal leading-[100%]">FAQ</a>
                </div>
            </div>
        </section>

        <!-- tabs -->
        <section class="max-w-2lg mx-auto px-4 xl-1:px-0 flex justify-center items-center flex-wrap  gap-3">
            <a href="{{ route('faq', ['slug' => 'all']) }}">
                <button
                    class="{{ (request('slug') ?? 'all') === 'all' ? 'bg-gradient-to-bl from-mint-600 via-mint-600 to-mint-200 border-transparent' : 'bg-white border-gray-200' }}
                        transition-all duration-150 h-10 w-fit px-3 md:px-5 rounded-xl group
                        hover:bg-gradient-to-bl hover:from-mint-600 hover:via-mint-600 hover:to-mint-200
                        border-[1.5px]
                    ">
                    <p class="{{ (request('slug') ?? 'all') === 'all' ? 'text-white' : 'text-gray-500 group-hover:text-white' }} text-[10px] md:text-xs font-medium text-center transition-all duration-150">
                        All Questions
                    </p>
                </button>
            </a>

            @foreach ($faqData as $faq)
                <a href="{{ route('faq', ['slug' => $faq->slug]) }}">
                    <button
                        class="{{ request('slug') === $faq->slug ? 'bg-gradient-to-bl from-mint-600 via-mint-600 to-mint-200 border-transparent ' : 'bg-white border-gray-200' }} transition-all duration-150 h-10 w-fit px-3 md:px-5 rounded-xl group hover:bg-gradient-to-bl hover:from-mint-600 hover:via-mint-600 hover:to-mint-200 border-[1.5px] border-gray-200 hover:border-transparent">
                        <p
                            class="{{ request('slug') === $faq->slug ? 'text-white' : '' }} text-[10px] md:text-xs font-medium text-center transition-all duration-150 text-gray-500 group-hover:text-white">
                            {{ config('enums.faqs')[$faq->slug] ?? $faq->slug }}
                        </p>
                    </button>
                </a>
            @endforeach


        </section>

        <section class="max-w-2lg mx-auto px-4 xl-1:px-0">
            <!-- accordion section  -->
            <div class="w-full">
                <div id="accordion" data-accordion="single" class="space-y-4">


                    @foreach ($faqs as $faq)
                        @php
                            $content = json_decode($faq->content, true);
                            $faqList = $content['faqs'] ?? [];
                            $title = config('enums.faqs')[$faq->slug] ?? ucfirst($faq->slug);
                        @endphp


                        {{-- QUESTIONS --}}
                        @foreach ($faqList as $index => $item)
                            <section
                                class="shadow-lg shadow-mint-100 p-4 flex justify-start items-start gap-4 bg-white rounded-2xl mb-4">

                                <div
                                    class="bg-mint-50 rounded-full h-10 w-10 min-h-10 min-w-10 flex justify-center items-center">
                                    <img src="{{ asset('assets/icons/question.svg') }}" alt="" class="h-5 w-5">
                                </div>

                                <div class="w-full">
                                    <button type="button"
                                        class="w-full flex items-center justify-between gap-3 text-left acc-btn"
                                        aria-expanded="false"
                                        aria-controls="acc-panel-{{ $faq->slug }}-{{ $index }}"
                                        id="acc-trigger-{{ $faq->slug }}-{{ $index }}">
                                        <div class="flex-1 flex justify-between items-center mt-2">
                                            <p class="text-sm md:text-base font-semibold text-gray-700">
                                                {{ $item['ques'] }}
                                            </p>

                                            <img src="{{ asset('assets/icons/chevron-down.svg') }}"
                                                class="chevron transition-transform duration-300">
                                        </div>
                                    </button>

                                    <div id="acc-panel-{{ $faq->slug }}-{{ $index }}" role="region"
                                        aria-labelledby="acc-trigger-{{ $faq->slug }}-{{ $index }}"
                                        class="acc-panel grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out">
                                        <div class="overflow-hidden">
                                            <p class="pt-4 text-slate-700 text-sm sm:text-base">
                                                {{ $item['answer'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </section>
                        @endforeach
                    @endforeach


                </div>
            </div>
        </section>

        <!-- still have questions -->
        <section class="max-w-2lg mx-auto px-4 xl-1:px-0 py-[30px] md:py-[60px] relative bg-cover bg-no-repeat min-h-52"
            style="background-image: url('../assets/images/stores-network/bg.png');">
            <div class="absolute top-0 right-0 w-full h-full z-0 bg-[#32d3a0]/5"></div>
            <div class="relative z-10 p-4 flex justify-center items-center flex-col gap-[30px]">
                <div class="space-y-[10px]">
                    <p class="text-2xl md:text-[32px] font-semibold text-center text-gray-900">
                        Still have questions?
                    </p>

                    <p class="text-sm md:text-lg text-center text-gray-700">
                        Can’t find the answer you’re looking for? Our customer support team is here to help.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-[15px]">
                    <a href="{{ route('contact') }}"
                        class="flex justify-center items-center w-full sm:w-fit h-14 overflow-hidden px-4 md:px-6 py-2 md:py-4 rounded-xl bg-mint-600">
                        <p class="text-sm md:text-base font-bold text-center text-white">
                            Contact Support
                        </p>
                    </a>
                    <a href="{{ route('home') }}"
                        class="flex justify-center items-center w-full sm:w-fit h-14 overflow-hidden px-4 md:px-6 py-2 md:py-4 rounded-xl border-[1.5px] border-mint-600 bg-white">
                        <p class="text-sm md:text-base font-bold text-center text-mint-600">
                            Back To Home
                        </p>
                    </a>
                </div>
            </div>
        </section>

    </div>
@endsection
@push('web-scripts')
    <script>
        document.querySelectorAll('.acc-btn').forEach(button => {
            button.addEventListener('click', () => {
                const expanded = button.getAttribute('aria-expanded') === 'true';
                const panel = document.getElementById(
                    button.getAttribute('aria-controls')
                );
                const chevron = button.querySelector('.chevron');

                // Toggle aria state
                button.setAttribute('aria-expanded', !expanded);

                // Toggle panel
                panel.classList.toggle('grid-rows-[1fr]');
                panel.classList.toggle('grid-rows-[0fr]');

                // Rotate chevron
                chevron.classList.toggle('rotate-180');
            });
        });
    </script>
@endpush
