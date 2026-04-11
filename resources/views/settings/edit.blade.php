@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="col-sm-6 p-md-0  mt-2 mt-sm-0 d-flex">
            <a href="{{ url()->previous() }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i>
                {{ __('Back') }}
            </a>
        </div>


        <div class="row">
            @if ($slug == 'contact-us')
                <div class="col-xl-12 col-xxl-12 col-lg-12 mt-4">
                    <div class="card shadow border-0 rounded-12">
                        <div class="card-header bg-primary py-3">
                            <h3 class="card-title m-0 text-white">{{ __('Edit') }} {{ __($setting->title) }}</h3>
                        </div>
                        <div class="card-body">
                            <x-form route="setting.update" updateId="{{ $setting->id }}" type="Save And Update"
                                method="true">
                                <x-input name='title' type="text" placeholder="Title" value="{{ $setting->title }}" />


                                @php
                                    $contentData = json_decode($setting->content, true);

                                    $phones = $contentData['phone_no'] ?? [''];
                                    $emails = $contentData['email'] ?? [''];
                                    $businesses = $contentData['business'] ?? [''];
                                    $officeAddress = $contentData['office_address'] ?? '';
                                @endphp

                                <div class="row">
                                    <!-- Phone Numbers -->
                                    <div class="col-3">
                                        <label for="phone_no">Phone Number</label>
                                        <div class="row align-items-center" id="phone-wrapper">
                                            @foreach ($phones as $phone)
                                                <div class="col-12 mb-2 phone-item">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="phone_no[]" required
                                                            value="{{ $phone }}" placeholder="Enter phone number">
                                                        @if ($loop->index > 0)
                                                            <button type="button"
                                                                class="btn btn-danger remove-phone">✕</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm" id="add-phone">Add
                                            more</button>
                                    </div>

                                    <!-- Emails -->
                                    <div class="col-3">
                                        <label for="email_no">Email</label>
                                        <div class="row align-items-center" id="email-wrapper">
                                            @foreach ($emails as $email)
                                                <div class="col-12 mb-2 email-item">
                                                    <div class="input-group">
                                                        <input type="email" class="form-control" name="email[]" required
                                                            value="{{ $email }}" placeholder="Enter email address">
                                                        @if ($loop->index > 0)
                                                            <button type="button"
                                                                class="btn btn-danger remove-email">✕</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm" id="add-email">Add
                                            more</button>
                                    </div>

                                    <!-- Business Hours -->
                                    <div class="col-3">
                                        <label for="business_hours">Business Hours</label>
                                        <div class="row align-items-center" id="business-wrapper">
                                            @foreach ($businesses as $business)
                                                <div class="col-12 mb-2 business-item">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="business[]" required
                                                            value="{{ $business }}" placeholder="Enter business hours">
                                                        @if ($loop->index > 0)
                                                            <button type="button"
                                                                class="btn btn-danger remove-business">✕</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm" id="add-business">Add
                                            more</button>
                                    </div>

                                    <!-- Office Address -->
                                    <div class="col-3">
                                        <label for="office-address">Office Address</label>
                                        <div class="row align-items-center">
                                            <div class="col-12 mb-2">
                                                <input type="text" class="form-control" required name="office_address"
                                                    value="{{ $officeAddress }}" placeholder="Enter office address">
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </x-form>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-xl-12 col-xxl-12 col-lg-12 mt-4">
                    <div class="card shadow border-0 rounded-12">
                        <div class="card-header bg-primary py-3">
                            <h3 class="card-title m-0 text-white">{{ __('Edit') }} {{ __($setting->title) }}</h3>
                        </div>
                        <div class="card-body">
                            <x-form route="setting.update" updateId="{{ $setting->id }}" type="Save And Update"
                                method="true">
                                <x-input name='title' type="text" placeholder="Title" value="{{ $setting->title }}" />

                                <textarea class="form-control" id="editor" name="content" placeholder="Content">{{ $setting->content }}</textarea>
                            </x-form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        $('#name').keyup(function() {
            $('#slug').val($(this).val().toLowerCase().split(',').join('').replace(/\s/g, "-"));
        });
    </script>

    <script>
        document.getElementById('add-phone').addEventListener('click', function() {
            const wrapper = document.getElementById('phone-wrapper');

            const div = document.createElement('div');
            div.classList.add('col-12', 'mb-2', 'phone-item');

            div.innerHTML = `
                <div class="input-group">
                    <input type="text" class="form-control" name="phone_no[]" required placeholder="Enter another phone number">
                    <button type="button" class="btn btn-danger remove-phone" style="height:39px">✕</button>
                </div>
            `;

            wrapper.appendChild(div);
        });

        // Remove phone field
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-phone')) {
                e.target.closest('.phone-item').remove();
            }
        });


        //email
        document.getElementById('add-email').addEventListener('click', function() {
            const wrapper = document.getElementById('email-wrapper');

            const div = document.createElement('div');
            div.classList.add('col-12', 'mb-2', 'email-item');

            div.innerHTML = `
                <div class="input-group">
                    <input type="email" class="form-control" name="email[]" required placeholder="Enter another email address">
                    <button type="button" class="btn btn-danger remove-email" style="height:39px">✕</button>
                </div>
            `;

            wrapper.appendChild(div);
        });

        // Remove email field
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-email')) {
                e.target.closest('.email-item').remove();
            }
        });


        //business
        document.getElementById('add-business').addEventListener('click', function() {
            const wrapper = document.getElementById('business-wrapper');

            const div = document.createElement('div');
            div.classList.add('col-12', 'mb-2', 'business-item');

            div.innerHTML = `
                <div class="input-group">
                    <input type="text" class="form-control" name="business[]" required placeholder="Enter another business hour">
                    <button type="button" class="btn btn-danger remove-business" style="height:39px">✕</button>
                </div>
            `;

            wrapper.appendChild(div);
        });

        // Remove business field
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-business')) {
                e.target.closest('.business-item').remove();
            }
        });
    </script>
@endpush
