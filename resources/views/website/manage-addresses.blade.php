@extends('website.layout.app')
@section('content')
    <!-- manage-address-modal -->
    <section id="role" class="modal_container">

        <!-- backdrop -->
        <div onclick="toggleModal('role')" class="modal_backdrop"></div>

        <!-- modal content -->
        <div class="modal_content">
            <form action="{{ route('delivery-address') }}" method="post" class="rs-manage-addresses-form">
                @csrf

                <div class="flex justify-between items-center mb-[30px]">
                    <h3 class="text-neutral-700 text-lg font-semibold">Add New Address</h3>
                    <button type="button" onclick="toggleModal('role')"
                        class="transition-transform duration-300 hover:rotate-90">
                        <img src="../assets/icons/close.svg" alt="">
                    </button>
                </div>
                <div class="mb-4">
                    <label class="text-neutral-700 text-base font-medium">Label<span style="color:red">*</span></label>
                    <input type="text" name="address_name" placeholder="Example : Home, Office ..."
                        class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                    @error('address_name')
                        <span class="text-danger" style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="text-neutral-700 text-base font-medium">Street Address</label>
                    <input type="text" name="road_no" placeholder="123 Lovely Road, Apt 6B"
                        class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]"value="{{ old('road_no') }}" />
                    @error('road_no')
                        <span class="text-danger" style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                <div class=" mb-4">
                    <label class="text-neutral-700 text-base font-medium">City<span style="color:red">*</span></label>
                    <input type="text" name="area" placeholder="New York" value="{{ old('area') }}"
                        class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                    @error('area')
                        <span class="text-danger" style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="text-neutral-700 text-base font-medium">ZIP Code</label>
                    <input type="text" name="post_code" placeholder="10003"value="{{ old('post_code') }}"
                        class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                    @error('post_code')
                        <span class="text-danger" style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-[30px]">
                    <label class="text-neutral-700 text-base font-medium">Phone Number</label>
                    <input type="text" name="phone_number" placeholder="+1 (555) 545-5421"
                        value="{{ old('phone_number') }}"
                        class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                    @error('phone_number')
                        <span class="text-danger" style="color:red">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit"
                    class="rs-add-new-address-btn text-sm bg-linear-to-r from-cyan-500 to-blue-500 text-white h-[48px] text-center leading-[48px] w-[100%] rounded-xl">
                    Save Address
                </button>
            </form>
        </div>
    </section>
    <section class="rs-manage-addresses-section pt-[60px] pb-[80px] px-4 xl:px-0">
        <div class="rs-manage-addresses-area max-w-2lg mx-auto">
            <div class="w-full">
                <a href="{{ route('my-dashboard') }}"
                    class="curson-pointer text-base font-semibold text-neutral-500 h-12 w-[230px] border border-neutral-200 rounded-xl flex items-center justify-center gap-2 mb-[10px]">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back To Dashboard
                </a>
                <p class="text-2xl md:text-4xl font-semibold text-left text-gray-700 mb-1">
                    Manage Addresses
                </p>
                <p class="text-base md:text-xl text-left text-gray-500 mb-10">
                    Add or edit delivery addresses for faster checkout
                </p>
            </div>
            <button onclick="toggleModal('role')"
                class="rs-add-new-address-btn text-sm bg-linear-to-r from-cyan-500 to-blue-500 text-white mb-[30px] h-[48px] text-center leading-[48px] w-[190px] sm:w-[290px] rounded-xl">
                <i class="fa-solid fa-plus mr-[8px]"></i>
                Add New Address
            </button>

            @foreach ($addresses as $address)
                <div
                    class="rs-manage-addresses-box bg-[white] rounded-xl p-4 md:p-[24px] mb-[24px] border border-transparent hover:border-[1.5px] hover:border-mint-600 transition-all duration-300">
                    <div class="rs-manage-addresses-content flex gap-[20px] border-b-[1.5px] border-neutral-200 mb-[20px]">
                        <div
                            class="rs-manage-addresses-icon flex-none w-[48px] h-[48px] sm:w-[56px] sm:h-[56px] flex bg-mint-50 rounded-lg">
                            <img src="../assets/icons/home-icon.svg" alt=""
                                class="m-auto w-[20px] h-[20px] sm:w-[24px] sm:h-[24px]">
                        </div>
                        <div class="rs-manage-addresses-info">
                            <div class="rs-manage-addresses-info-top flex text-center gap-[12px] mb-[12px]">
                                <a href="javascript:void(0)"
                                    class="text-neutral-900 text-base leading-[140%] font-medium">{{ $address->address_name }}</a>
                                @if ($address->is_default)
                                    <span
                                        class="text-mint-600 rounded-[25px] border-[1.5px] border-mint-200 flex gap-[4px] justify-center bg-mint-50 h-[24px] w-[78px] leading-[24px] text-xs font-normal">
                                        <img class="h-[10px] w-[10px] mt-auto mb-auto" src="../assets/icons/star.svg"
                                            alt="">
                                        Default
                                    </span>
                                @endif
                            </div>
                            <a href="javascript:void(0)"
                                class="leading-[140%] mb-[6px] text-[12px] sm:text-sm font-normal flex gap-[6px] text-neutral-700">
                                <img src="../assets/icons/location.svg" alt="">
                                {{ $address->road_no }},
                                {{ $address->area }}
                                @if ($address->post_code)
                                    - {{ $address->post_code }}
                                @endif
                            </a>


                            </a>
                            <a href="tel:+1(555)123-4567"
                                class="leading-[140%] text-sm mb-[24px] font-normal flex gap-[6px] text-neutral-500">
                                <img src="../assets/icons/call.svg" alt="" srcset="">
                                {{ $address->phone_number }}
                            </a>
                        </div>
                    </div>
                    <div class="rs-manage-addresses-box-bottom-area">
                        <div class="rs-manage-addresses-btn flex flex-wrap gap-[15px] md:flex-nowrap">
                            @if ($address->is_default == 0)
                                <a href="{{ route('default-address', $address->id) }}"
                                    class="cursor-pointer leading-[40px] text-xs font-medium flex gap-[5px] text-neutral-500 rounded-[12px] border-[1.50px] border-neutral-200 w-[30%] sm:w-[124px] h-[40px] justify-center border  hover:border-[1.5px] hover:border-mint-600 transition-all duration-300">
                                    Set as Default
                                </a>
                            @endif
                            <button onclick="toggleModalEdit_{{ $address->id }}()"
                                class="cursor-pointer leading-[40px] text-xs font-medium flex gap-[5px] text-neutral-500 rounded-[12px] border-[1.50px] border-neutral-200 w-[30%] sm:w-[88px] h-[40px] justify-center border hover:border-[1.5px] hover:border-mint-600 transition-all duration-300">
                                <img class="h-[12px] w-[12px] mt-auto mb-auto" src="../assets/icons/edit-btn.svg"
                                    alt="">
                                Edit
                            </button>


                            <section id="modalEdit_{{ $address->id }}" class="modal_container_edit">
                                <div onclick="toggleModalEdit_{{ $address->id }}()" class="modal_backdrop_edit"></div>

                                <div class="modal_content_edit">
                                    <form action="{{ route('delivery-address-update', $address->id) }}" method="post"
                                        class="rs-manage-addresses-form">
                                        @csrf

                                        <div class="flex justify-between items-center mb-[30px]">
                                            <h3 class="text-neutral-700 text-lg font-semibold">Edit Address</h3>
                                            <button type="button" onclick="toggleModalEdit_{{ $address->id }}()"
                                                class="transition-transform duration-300 hover:rotate-90">
                                                <img src="../assets/icons/close.svg" alt="">
                                            </button>
                                        </div>
                                        <div class="mb-4">
                                            <label class="text-neutral-700 text-base font-medium">Label<span
                                                    style="color:red">*</span></label>
                                            <input type="text" name="address_name"
                                                value="{{ $address->address_name }}"
                                                placeholder="Example : Home, Office ..."
                                                class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                                            @error('address_name')
                                                <span class="text-danger" style="color:red">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label class="text-neutral-700 text-base font-medium">Street Address</label>
                                            <input type="text" name="road_no" placeholder="123 Lovely Road, Apt 6B"
                                                value="{{ $address->road_no }}"
                                                class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                                            @error('road_no')
                                                <span class="text-danger" style="color:red">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class=" mb-4">
                                            <label class="text-neutral-700 text-base font-medium">City<span
                                                    style="color:red">*</span></label>
                                            <input type="text" name="area" placeholder="New York"
                                                value="{{ $address->area }}"
                                                class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                                            @error('area')
                                                <span class="text-danger" style="color:red">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label class="text-neutral-700 text-base font-medium">ZIP Code</label>
                                            <input type="text" name="post_code" placeholder="10003"
                                                value="{{ $address->post_code }}"
                                                class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                                            @error('post_code')
                                                <span class="text-danger" style="color:red">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-[30px]">
                                            <label class="text-neutral-700 text-base font-medium">Phone Number</label>
                                            <input type="text" name="phone_number" placeholder="+1 (555) 545-5421"
                                                value="{{ $address->phone_number }}"
                                                class="w-full h-[40px] mt-2.5 border border-gray-300 rounded-[12px] px-[16px] py-[10px]" />
                                            @error('phone_number')
                                                <span class="text-danger" style="color:red">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="submit"
                                            class="rs-add-new-address-btn text-sm bg-linear-to-r from-cyan-500 to-blue-500 text-white h-[48px] text-center leading-[48px] w-[100%] rounded-xl">
                                            Update Address
                                        </button>
                                    </form>
                                </div>
                                <script>
                                    function toggleModalEdit_{{ $address->id }}() {
                                        const modal = document.getElementById('modalEdit_{{ $address->id }}');
                                        modal.classList.toggle('active');
                                    }
                                </script>
                            </section>
                            @if ($address->is_default == 0)
                                <a href="{{ route('delete-address', $address->id) }}"
                                    class=" cursor-pointer leading-[40px] text-xs font-medium flex gap-[5px] text-danger-600 rounded-[12px] border-[1.50px] border-neutral-200 w-[30%] sm:w-[88px] h-[40px] justify-center border hover:border-[1.5px] hover:border-danger-600 transition-all duration-300">
                                    <img class="h-[12px] w-[12px] mt-auto mb-auto" src="../assets/icons/trash-red.svg"
                                        alt="">
                                    Delete
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
@push('web-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {


        @if ($errors->any() && old('role_id'))
            toggleModal('role');
        @endif


        @if ($errors->any() && old('address_id') && isset($addresses))
            @foreach ($addresses as $address)
                @if (old('address_id') == $address->id)
                    if (typeof toggleModalEdit_{{ $address->id }} === "function") {
                        toggleModalEdit_{{ $address->id }}();
                    }
                @endif
            @endforeach
        @endif

        @if ($errors->any() && !old('address_id'))
            toggleModal('role');
        @endif

    });
</script>
@endpush

