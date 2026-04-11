@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4">
        <div class="row">
            <div class="col-xl-12 col-xxl-12 col-lg-12 m-auto">
                @if ($setting->slug == 'contact-us')
                    <div class="card shadow rounded-12 border-0">
                        <div class="card-header py-3 bg-primary">
                            <h3 class="card-title m-0 text-white">{{ __($setting->title) }}</h3>
                        </div>
                        @php
                            $contentData = json_decode($setting->content, true);
                        @endphp

                        <div class="card-body row">
                            <div class="col-3">
                                @if (!empty($contentData['phone_no']))
                                    <h5>Phone Numbers</h5>
                                    <ul class="list-group mb-3">
                                        @foreach ($contentData['phone_no'] as $phone)
                                            <li class="list-group-item">{{ $phone }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="col-3">
                                @if (!empty($contentData['email']))
                                    <h5>Email Addresses</h5>
                                    <ul class="list-group mb-3">
                                        @foreach ($contentData['email'] as $email)
                                            <li class="list-group-item">{{ $email }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="col-3">
                                @if (!empty($contentData['business']))
                                    <h5>Business Hours</h5>
                                    <ul class="list-group mb-3">
                                        @foreach ($contentData['business'] as $business)
                                            <li class="list-group-item">{{ $business }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="col-3">
                                @if (!empty($contentData['office_address']))
                                    <h5>Office Address</h5>
                                    <p class="border p-2">{{ $contentData['office_address'] }}</p>
                                @endif
                            </div>
                            <div class=" p-3 text-right">
                                <a href="{{ route('setting.edit', $setting->slug) }}" class="btn btn-primary">
                                    {{ __('Edit') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="card shadow rounded-12 border-0">
                            <div class="card-header py-3 bg-primary">
                                <h3 class="card-title m-0 text-white">{{ __($setting->title) }}</h3>
                            </div>
                            <div class="card-body">
                                {!! $setting->content !!}
                            </div>
                            <div class="card-footer py-3 text-right">
                                <a href="{{ route('setting.edit', $setting->slug) }}" class="btn btn-primary">
                                    {{ __('Edit') }}
                                </a>
                            </div>
                        </div>
                @endif
            </div>
        </div>
    </div>
@endsection
