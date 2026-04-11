@extends('layouts.app')

@section('content')
    <div class="container-fluid my-5">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h4 class="m-0">{{ __('Payment Gateways') }}</h4>

        </div>
        <div class="row">

            @php
                $storePaymentGateways = $storePaymentGateways->keyBy('payment_gateway_id');
            @endphp
            @foreach ($paymentGateways as $paymentGateway)
                @php

                    $storeGateway = $storePaymentGateways[$paymentGateway->id] ?? null;
                    $adminConfigs = json_decode($paymentGateway->config, true);
                    $storeConfigs = $storeGateway ? json_decode($storeGateway->config, true) : null;

                    $role = auth()->user()->getRoleNames()[0] ?? 'Admin';
                    $author = $role === 'store' ? 'Shop' : $role;

                @endphp


                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between gap-2 py-3">
                            <p class="paymentTitle m-0">
                                {{ strtoupper($paymentGateway->name) }}
                            </p>

                            <div class="mt-3">
                                <label class="switch">
                                    <input type="checkbox" class="statusSwitch"
                                        data-id="{{ $storePaymentGateways[$paymentGateway->id]->id ?? '' }}"
                                        {{ isset($storePaymentGateways[$paymentGateway->id]) && $storePaymentGateways[$paymentGateway->id]->is_active ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>



                        </div>

                        <div class="card-body">
                            <div class="py-2">

                                <img id="preview{{ $paymentGateway->name }}" class="paymentLogo w-50"
                                    src="{{ $paymentGateway->logo }}" alt="logo" loading="lazy">

                            </div>
                            <form action="{{ route('store-payment-gateway.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mt-3">

                                    <x-select1 name="mode" :title="'Mode'" :id="'mode'" :placeholder="'Select Mode'"
                                        :required="false">
                                        <option value="test" {{ $paymentGateway->mode == 'test' ? 'selected' : '' }}>
                                            Test
                                        </option>
                                        <option value="live" {{ $paymentGateway->mode == 'live' ? 'selected' : '' }}
                                            {{ app()->environment('local') ? 'disabled' : '' }}>
                                            Live
                                        </option>
                                    </x-select1>


                                </div>

                                @php
                                    // Find the matching store gateway for the current payment gateway
                                    $storeGateway = $storePaymentGateways->firstWhere(
                                        'payment_gateway_id',
                                        $paymentGateway->id,
                                    );
                                    $storeConfigs = $storeGateway ? json_decode($storeGateway->config, true) : null;
                                @endphp

                                @if ($storeConfigs && is_array($storeConfigs))
                                    @foreach ($storeConfigs as $key => $value)
                                        @php
                                            $label = ucwords(str_replace('_', ' ', $key));
                                        @endphp
                                        <div class="mt-3">
                                            <x-input1 :value="$value" name="config[{{ $key }}]" type="text"
                                                placeholder="{{ $label }}" title="{{ $label }}"
                                                :required="true" />
                                        </div>
                                    @endforeach
                                @else
                                    @foreach ($adminConfigs as $key => $value)
                                        @php
                                            $label = ucwords(str_replace('_', ' ', $key));
                                        @endphp
                                        <div class="mt-3">
                                            <x-input1 value="" name="config[{{ $key }}]" type="text"
                                                placeholder="{{ $label }}" title="{{ $label }}"
                                                :required="true" />
                                        </div>
                                    @endforeach
                                @endif

                                <input type="hidden" name="payment_gateway_id" id=""
                                    value="{{ $paymentGateway->id }}">
                                <div class="mt-3">

                                    <x-input1 name="title" type="text" title="Payment Gateway Title"
                                        value="{{ $paymentGateway->title }}" :required="true" />


                                </div>

                                <div class="mt-3 d-flex justify-content-end">
                                    <x-common-button name="save and update" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(".confirm").on("click", function(e) {
            e.preventDefault();
            const url = $(this).attr("href");
            Swal.fire({
                title: "Are you sure?",
                text: "You want to change status!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Change it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        function toggleStatus(checkbox, url) {
            const status = checkbox.checked ? 'on' : 'off';
            window.location.href = `${url}?status=${status}`;
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.statusSwitch').on('change', function() {
                const id = $(this).data('id');
                const status = $(this).is(':checked') ? 1 : 0;

                if (!id) {
                    alert('Store payment gateway ID not found!');
                    return;
                }

                $.ajax({
                    url: "{{ route('store-payment-gateway.toggleStatus') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        is_active: status
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Updated!', response.message, 'success');
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong!', 'error');
                    }
                });
            });
        });
    </script>
@endpush
