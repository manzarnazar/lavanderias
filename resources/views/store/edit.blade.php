@extends('layouts.app')

@section('content')
    <div class="mt-3 container-fluid">
        <div class="row">
            <div class="col-md-8 col-lg-6 mt-0 mt-md-4 m-auto">
                <form action="{{ route('store.update', $store->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="card">
                        <div class="card-header bg-primary py-2">
                            <h3 class="text-white m-0">{{ __('Edit_Shop_Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>{{ __('Shop_name') }}<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" value="{{ $store->name }}"
                                        required />
                                    @error('Shop_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label>{{ __('Delivery_charge') }}</label>
                                    <x-input type="number" name="delivery_charge" :value="$store->delivery_charge" />
                                </div>
                                <div class="col-md-6">
                                    <label>{{ __('Service Time') }}</label>
                                    <x-input type="number" name="service_time" :value="$store->service_time" placeholder="0 Hours"/>
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-0">{{ __('Minimum_order_amount') }}</label>
                                    <x-input type="number" name="min_order_amount" :value="$store->min_order_amount" />
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-0">{{ __('Logo') }}</label>
                                    <x-input-file name="logo" />
                                </div>

                                <div class="col-md-6">
                                    <label class="mb-0">{{ __('Banner') }}</label>
                                    <x-input-file name="banner" />
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-0">{{ __('Shop Signature') }}</label>
                                    <x-input-file name="shop_signature" />
                                    @if ($store->shop_signature_path)
                                        <div class="mt-2">
                                            <img src="{{ $store->shop_signature_path }}" alt="Shop Signature"
                                                width="120">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-0">{{ __('Prefix') }}</label>
                                    <x-input type="text" name="prefix" :value="$store->prifix" />
                                </div>
                                <div class="col-md-12">
                                    <label class="mb-0">{{ __('Description') }}</label>
                                    <x-textarea name="description" :value="$store->description" />
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between py-2">
                            <a href="{{ route('store.index') }}" class="btn btn-danger">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                            <button class="btn btn-primary" type="submit">{{ __('Save_And_Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
