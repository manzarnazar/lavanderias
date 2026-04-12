@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-lg-9 m-auto">
                <div class="card shadow rounded-12 border-0">
                    <div class="card-header py-2 d-flex align-items-center justify-content-between">
                        <h2 class="card-title m-0">{{ __('Add_New_Shop') }}</h2>
                        <a href="{{ route('shop.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('shop.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="section">
                                <h2 class="title"> {{ __('Shop_Owner') }}</h2>
                                <div class="row px-2">
                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('First_Name') }}<span
                                                class="text-danger">*</span></label>
                                        <x-input type="text" name="first_name" placeholder="First Name" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label fclass="mb-1">{{ __('Last_Name') }}</label>
                                        <x-input type="text" name="last_name" placeholder="Last Name" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Email') }}<span class="text-danger">*</span></label>
                                        <x-input type="email" name="email" placeholder="Email Address" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Gender') }}</label>
                                        <x-select name="gender">
                                            @foreach (config('enums.ganders') as $gender)
                                                <option value="{{ $gender }}"
                                                    {{ $gender == old('gender') ? 'selected' : '' }}>
                                                    {{ $gender }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label class="mb-1">{{ __('Phone_number') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" onkeypress="onlyNumber(event)" value="{{ old('mobile') }}"
                                            name="mobile" class="form-control" placeholder="Phone number ...">
                                        @error('mobile')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Date_of_Birth') }}<span
                                                class="text-danger">*</span></label>
                                        <x-input type="date" name="date_of_birth" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Password') }}<span
                                                class="text-danger">*</span></label>
                                        <x-input type="text" name="password" placeholder="Password ..." />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Confirm_Password') }}<span
                                                class="text-danger">*</span></label>
                                        <x-input type="text" name="password_confirmation"
                                            placeholder="Confirm Password ..." />
                                    </div>
                                </div>
                            </div>

                            {{-- Shop Section --}}
                            <div class="section">
                                <h2 class="title">{{ __('Shop') }}</h2>
                                <div class="row px-2">

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Shop_name') }}<span
                                                class="text-danger">*</span></label>
                                        <x-input type="text" name="name" placeholder="Shop Name" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Agreement_commission') }}</label>
                                        <x-input type="number" name="commission" placeholder="Agreement commission" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Commission Due Limit') }}</label>
                                        <x-input type="number" name="commission_due_limit"
                                            placeholder="Commission Due Limit" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Logo') }}</label>
                                        <x-input-file name="logo" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Banner') }}</label>
                                        <x-input-file name="banner" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Description') }}<span
                                                class="text-danger">*</span></label>
                                        <x-textarea name="description" placehold="Description"></x-textarea>
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Shop Signature') }}</label>
                                        <x-input-file name="shop_signature" />
                                    </div>

                                </div>
                            </div>

                            <div class="section">
                                <h2 class="title">{{ __('Set_Location') }}</h2>
                                <p class="text-muted small px-2 mb-2">{{ __('Click the map to place the shop pin. Drag the marker to adjust.') }}</p>
                                <div class="row px-2">
                                    <div class="col-lg-4 mb-2">
                                        <label class="mb-1">{{ __('Latitude') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="latitude" id="shop_latitude"
                                            value="{{ old('latitude', '23.8103') }}" readonly />
                                        @error('latitude')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 mb-2">
                                        <label class="mb-1">{{ __('Longitude') }}<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="longitude" id="shop_longitude"
                                            value="{{ old('longitude', '90.4125') }}" readonly />
                                        @error('longitude')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 mb-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="shop_use_my_location">
                                            {{ __('Use my location') }}
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <div id="shopCreateMapCanvas" class="rounded border"
                                            style="width: 100%; height: 380px;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-3">
                                <button type="submit"
                                    class="btn btn-primary rounded-0 px-4">{{ __('Save_And_Update') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ mapApiKey() }}"></script>
    <script>
        function onlyNumber(evt) {
            var chars = String.fromCharCode(evt.which);
            if (!(/[0-9]/.test(chars))) {
                evt.preventDefault();
            }
        }

        (function() {
            var latInput = document.getElementById('shop_latitude');
            var lngInput = document.getElementById('shop_longitude');
            var lat = parseFloat(latInput.value) || 23.8103;
            var lng = parseFloat(lngInput.value) || 90.4125;
            var map, marker;

            function setPosition(newLat, newLng) {
                lat = newLat;
                lng = newLng;
                latInput.value = newLat.toFixed(7);
                lngInput.value = newLng.toFixed(7);
                if (marker) {
                    marker.setPosition({
                        lat: lat,
                        lng: lng
                    });
                }
                if (map) {
                    map.panTo({
                        lat: lat,
                        lng: lng
                    });
                }
            }

            function initShopCreateMap() {
                var center = {
                    lat: lat,
                    lng: lng
                };
                map = new google.maps.Map(document.getElementById('shopCreateMapCanvas'), {
                    zoom: 15,
                    center: center,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                marker = new google.maps.Marker({
                    position: center,
                    map: map,
                    draggable: true,
                    icon: "{{ asset('web/final-shop.png') }}"
                });
                marker.addListener('dragend', function(e) {
                    setPosition(e.latLng.lat(), e.latLng.lng());
                });
                map.addListener('click', function(e) {
                    setPosition(e.latLng.lat(), e.latLng.lng());
                });
            }

            google.maps.event.addDomListener(window, 'load', initShopCreateMap);

            document.getElementById('shop_use_my_location').addEventListener('click', function() {
                if (!navigator.geolocation) {
                    return;
                }
                navigator.geolocation.getCurrentPosition(function(pos) {
                    setPosition(pos.coords.latitude, pos.coords.longitude);
                });
            });
        })();
    </script>
@endpush
