@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="row page-titles mx-0">
            <div class="col-12 mb-3 mb-lg-0 p-0">
                <a href="{{ route('product.index') }}" class="btn btn-danger">
                    <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-7 col-xxl-7 col-lg-7 m-auto">
                <div class="card shadow rounded-12 border-0">
                    <div class="card-header bg-primary py-3">
                        <h2 class="card-title m-0 text-white">{{ __('Add_New_Product') }}</h2>
                    </div>
                    <div class="card-body">
                        <x-form route="product.store" type="Submit">
                            <label class="mb-1">{{ __('Name') }}<span class="text-danger">*</span></label>
                            <x-input name="name" type="text" placeholder="Product name" value="{{ old('name') }}" />

                            <div class="mb-3">
                                <label class="mb-1">{{ __('Price') }}<span class="text-danger">*</span></label>
                                <input name="price" type="text" class="form-control"
                                    placeholder="Product price" value="{{ old('price') }}" onkeypress="onlyNumber(event)" />
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-1">{{ __('Discount_Price') }}</label>
                                <input name="discount_price" type='text' class="form-control"
                                    placeholder="Discount Price" value="{{ old('discount_price') }}" onkeypress="onlyNumber(event)" />
                                @error('discount_price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <label class="mb-1 mt-3">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control mb-3" placeholder="Product Description">{{ old('description') }}</textarea>

                            <input type="hidden" id="slug" name="slug" class="form-control input-default" value="{{ old('slug') }}">

                            <div class="mb-3">
                                <label class="mb-1">{{ __('Service') }}<span class="text-danger">*</span></label>
                                <x-select name="service_id">
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                </x-select>
                            </div>

                            <div class="mb-3">
                                <label class="mb-1">{{ __('Variant') }}<span class="text-danger">*</span></label>
                                <x-select name="variant_id">
                                    @if(old('variant_id'))
                                        <option value="{{ old('variant_id') }}" selected>{{ old('variant_name', 'Select Variant') }}</option>
                                    @endif
                                </x-select>
                            </div>

                            <label class="mb-1">{{ __('Thumbnail') }}<span class="text-danger">*</span></label>
                            <x-input-file name="image" type="file" />
                            

                            <div class="form-group mt-3">
                                <label for="active" class="mr-2">
                                    <input type="radio" id="active" name="is_active" value="1" {{ old('is_active', 1) == 1 ? 'checked' : '' }}>
                                    {{ __('Active') }}
                                </label>

                                <label for="inActive">
                                    <input type="radio" id="inActive" name="is_active" value="0" {{ old('is_active') == 0 ? 'checked' : '' }}>
                                    {{ __('Inactive') }}
                                </label>
                            </div>
                        </x-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function onlyNumber(evt) {
            var chars = String.fromCharCode(evt.which);
            if (!(/[0-9.]/.test(chars))) {
                evt.preventDefault();
            }
        };

        $('#name').keyup(function() {
            $('#slug').val($(this).val().toLowerCase().split(',').join('').replace(/\s/g, "-"));
        });

        $('select[name="service_id"]').on('change', function() {
            var serviceId = $(this).val();
            if (serviceId) {
                $.ajax({
                    url: `/services/${serviceId}/variants`,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="variant_id"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="variant_id"]').append('<option value="' + value.id +
                                '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('select[name="variant_id"]').empty();
            }
        });
    </script>
@endpush
