@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-9 m-auto">
                <div class="card mb-2">
                    <div class="card-header py-2 d-flex align-items-center justify-content-between">
                        <h2 class="card-title m-0">{{ __('Edit') . ' ' . __('Shop') }}</h2>
                        <a href="{{ route('shop.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('shop.update', $store->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <div class="section">
                                <h2 class="title">{{ __('Shop_Owner') }}</h2>
                                <div class="row px-2">
                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('First_Name') }}<span
                                                class="text-danger">*</span></label>
                                        <x-input type="text" name="first_name" :value="$user->first_name" />
                                    </div>

                                    <input type="hidden" name="userId" value="{{ $user->id }}">

                                    <div class="col-lg-6">
                                        <label fclass="mb-1">L{{ __('Last_Name') }}</label>
                                        <x-input type="text" name="last_name" :value="$user->last_name" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Email') }}<span class="text-danger">*</span></label>
                                        <x-input type="email" name="email" :value="$user->email" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Gender') }}</label>
                                        <x-select name="gender">
                                            @foreach (config('enums.ganders') as $gender)
                                                <option value="{{ $gender }}"
                                                    {{ $gender == $user->gender ? 'selected' : '' }}>
                                                    {{ $gender }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label class="mb-1">{{ __('Phone_number') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" onkeypress="onlyNumber(event)" name="mobile"
                                            class="form-control" value="{{ $user->mobile }}">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Date Of Birth') }}<span
                                                class="text-danger">*</span></label>
                                        <x-input type="date" name="date_of_birth" :value="$user->date_of_birth" />
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
                                        <x-input type="text" name="name" :value="$store->name" />
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Agreement_commission') }}(%)</label>
                                        <x-input type="number" name="commission" placeholder="Agreement commission"
                                            :value="$store->commission" />
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Commission Due Limit') }}</label>
                                        <x-input type="number" name="commission_due_limit"
                                            placeholder="Commission Due Limit" :value="$store->commission_due_limit" />
                                    </div>
                                    {{-- <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Commission Wallet') }}</label>
                                        <x-input type="number" name="commission_wallet" placeholder="Commission Due Limit" :value="$store->commission_wallet" />
                                    </div> --}}

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
                                        <x-textarea name="description" :value="$store->description"></x-textarea>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('New Password') }}</label>
                                        <div class="relative toggle__password">
                                            <x-input type="password" id="password" name="password" value=""
                                                placeholder="Enter new password" class="form-control pe-5" />
                                            <span class="absolute" style="cursor:pointer;"
                                                onclick="togglePassword('password','togglePasswordIcon')">
                                                <i class="far fa-eye-slash" id="togglePasswordIcon"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Confirm Password') }}</label>
                                        <div class="position-relative toggle__password">
                                            <x-input type="password" id="password_confirmation" name="password_confirmation"
                                                placeholder="Confirm new password" class="form-control pe-5" />
                                            <span class="position-absolute top-50 end-0 translate-middle-y pe-3"
                                                style="cursor:pointer;"
                                                onclick="togglePassword('password_confirmation','toggleConfirmIcon')">
                                                <i class="far fa-eye-slash" id="toggleConfirmIcon"></i>
                                            </span>
                                        </div>
                                    </div>


                                    <div class="col-lg-6">
                                        <label class="mb-1">{{ __('Shop Signature') }}</label>
                                        <x-input-file name="shop_signature" />

                                        @if ($store->shop_signature_path)
                                            <div class="mt-2">
                                                <img src="{{ $store->shop_signature_path }}" width="120"
                                                    class="rounded border">
                                            </div>
                                        @endif
                                    </div>


                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-3">
                                <button type="submit" class="btn btn-primary rounded-0 px-4">
                                    {{ __('Save_And_Update') }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
<style>
    .position-relative .pe-5 {
        padding-right: 2.5rem !important;
        /* Ensure input text doesn't overlap icon */
    }

    .position-relative span {
        cursor: pointer;
        color: #888;
    }

    .position-relative span:hover {
        color: #555;
    }

    .toggle__password {
        position: relative;
    }

    .toggle__password span {
        position: absolute;
        right: 16px;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }
</style>

@push('scripts')
    <script>
        function onlyNumber(evt) {
            var chars = String.fromCharCode(evt.which);
            if (!(/[0-9]/.test(chars))) {
                evt.preventDefault();
            }
        }

         function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>

@endpush
