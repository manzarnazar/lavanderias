@extends('website.layout.app')
@section('content')
    <section class="max-w-2lg mx-auto pt-[60px] pb-[80px] px-4 xl:px-0 space-y-5 md:space-y-10">

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
            <div class=" col-span-12 lg:col-span-8 flex flex-col gap-6">

                <form action="{{ route('update-user') }}" method="POST" class="rounded-3xl p-6 bg-white shadow-sm">
                    @csrf
                    <div class="flex justify-between items-center">
                        <h3 class="text-neutral-700 text-lg font-semibold"> Account Setting</h3>
                    </div>


                    <div class="space-y-4 mt-6 mb-[30px]">
                        <div>
                            <label for="name" class="text-sm md:text-base font-medium text-left text-neutral-700">Full
                                Name</label>
                            <input type="text" id="name" name="name" placeholder="John Doe"
                                value="{{ $customer->first_name }} {{ $customer->last_name }}"
                                class="w-full h-[40px] mt-2.5 border border-neutral-100 p-3 rounded-xl text-sm focus:outline-mint-100 ">
                        </div>
                        <div>
                            <label for="email" class="text-sm md:text-base font-medium text-left text-neutral-700">Email
                                Address</label>
                            <input type="text" id="email" name="email" placeholder="john.doe@email.com"
                                value="{{ $customer->email }}"
                                class="w-full h-[40px] mt-2.5 border border-neutral-100 p-3 rounded-xl text-sm focus:outline-mint-100 ">
                        </div>
                        <div>
                            <label for="phone" class="text-sm md:text-base font-medium text-left text-neutral-700">Phone
                                Number</label>
                            <input type="phone" id="mobile" name="mobile" placeholder="Your phone number"
                                value="{{ old('mobile', $customer->mobile) }}"
                                class="w-full h-[40px] mt-2.5 border border-neutral-100 p-3 rounded-xl text-sm focus:outline-mint-100">
                            @error('mobile')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>


                    <button type="submit"
                        class="flex justify-center items-center w-full h-12 overflow-hidden px-5 py-3.5 rounded-xl bg-mint-600">
                        <p class="text-sm font-semibold text-center text-white">
                            Save Changes
                        </p>
                    </button>
                </form>



                <!-- notification -->
                <div class="rounded-3xl p-6 bg-white shadow-sm">
                    <div class="flex justify-between items-center">
                        <h3 class="text-neutral-700 text-lg font-semibold">Notifications</h3>
                    </div>

                    <div class="mt-6">
                        <div class="flex justify-start items-center py-4 border-b border-neutral-200">
                            <label for="notification" class="flex-1">
                                <p class="text-sm font-semibold text-left text-neutral-700">Order Updates</p>
                                <p class="text-sm text-left text-neutral-500">Get notified about your order status
                                </p>
                            </label>
                            <label class="cursor-pointer flex items-center">
                                <input type="checkbox" name="order_update_notify" id="orderUpdateNotify" class="peer hidden"
                                    {{ $customer->order_update_notify ? 'checked' : '' }}>

                                <img src="../assets/icons/checked.svg" class="w-6 h-6 hidden peer-checked:block">

                                <img src="../assets/icons/unchecked-green.svg" class="w-6 h-6 block peer-checked:hidden">
                            </label>

                        </div>
                        <div class="flex justify-start items-center py-4">
                            <label for="promotion" class="flex-1">
                                <p class="text-sm font-semibold text-left text-neutral-700">Promotions</p>
                                <p class="text-sm text-left text-neutral-500">Receive special offers and discounts
                                </p>
                            </label>
                            <label for="promotion" class="cursor-pointer flex items-center">
                                <input type="checkbox" name="promotion_notify" id="promotion" class="peer hidden"
                                    {{ $customer->promotion_notify ? 'checked' : '' }}>
                                <img src="../assets/icons/unchecked-green.svg" alt=""
                                    class="w-6 h-6 peer-checked:hidden">
                                <img src="../assets/icons/checked.svg" alt=""
                                    class="w-6 h-6 hidden peer-checked:block">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('web-scripts')
    <script>
        document.getElementById('promotion').addEventListener('change', function() {
            const status = this.checked ? 1 : 0;

            fetch(`/promotion-notify/${status}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
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
                            title: 'Promotion status has been updated'
                        });
                    }
                });
        });

        document.getElementById('orderUpdateNotify').addEventListener('change', function() {
            const status = this.checked ? 1 : 0;

            fetch(`/order-update-notify/${status}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
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
                            title: 'Promotion status has been updated'
                        });
                    }
                });
        });
    </script>
@endpush
