@extends('website.layout.app')
@section('content')
    <section class="max-w-2lg mx-auto pt-[60px] pb-[80px] px-4 xl:px-0 space-y-10">

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
            <div class=" col-span-12 lg:col-span-8">

                <div class="p-6 rounded-3xl flex flex-col justify-start w-full bg-white">
                    <p class="text-base font-semibold text-left text-gray-700">
                        Favorite Stores
                    </p>

                    <div>
                        @foreach ($favouriteStores as $store)
                            <!-- card 1 -->
                            <div class="py-[15px] flex justify-between items-center gap-4 border-b border-neutral-200">
                                <div class="icon_labels">
                                    <img src="../assets/icons/heart-green-solid.svg" alt="">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-left text-neutral-700">
                                        {{ $store->name }}
                                    </p>
                                    <div class="flex justify-start my-1 gap-2">
                                        <p class="text-sm text-left text-neutral-500">{{ count($store->orders) }} Orders
                                        </p>
                                        <p class="text-sm text-left text-neutral-500 flex justify-start items-center gap-1">
                                            <img src="../assets/icons/star-gold.svg" alt="" class="inline">
                                            {{ round($store->ratings->avg('rating') * 2) / 2 }}
                                            rating
                                        </p>
                                    </div>

                                    <a href="{{ route('store-services', $store->slug) }}" type="submit"
                                        class="cursor-pointer block md:hidden w-full h-9 relative rounded-xl text-xs font-medium text-center text-neutral-500 border-[1.5px] border-neutral-200  px-4 py-2 capitalize transition-all duration-150 hover:bg-mint-600 hover:text-white">book
                                        Again</a>
                                </div>
                                <a href="{{ route('store-services', $store->slug) }}" type="submit"
                                    class="cursor-pointer hidden md:block w-40 h-9 relative rounded-xl text-xs font-medium text-center text-neutral-500 border-[1.5px] border-neutral-200  px-4 py-2 capitalize transition-all duration-150 hover:bg-mint-600 hover:text-white">book
                                    Again</a>
                            </div>
                        @endforeach



                    </div>
                </div>

            </div>

        </div>
    </section>
@endsection
@push('web-scripts')
@endpush
