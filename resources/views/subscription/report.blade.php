@extends('layouts.app')
@section('title', __('subscription_reports'))

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card shadow border-0 rounded-12">
                    <div class="card-header d-flex justify-content-between align-items-center py-2">
                        <h2 class="card-title m-0">{{ __('Subscription Reports') }}</h2>
                    </div>
                    <div class="card-body">
                        @php
                            $role = auth()->user()->getRoleNames()[0] ?? 'Admin';
                        @endphp
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead class="table-bg-color">
                                    <tr>
                                        <th class="not-exported">{{ __('sl') }}</th>
                                        @if ($role != 'store')
                                            <th>{{ __('shop name') }}</th>
                                        @endif
                                        <th>{{ __('subscription title') }}</th>
                                        <th>{{ __('payment gateway') }}</th>
                                        <th>{{ __('payment status') }}</th>
                                        <th>{{ __('expired at') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($shopSubscriptions as $shopSubscription)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            @if ($role != 'store')
                                                <td>{{ $shopSubscription->store->name }}</td>
                                            @endif
                                            <td>{{ $shopSubscription->subscription->title }}</td>
                                            <td>{{ $shopSubscription->payment_gateway }}</td>
                                            <td>{{ $shopSubscription->payment_status }}</td>
                                            <td>{{ $shopSubscription->expired_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
