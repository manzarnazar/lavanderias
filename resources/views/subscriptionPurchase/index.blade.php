@extends('layouts.app')

@section('content')
    <section>
        <style>
            .description {
                text-align: justify;
                font-size: 1.1rem;
                color: #3b3b3b
            }

            .price {
                font-weight: 700;
                font-size: 2.2rem;
                color: #39d8d8
            }

            .price span {
                font-size: 1.2rem;
                color: #525f7f
            }

            .title {
                font-size: 2rem;
                color: #39d8d8
            }
        </style>
        <div class="container-fluid mt-5">
            @php

                $role = auth()->user()->getRoleNames()[0] ?? 'Admin';
                $author = $role == 'store' ? 'Shop' : $role;

            @endphp
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h2>Subscription Purchase</h2>
                </div>

                <div class="card-body ">
                    <div class="row">

                        @foreach ($subscriptions as $subscription)
                            <div class="col-md-4 mb-3 ">
                                <div class="card text-center px-4 shadow">
                                    @if($author==='root')
                                    <div style="margin-left:390px">
                                        <a href="javascript:void(0)" class="dropdown-item w-25" data-toggle="modal"
                                            data-target="#editSubscriptionModal_{{ $subscription->id }}"><i
                                                class="fa fa-edit text-info"></i>
                                        </a>
                                    </div>
                                    @endif

                                    <div class="mt-4">

                                        <h1 class="title">{{ $subscription->title }}</h1>
                                    </div>
                                    <div>
                                        <h2 class="price mb-3">
                                            $ {{ $subscription->price }}<span> / {{ $subscription->type }}</span>
                                        </h2>
                                        <b class="offer">{{ __('you can create') }} {{ $subscription->shop_limit }}
                                            {{ __('branche and also create') }}
                                            {{ $subscription->product_limit }} {{ __('products for a branch') }}.</b>
                                        <p class="mt-3 description">{{ $subscription->description }}.</p>
                                    </div>

                                    <div class="mb-4 mt-3">
                                        <button type="button"
                                            data-action="{{ route('subscription.purchase.update', $subscription->id) }}"
                                            class="btn btn-primary w-100 subscribe-button">{{ __('subscribe now') }}</button>
                                    </div>

                                </div>
                            </div>

                            <div id="editSubscriptionModal_{{ $subscription->id }}" tabindex="-1" data-backdrop="static"
                                role="dialog" aria-labelledby="editSubscriptionModalLabel_{{ $subscription->id }}"
                                aria-hidden="true" class="modal fade text-left">
                                <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <form action="{{ route('subscription.update', $subscription->id) }}"
                                            method="POST">
                                            @method('put')
                                            @csrf

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="editSubscriptionModalLabel_{{ $subscription->id }}">
                                                    {{ __('Edit Subscription') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <!-- Modal Body -->
                                            <div class="modal-body">
                                                <div class="row">
                                                    <!-- Title -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="title">{{ __('Title') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="title" id="title"
                                                            class="form-control" value="{{ $subscription->title }}"
                                                            placeholder="{{ __('Enter your subscription title') }}"
                                                            required>
                                                    </div>

                                                    <!-- Price -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="price">{{ __('Price') }} <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" name="price" id="price"
                                                            class="form-control" value="{{ $subscription->price }}"
                                                            placeholder="{{ __('Enter your subscription price') }}"
                                                            required>
                                                    </div>

                                                    <!-- Recurring Type -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="type">{{ __('Type') }}</label>
                                                        <select name="type" id="type" class="form-control">
                                                            <option value="" disabled>
                                                                {{ __('Select an option') }}</option>
                                                            @foreach ($subscriptionTypes as $subscriptionType)
                                                                <option value="{{ $subscriptionType->value }}"
                                                                    {{ $subscription->type->value == $subscriptionType->value ? 'selected' : '' }}>
                                                                    {{ $subscriptionType->value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="status">{{ __('Status') }}</label>
                                                        <select name="status" id="status" class="form-control">
                                                            <option value="" disabled>
                                                                {{ __('Select an option') }}</option>
                                                            @foreach ($statuses as $status)
                                                                <option value="{{ $status->value }}"
                                                                    {{ $subscription->status->value == $status->value ? 'selected' : '' }}>
                                                                    {{ $status->value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Description -->
                                                    <div class="col-md-12 mb-3">
                                                        <label for="description">{{ __('Description') }}
                                                            <span class="text-danger">*</span></label>
                                                        <textarea name="description" id="description" class="form-control" rows="4"
                                                            placeholder="{{ __('Enter your subscription description') }}" required>{{ $subscription->description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">{{ __('Close') }}</button>
                                                <button type="submit"
                                                    class="btn btn-primary">{{ __('Update and Save') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $('.subscribe-button').on("click", function() {
            const action = $(this).attr('data-action');
            new swal({
                title: "Are you sure?",
                text: "To purchase this subscription",
                type: "warning",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#29aae1",
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.value) {
                    window.location.href = action;
                }
            });
        });
    </script>
@endpush
