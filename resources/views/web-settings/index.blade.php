@extends('layouts.app')

@section('content')
    <style>
        .no-hover:hover {
            background-color: transparent !important;
        }

        .ck-blurred,
        .ck-focused {
            padding: 1.5rem !important
        }

        .ant-upload.ant-upload-select-picture-card {
            margin-right: 8px;
            margin-bottom: 8px;
            margin-top: 10px;
            text-align: center;
            vertical-align: top;
            border-radius: 2px;
            cursor: pointer;
            transition: border-color .3s;
            height: 100px
        }

        .ant-upload .file-input {
            display: none;
        }

        .template-div {
            height: 8rem;
            width: 100%;
        }

        .image_preview {
            object-fit: cover;
        }

        .div-position {
            position: relative;
        }

        .plus__icon {
            position: absolute;
            right: 6px;
            top: 6px;
        }
    </style>

    {{-- header section --}}
    <div class="mx-4 card mt-6 rounded-lg border-none">
        <div class="card-header py-2 bg-primary ">
            <h2 class="text-white"> {{ __('Header Section') }}</h2>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row card-body">
                <div class="col-12">
                    <form action="{{ route('web-settings.update', 'header') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @php
                            $header = $webSettings->firstWhere('key', 'header')->decoded_value ?? null;
                            $imageGroupCounter = 0;
                        @endphp

                        <div class="col-3 mb-4 ">
                            <label class="form-label">{{ __('Header Thumbnail') }}</label>
                            <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                <input id="image" name="header_img" type="file" style="display: none;"
                                    accept="image/*" x-ref="image"
                                    @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                <template x-if="imagePreview">
                                    <div
                                        class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                        <div class="w-100 h-100 div-position">
                                            <img :src="imagePreview" alt="{{ __('Header Preview') }}"
                                                class="image_preview rounded absolute inset-0 h-100 w-100" />
                                            <div class="plus__icon bg-white rounded">
                                                <img class="w-max h-max" src="{{ asset('assets/images/image-plus.svg') }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!imagePreview">
                                    <div
                                        class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                        @php
                                            $headerThumb = Str::startsWith($header->header_img, 'assets/')
                                                ? asset($header->header_img)
                                                : asset('storage/' . $header->header_img);
                                        @endphp

                                        <div class="w-100 h-100 div-position">
                                            <img src="{{ $headerThumb }}" alt="{{ __('Current Header') }}"
                                                class="image_preview absolute inset-0 rounded template-div" />
                                            <div class="plus__icon bg-white rounded">
                                                <img class="w-max h-max" src="{{ asset('assets/images/image-plus.svg') }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <label class="form-label">{{ __('Image Group') }}</label>
                            <div class="row" id="image-group-container">
                                @if (!empty($header->trusted_client_image_group))
                                    @foreach ($header->trusted_client_image_group as $index => $image)
                                        @php $imageGroupCounter = max($imageGroupCounter, $index); @endphp
                                        <div class="col-2 mb-3 image-item ">

                                            <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                                class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">


                                                <input type="file" style="display: none"
                                                    name="trusted_client_image_group[{{ $index }}][img]"
                                                    class="hidden" accept="image/*" x-ref="image"
                                                    @change="const file = $event.target.files[0]; if (file) imagePreview = URL.createObjectURL(file)" />

                                                <input type="hidden"
                                                    name="trusted_client_image_group[{{ $index }}][existing_img]"
                                                    value="{{ $image->img ?? '' }}">

                                                <template x-if="imagePreview">
                                                    <div
                                                        class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                                        <div class="w-100 h-100 div-position">
                                                            <img :src="imagePreview" alt="{{ __('Header Preview') }}"
                                                                class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                            <div class="plus__icon bg-white rounded">
                                                                <img class="w-max h-max"
                                                                    src="{{ asset('assets/images/image-plus.svg') }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template x-if="!imagePreview">
                                                    <div
                                                        class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                                        @php
                                                            $imgGroup = Str::startsWith($image->img, 'assets/')
                                                                ? asset($image->img)
                                                                : asset('storage/' . $image->img);
                                                        @endphp

                                                        <div class="w-100 h-100 div-position">
                                                            <img src="{{ $imgGroup }}" alt="{{ __('Current Header') }}"
                                                                class="image_preview absolute inset-0 rounded template-div" />
                                                            <div class="plus__icon bg-white rounded">
                                                                <img class="w-max h-max"
                                                                    src="{{ asset('assets/images/image-plus.svg') }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm remove-image mt-4 w-100">
                                                <i class="fa fa-trash"></i> {{ __('Remove') }}
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" style="width: 9rem" id="add-image-btn"
                                    class="btn btn-outline-info btn-sm mb-3">
                                    {{ __('Add Image') }}
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 p-4">
                                <label for="title" class="form-label">{{ __('Title') }}</label>
                                <textarea name="title" id="editor_title" class="form-control" rows="4">{!! $header->title ?? '' !!}</textarea>
                            </div>

                            <div class="col-6 p-4">
                                <label for="title" class="form-label">{{ __('Description') }}</label>
                                <x-textarea name="description" class="form-control" rows="3"
                                    placeholder="{{ __('Description') }}" :value="old('description', $header->description ?? '')"></x-textarea>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- premium service --}}
    <div class="mx-4 card mt-3 rounded-lg border-none">
        <div class="card-header py-2 bg-primary ">
            <h2 class="text-white"> {{ __('Premium Service Section') }}</h2>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row card-body">
                <div class="col-12">
                    <form action="{{ route('web-settings.update', 'premium_services') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $premiumServices =
                                $webSettings->firstWhere('key', 'premium_services')->decoded_value ?? null;
                        @endphp

                        <div class="row pb-4">
                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Title') }}</label>
                                <textarea name="title" id="editor_premium_title" class="form-control" rows="4">{!! $premiumServices->title ?? '' !!}</textarea>
                            </div>

                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Sub Title') }}</label>
                                <x-textarea name="sub_title" class="form-control" rows="3"
                                    placeholder="{{ __('Sub Title') }}" :value="old('sub_title', $premiumServices->sub_title ?? '')"></x-textarea>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- experience service section --}}
    <div class="mx-4 card mt-3 rounded-lg border-none">
        <div class="card-header py-2 bg-primary ">
            <h2 class="text-white"> {{ __('Experience Service Section') }}</h2>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row card-body">
                <div class="col-12">
                    <form action="{{ route('web-settings.update', 'experience_services') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $experienceServices =
                                $webSettings->firstWhere('key', 'experience_services')->decoded_value ?? null;
                        @endphp

                        <div class="row pb-4">
                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Title') }}</label>
                                <textarea name="title" id="editor_experience_title" class="form-control" rows="4">{!! $experienceServices->title ?? '' !!}</textarea>
                            </div>

                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Sub Title') }}</label>
                                <x-textarea name="sub_title" class="form-control" rows="3"
                                    placeholder="{{ __('Sub Title') }}" :value="old('sub_title', $experienceServices->sub_title ?? '')"></x-textarea>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    {{-- how it work section --}}
    <div class="mx-4 card mt-3 rounded-lg border-none">
        <div class="card-header py-2 bg-primary ">
            <h2 class="text-white"> {{ __('How It Work Section') }}</h2>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row card-body">
                <div class="col-12">
                    <form action="{{ route('web-settings.update', 'how_it_works') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $howItWork = $webSettings->firstWhere('key', 'how_it_works')->decoded_value ?? null;
                            $counter = 0;
                        @endphp
                        <div class="row">
                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Title') }}</label>
                                <textarea name="title" id="editor_how_it_work_title" class="form-control" rows="4">{!! $howItWork->title ?? '' !!}</textarea>
                            </div>

                            <div class="col-3 mb-4 ">
                                <label class="form-label">{{ __('Thumbnail') }}</label>
                                <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                    class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview mt-0">
                                    <input id="image" name="right_side_img" type="file" style="display: none;"
                                        accept="image/*" x-ref="image"
                                        @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                    <template x-if="imagePreview">
                                        <div
                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                            <div class="w-100 h-100 div-position">
                                                <img :src="imagePreview" alt="{{ __('Image Preview') }}"
                                                    class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max"
                                                        src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!imagePreview">
                                        <div
                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                            @php
                                                $imageThumb = Str::startsWith($howItWork->right_side_img, 'assets/')
                                                    ? asset($howItWork->right_side_img)
                                                    : asset('storage/' . $howItWork->right_side_img);
                                            @endphp

                                            <div class="w-100 h-100 div-position">
                                                <img src="{{ $imageThumb }}" alt="{{ __('Current Image') }}"
                                                    class="image_preview absolute inset-0 rounded template-div" />
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max"
                                                        src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">{{ __('Work Steps') }}</label>
                            <div class="row" id="work-card-container">
                                @if (!empty($howItWork->work_steps))
                                    @foreach ($howItWork->work_steps as $index => $step)
                                        @php $counter = max($counter, $index); @endphp

                                        <div class="col-3 mb-4 work-card-item">
                                            <div class="border rounded p-3">
                                                <label>{{ __('Number') }}</label>
                                                <input type="number" name="work_steps[{{ $counter }}][number]"
                                                    value="{{ $step->number }}" class="form-control mb-2" placeholder="Enter Number"/>

                                                <label>{{ __('Title') }}</label>
                                                <input type="text" name="work_steps[{{ $counter }}][title]"
                                                    value="{{ $step->title }}" class="form-control mb-2" placeholder="Enter Title"/>

                                                <label>{{ __('Subtitle') }}</label>
                                                <input type="text" name="work_steps[{{ $counter }}][sub_title]"
                                                    value="{{ $step->sub_title }}" class="form-control mb-2" placeholder="Enter Subtitle"/>



                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm w-100 remove-how-it-work-card mt-2">
                                                    {{ __('Remove') }}
                                                </button>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" style="width: 9rem" id="add-work-btn"
                                    class="btn btn-outline-info btn-sm mb-3">
                                    {{ __('Add work') }}
                                </button>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- build on trust --}}
    <div class="mx-4 card mt-3 rounded-lg border-none">
        <div class="card-header py-2 bg-primary ">
            <h2 class="text-white"> {{ __('Build on Trust Section') }}</h2>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row card-body">
                <div class="col-12">
                    <form action="{{ route('web-settings.update', 'build_on_trust') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $buildOnTrust = $webSettings->firstWhere('key', 'build_on_trust')->decoded_value ?? null;
                            $counter = 0;
                        @endphp
                        <div class="row">
                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Title') }}</label>
                                <textarea name="title" id="editor_build_on_trust_title" class="form-control" rows="4">{!! $buildOnTrust->title ?? '' !!}</textarea>
                            </div>

                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Sub Title') }}</label>
                                <x-textarea name="sub_title" class="form-control" rows="3"
                                    placeholder="{{ __('Sub Title') }}" :value="old('sub_title', $buildOnTrust->sub_title ?? '')"></x-textarea>
                            </div>

                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">{{ __('Sample') }}</label>
                            <div class="row" id="build-card-container">
                                @if (!empty($buildOnTrust->sample))
                                    @foreach ($buildOnTrust->sample as $index => $sample)
                                        @php $counter = max($counter, $index); @endphp

                                        <div class="col-3 mb-4 build-card-item">
                                            <div class="border rounded p-3">

                                                <label>{{ __('Title') }}</label>
                                                <input type="text" name="sample[{{ $counter }}][title]"
                                                    value="{{ $sample->title }}" class="form-control mb-2" placeholder="Enter Title"/>

                                                <label>{{ __('Description') }}</label>
                                                <x-textarea name="sample[{{ $counter }}][description]"
                                                    class="form-control" rows="3"
                                                    placeholder="{{ __('Description') }}" :value="old('description', $sample->description ?? '')"></x-textarea>


                                                <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                                    class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                                    <input id="image" name="sample[{{ $counter }}][icon]"
                                                        type="file" style="display: none;" accept="image/*"
                                                        x-ref="image"
                                                        @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                                    <template x-if="imagePreview">
                                                        <div
                                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                                            <div class="w-100 h-100 div-position">
                                                                <img :src="imagePreview"
                                                                    alt="{{ __('Header Preview') }}"
                                                                    class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                                <div class="plus__icon bg-white rounded">
                                                                    <img class="w-max h-max"
                                                                        src="{{ asset('assets/images/image-plus.svg') }}"
                                                                        alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template x-if="!imagePreview">
                                                        <div
                                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                                            @php
                                                                $sampleIcon = Str::startsWith($sample->icon, 'assets/')
                                                                    ? asset($sample->icon)
                                                                    : asset('storage/' . $sample->icon);
                                                            @endphp

                                                            <div class="w-100 h-100 div-position">
                                                                <img src="{{ $sampleIcon }}"
                                                                    alt="{{ __('Current Header') }}"
                                                                    class="image_preview absolute inset-0 rounded template-div" />
                                                                <div class="plus__icon bg-white rounded">
                                                                    <img class="w-max h-max"
                                                                        src="{{ asset('assets/images/image-plus.svg') }}"
                                                                        alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>

                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm w-100 remove-build-card mt-4">
                                                    {{ __('Remove') }}
                                                </button>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" style="width: 9rem" id="add-build-btn"
                                    class="btn btn-outline-info btn-sm mb-3">
                                    {{ __('Add sample') }}
                                </button>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- our promise  --}}
    <div class="mx-4 card mt-3 rounded-lg border-none">
        <div class="card-header py-2 bg-primary ">
            <h2 class="text-white"> {{ __('Our Promise Section') }}</h2>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row card-body">
                <div class="col-12">
                    <form action="{{ route('web-settings.update', 'our_promise') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $ourPromise = $webSettings->firstWhere('key', 'our_promise')->decoded_value ?? null;
                        @endphp
                        <div class="row">
                            <div class="col-3 mb-4">
                                <label class="form-label">{{ __('Background Image') }}</label>
                                <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                    class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                    <input id="image" name="background_image" type="file" style="display: none;"
                                        accept="image/*" x-ref="image"
                                        @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                    <template x-if="imagePreview">
                                        <div
                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                            <div class="w-100 h-100 div-position">
                                                <img :src="imagePreview" alt="{{ __('Image Preview') }}"
                                                    class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max"
                                                        src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!imagePreview">
                                        <div
                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                            @php
                                                $backgroundImage = Str::startsWith(
                                                    $ourPromise->background_image,
                                                    'assets/',
                                                )
                                                    ? asset($ourPromise->background_image)
                                                    : asset('storage/' . $ourPromise->background_image);
                                            @endphp

                                            <div class="w-100 h-100 div-position">
                                                <img src="{{ $backgroundImage }}" alt="{{ __('Current Header') }}"
                                                    class="image_preview absolute inset-0 rounded template-div" />
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max"
                                                        src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <div class="col-3 mb-4">
                                <label class="form-label">{{ __('Thumbnail') }}</label>
                                <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                    class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                    <input id="image" name="side_image" type="file" style="display: none;"
                                        accept="image/*" x-ref="image"
                                        @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                    <template x-if="imagePreview">
                                        <div
                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                            <div class="w-100 h-100 div-position">
                                                <img :src="imagePreview" alt="{{ __('Image Preview') }}"
                                                    class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max"
                                                        src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!imagePreview">
                                        <div
                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                            @php
                                                $sideImage = Str::startsWith($ourPromise->side_image, 'assets/')
                                                    ? asset($ourPromise->side_image)
                                                    : asset('storage/' . $ourPromise->side_image);
                                            @endphp

                                            <div class="w-100 h-100 div-position">
                                                <img src="{{ $sideImage }}" alt="{{ __('Current Header') }}"
                                                    class="image_preview absolute inset-0 rounded template-div" />
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max"
                                                        src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Title') }}</label>
                                <textarea name="title" id="editor_promise_title" class="form-control" rows="4">{!! $ourPromise->title ?? '' !!}</textarea>
                            </div>

                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Sub Title') }}</label>
                                <x-textarea name="sub_title" class="form-control" rows="3"
                                    placeholder="{{ __('Sub Title') }}" :value="old('sub_title', $ourPromise->sub_title ?? '')"></x-textarea>
                            </div>

                        </div>


                        <div class="mt-4">
                            <button class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- join our network --}}
    <div class="mx-4 card mt-3 rounded-lg border-none">
        <div class="card-header py-2 bg-primary ">
            <h2 class="text-white"> {{ __('Joun Our Network Section') }}</h2>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row card-body">
                <div class="col-12">
                    <form action="{{ route('web-settings.update', 'join_our_network') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $joinOurNet = $webSettings->firstWhere('key', 'join_our_network')->decoded_value ?? null;
                            $counterJoin = 0;
                        @endphp
                        <div class="row">
                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Title') }}</label>
                                <textarea name="title" id="editor_join_our_network_title" class="form-control" rows="4">{!! $joinOurNet->title ?? '' !!}</textarea>
                            </div>

                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Description') }}</label>
                                <x-textarea name="description" class="form-control" rows="3"
                                    placeholder="{{ __('Description') }}" :value="old('description', $joinOurNet->description ?? '')"></x-textarea>
                            </div>

                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">{{ __('Lists') }}</label>
                            <div class="row" id="facility-list-container">
                                @if (!empty($joinOurNet->lists))
                                    @foreach ($joinOurNet->lists as $index => $list)
                                        {{-- @php $counter = max($counter, $index); @endphp --}}

                                        <div class="col-6 mb-2 facility-list-item">
                                            <div class="input-group mt-2">
                                                <input type="text" name="lists[{{ $index }}][list]"
                                                    value="{{ $list->list }}" class="form-control" />

                                                <button type="button" style="height: 2.4rem"
                                                    class="btn btn-outline-danger btn-sm remove-list-button no-hover">
                                                    <i class="fa fa-trash" style="color:red; width:14px"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" style="width: 9rem" id="add-join-list-btn"
                                    class="btn btn-outline-info btn-sm mb-3">
                                    {{ __('Add list') }}
                                </button>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">{{ __('Facilities') }}</label>
                            <div class="row" id="facility-card-container">
                                @if (!empty($joinOurNet->facilities))
                                    @foreach ($joinOurNet->facilities as $index => $facility)
                                        @php $counterJoin = max($counterJoin, $index); @endphp

                                        <div class="col-3 mb-4 facility-card-item">
                                            <div class="border rounded p-3">

                                                <label>{{ __('Title') }}</label>
                                                <input type="text" name="facilities[{{ $counterJoin }}][title]"
                                                    value="{{ $facility->title }}" class="form-control mb-2" placeholder="Enter Title"/>

                                                <label>{{ __('Description') }}</label>
                                                <x-textarea name="facilities[{{ $counterJoin }}][description]"
                                                    class="form-control" rows="3"
                                                    placeholder="{{ __('Description') }}" :value="old('description', $facility->description ?? '')"></x-textarea>


                                                <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                                    class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                                    <input id="image" name="facilities[{{ $counterJoin }}][icon]"
                                                        type="file" style="display: none;" accept="image/*"
                                                        x-ref="image"
                                                        @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                                    <template x-if="imagePreview">
                                                        <div
                                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                                            <div class="w-100 h-100 div-position">
                                                                <img :src="imagePreview"
                                                                    alt="{{ __('Header Preview') }}"
                                                                    class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                                <div class="plus__icon bg-white rounded">
                                                                    <img class="w-max h-max"
                                                                        src="{{ asset('assets/images/image-plus.svg') }}"
                                                                        alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template x-if="!imagePreview">
                                                        <div
                                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                                            @php
                                                                $facilityIcon = Str::startsWith(
                                                                    $facility->icon,
                                                                    'assets/',
                                                                )
                                                                    ? asset($facility->icon)
                                                                    : asset('storage/' . $facility->icon);
                                                            @endphp

                                                            <div class="w-100 h-100 div-position">
                                                                <img src="{{ $facilityIcon }}"
                                                                    alt="{{ __('Current Header') }}"
                                                                    class="image_preview absolute inset-0 rounded template-div" />
                                                                <div class="plus__icon bg-white rounded">
                                                                    <img class="w-max h-max"
                                                                        src="{{ asset('assets/images/image-plus.svg') }}"
                                                                        alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>

                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm w-100 remove-facility-card mt-4">
                                                    {{ __('Remove') }}
                                                </button>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" style="width: 9rem" id="add-facility-btn"
                                    class="btn btn-outline-info btn-sm mb-3">
                                    {{ __('Add facility') }}
                                </button>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- take with you --}}
    <div class="mx-4 card mt-3 rounded-lg border-none">
        <div class="card-header py-2 bg-primary ">
            <h2 class="text-white"> {{ __('Take With Us Section') }}</h2>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row card-body">
                <div class="col-12">
                    <form action="{{ route('web-settings.update', 'take_with_you') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $takeWith = $webSettings->firstWhere('key', 'take_with_you')->decoded_value ?? null;
                            $imageCounter = 0;
                            $takeCounter = 0;
                            $buttonCounter = 0;
                        @endphp

                        <div class="col-3 ">
                            <label class="form-label">{{ __('Thumbnail') }}</label>
                            <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                <input id="image" name="right_side_image" type="file" style="display: none;"
                                    accept="image/*" x-ref="image"
                                    @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                <template x-if="imagePreview">
                                    <div
                                        class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                        <div class="w-100 h-100 div-position">
                                            <img :src="imagePreview" alt="{{ __('Header Preview') }}"
                                                class="image_preview rounded absolute inset-0 h-100 w-100" />
                                            <div class="plus__icon bg-white rounded">
                                                <img class="w-max h-max"
                                                    src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!imagePreview">
                                    <div
                                        class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                        @php
                                            $takeWithThumb = Str::startsWith($takeWith->right_side_image, 'assets/')
                                                ? asset($takeWith->right_side_image)
                                                : asset('storage/' . $takeWith->right_side_image);
                                        @endphp

                                        <div class="w-100 h-100 div-position">
                                            <img src="{{ $takeWithThumb }}" alt="{{ __('Current Header') }}"
                                                class="image_preview absolute inset-0 rounded template-div" />
                                            <div class="plus__icon bg-white rounded">
                                                <img class="w-max h-max"
                                                    src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="col-12 mt-5">
                            <label class="form-label">{{ __('Image Group') }}</label>
                            <div class="row" id="image-take-group-container">
                                @if (!empty($takeWith->image_group))
                                    @foreach ($takeWith->image_group as $index => $image)
                                        @php $imageCounter = max($imageCounter, $index); @endphp
                                        <div class="col-2 mb-3 take-image-item ">

                                            <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                                class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">

                                                <input type="file" style="display: none"
                                                    name="image_group[{{ $index }}][img]"
                                                    class="hidden" accept="image/*" x-ref="image"
                                                    @change="const file = $event.target.files[0]; if (file) imagePreview = URL.createObjectURL(file)" />

                                                <input type="hidden"
                                                    name="image_group[{{ $index }}][existing_img]"
                                                    value="{{ $image->img ?? '' }}">


                                                <template x-if="imagePreview">
                                                    <div
                                                        class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                                        <div class="w-100 h-100 div-position">
                                                            <img :src="imagePreview" alt="{{ __('Header Preview') }}"
                                                                class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                            <div class="plus__icon bg-white rounded">
                                                                <img class="w-max h-max"
                                                                    src="{{ asset('assets/images/image-plus.svg') }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                                <template x-if="!imagePreview">
                                                    <div
                                                        class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                                        @php
                                                            $imgGroup = Str::startsWith($image->img, 'assets/')
                                                                ? asset($image->img)
                                                                : asset('storage/' . $image->img);
                                                        @endphp

                                                        <div class="w-100 h-100 div-position">
                                                            <img src="{{ $imgGroup }}"
                                                                alt="{{ __('Current Header') }}"
                                                                class="image_preview absolute inset-0 rounded template-div" />
                                                            <div class="plus__icon bg-white rounded">
                                                                <img class="w-max h-max"
                                                                    src="{{ asset('assets/images/image-plus.svg') }}"
                                                                    alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <button type="button"
                                                class="btn btn-outline-danger btn-sm remove-take-group-image mt-4 w-100">
                                                <i class="fa fa-trash"></i> {{ __('Remove') }}
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" style="width: 9rem" id="add-image-take-btn"
                                    class="btn btn-outline-info btn-sm mb-3">
                                    {{ __('Add Image') }}
                                </button>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label">{{ __('Take Information') }}</label>
                            <div class="row" id="take-info-card-container">
                                @if (!empty($takeWith->infos))
                                    @foreach ($takeWith->infos as $index => $info)
                                        @php $takeCounter = max($takeCounter, $index); @endphp

                                        <div class="col-3 mb-4 take-info-card-item">
                                            <div class="border rounded p-3">

                                                <label>{{ __('Title') }}</label>
                                                <input type="text" name="infos[{{ $takeCounter }}][title]"
                                                    value="{{ $info->title }}" class="form-control mb-2" placeholder="Enter Title"/>

                                                <label>{{ __('Sub Title') }}</label>
                                                <textarea name="infos[{{ $takeCounter }}][sub_title]" class="form-control" rows="2"
                                                    placeholder="{{ __('Sub Title') }}">{{ $info->sub_title ?? '' }}</textarea>


                                                <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                                    class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                                    <input id="image" name="infos[{{ $takeCounter }}][icon]"
                                                        type="file" style="display: none;" accept="image/*"
                                                        x-ref="image"
                                                        @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                                    <template x-if="imagePreview">
                                                        <div
                                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                                            <div class="w-100 h-100 div-position">
                                                                <img :src="imagePreview"
                                                                    alt="{{ __('Header Preview') }}"
                                                                    class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                                <div class="plus__icon bg-white rounded">
                                                                    <img class="w-max h-max"
                                                                        src="{{ asset('assets/images/image-plus.svg') }}"
                                                                        alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template x-if="!imagePreview">
                                                        <div
                                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                                            @php
                                                                $infoIcon = Str::startsWith($info->icon, 'assets/')
                                                                    ? asset($info->icon)
                                                                    : asset('storage/' . $info->icon);
                                                            @endphp

                                                            <div class="w-100 h-100 div-position">
                                                                <img src="{{ $infoIcon }}"
                                                                    alt="{{ __('Current Header') }}"
                                                                    class="image_preview absolute inset-0 rounded template-div" />
                                                                <div class="plus__icon bg-white rounded">
                                                                    <img class="w-max h-max"
                                                                        src="{{ asset('assets/images/image-plus.svg') }}"
                                                                        alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>

                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm w-100 remove-take-info-card mt-4">
                                                    {{ __('Remove') }}
                                                </button>

                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" style="width: 9rem" id="add-take-info-btn"
                                    class="btn btn-outline-info btn-sm mb-3">
                                    {{ __('Add take info') }}
                                </button>
                            </div>
                        </div>

                        <div class="col-12 mt-1">
                            <label class="form-label">{{ __('Button name & link') }}</label>
                            <div class="row" id="facility-list-container">
                                @if (!empty($takeWith->button_group))
                                    @foreach ($takeWith->button_group as $index => $button)
                                        <div class="col-6 mb-2">
                                            <div class="input-group mt-2">
                                                <input type="text" name="button_group[{{ $index }}][name]"
                                                    value="{{ $button->name }}" class="form-control border w-25" />
                                                <input type="text" name="button_group[{{ $index }}][link]"
                                                    value="{{ $button->link }}" class="form-control border w-75"
                                                    style="padding-left:10px" />


                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-12 mt-1">
                            <div class="row">
                                <div class="col-4 py-4 ">
                                    <label>{{ __('Take With Us') }}</label>
                                    <div class="border rounded p-3">

                                        <label>{{ __('Title') }}</label>
                                        <input type="text" name="take_info[title]"
                                            value="{{ $takeWith->take_info[0]->title }}" class="form-control mb-2" />

                                        <label>{{ __('Sub Title') }}</label>
                                        <textarea name="take_info[sub_title]" class="form-control" rows="2" placeholder="{{ __('Sub Title') }}">{{ $takeWith->take_info[0]->sub_title ?? '' }}</textarea>


                                        <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                            class="mb-4 ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                            <input id="image" name="take_info[icon]" type="file"
                                                style="display: none;" accept="image/*" x-ref="image"
                                                @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                            <template x-if="imagePreview">
                                                <div
                                                    class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                                    <div class="w-100 h-100 div-position">
                                                        <img :src="imagePreview" alt="{{ __('Image Preview') }}"
                                                            class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                        <div class="plus__icon bg-white rounded">
                                                            <img class="w-max h-max"
                                                                src="{{ asset('assets/images/image-plus.svg') }}"
                                                                alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="!imagePreview">
                                                <div
                                                    class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                                    @php
                                                        $takeIcon = Str::startsWith(
                                                            $takeWith->take_info[0]->icon,
                                                            'assets/',
                                                        )
                                                            ? asset($takeWith->take_info[0]->icon)
                                                            : asset('storage/' . $takeWith->take_info[0]->icon);
                                                    @endphp

                                                    <div class="w-100 h-100 div-position">
                                                        <img src="{{ $takeIcon }}"
                                                            alt="{{ __('Current Header') }}"
                                                            class="image_preview absolute inset-0 rounded template-div" />
                                                        <div class="plus__icon bg-white rounded">
                                                            <img class="w-max h-max"
                                                                src="{{ asset('assets/images/image-plus.svg') }}"
                                                                alt="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-4 p-4">
                                    <label for="title" class="form-label">{{ __('Title') }}</label>
                                    <textarea name="title" id="editor_title" class="form-control" rows="4">{!! $takeWith->title ?? '' !!}</textarea>
                                </div>

                                <div class="col-4 p-4">
                                    <label for="title" class="form-label">{{ __('Sub Title') }}</label>
                                    <x-textarea name="sub_title" class="form-control" rows="3"
                                        placeholder="{{ __('Sub Title') }}" :value="old('sub_title', $takeWith->sub_title ?? '')"></x-textarea>
                                </div>
                            </div>
                        </div>
                        <div class="m-3">
                            <button class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- get started section --}}
    <div class="mx-4 card mt-3 rounded-lg border-none">
        <div class="card-header py-2 bg-primary ">
            <h2 class="text-white"> {{ __('Get Started Section') }}</h2>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row card-body">
                <div class="col-12">
                    <form action="{{ route('web-settings.update', 'get_started') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $getStarted = $webSettings->firstWhere('key', 'get_started')->decoded_value ?? null;
                        @endphp

                        <div class="row pb-4">
                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Title') }}</label>
                                <textarea name="title" id="editor_get_started_title" class="form-control" rows="4">{!! $getStarted->title ?? '' !!}</textarea>
                            </div>

                            <div class="col-6 px-4">
                                <label for="title" class="form-label">{{ __('Sub Title') }}</label>
                                <x-textarea name="sub_title" class="form-control" rows="3"
                                    placeholder="{{ __('Sub Title') }}" :value="old('sub_title', $getStarted->sub_title ?? '')"></x-textarea>
                            </div>
                        </div>

                        <div class="mt-2">
                            <button class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- footer section --}}
    <div class="mx-4 card mt-3 rounded-lg border-none">
        <div class="card-header py-2 bg-primary ">
            <h2 class="text-white"> {{ __('Footer Section') }}</h2>
        </div>
        <div class="d-flex justify-content-center">
            <div class="row card-body">
                <div class="col-12">
                    <form action="{{ route('web-settings.update', 'footer') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @php
                            $footer = $webSettings->firstWhere('key', 'footer')->decoded_value ?? null;
                            $iconCounter = 0;
                            $contactCounter = 0;
                        @endphp
                        <div class="col-12">
                            <div class="row">


                                <div class="col-3 ">
                                    <label class="form-label">{{ __('Footer Logo') }}</label>
                                    <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                        class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                        <input id="image" name="footer_logo" type="file" style="display: none;"
                                            accept="image/*" x-ref="image"
                                            @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                        <template x-if="imagePreview">
                                            <div
                                                class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                                <div class="w-100 h-100 div-position">
                                                    <img :src="imagePreview" alt="{{ __('Header Preview') }}"
                                                        class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                    <div class="plus__icon bg-white rounded">
                                                        <img class="w-max h-max"
                                                            src="{{ asset('assets/images/image-plus.svg') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="!imagePreview">
                                            <div
                                                class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                                @php
                                                    $footerThumb = Str::startsWith($footer->footer_logo, 'assets/')
                                                        ? asset($footer->footer_logo)
                                                        : asset('storage/' . $footer->footer_logo);
                                                @endphp

                                                <div class="w-100 h-100 div-position">
                                                    <img src="{{ $footerThumb }}" alt="{{ __('Current Header') }}"
                                                        class="image_preview absolute inset-0 rounded template-div" />
                                                    <div class="plus__icon bg-white rounded">
                                                        <img class="w-max h-max"
                                                            src="{{ asset('assets/images/image-plus.svg') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="col-3 ">
                                    <label class="form-label">{{ __('Footer Background Image') }}</label>
                                    <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                        class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                        <input id="image" name="footer_background" type="file"
                                            style="display: none;" accept="image/*" x-ref="image"
                                            @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                        <template x-if="imagePreview">
                                            <div
                                                class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                                <div class="w-100 h-100 div-position">
                                                    <img :src="imagePreview" alt="{{ __('Background Preview') }}"
                                                        class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                    <div class="plus__icon bg-white rounded">
                                                        <img class="w-max h-max"
                                                            src="{{ asset('assets/images/image-plus.svg') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="!imagePreview">
                                            <div
                                                class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                                @php
                                                    $footerBackgroundThumb = Str::startsWith(
                                                        $footer->footer_background,
                                                        'assets/',
                                                    )
                                                        ? asset($footer->footer_background)
                                                        : asset('storage/' . $footer->footer_background);
                                                @endphp

                                                <div class="w-100 h-100 div-position">
                                                    <img src="{{ $footerBackgroundThumb }}"
                                                        alt="{{ __('Current Header') }}"
                                                        class="image_preview absolute inset-0 rounded template-div" />
                                                    <div class="plus__icon bg-white rounded">
                                                        <img class="w-max h-max"
                                                            src="{{ asset('assets/images/image-plus.svg') }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 mt-5">
                            <label class="form-label">{{ __('Follow Us') }}</label>
                            <div class="row" id="follow-icon-container">
                                @if (!empty($footer->follow_us))
                                    @foreach ($footer->follow_us as $index => $follow)
                                        @php $iconCounter = max($iconCounter, $index); @endphp
                                        <div class="col-3 mb-3 follow-icon-item ">
                                            <div class="border rounded p-3">
                                                <input type="text" name="follow_us[{{ $iconCounter }}][link]"
                                                    value="{{ $follow->link }}" class="form-control mb-2" />

                                                <div x-data="{ imagePreview: null }" @click="$refs.image.click()"
                                                    class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview">
                                                    <input id="image" name="follow_us[{{ $iconCounter }}][icon]"
                                                        type="file" style="display: none;" accept="image/*"
                                                        x-ref="image"
                                                        @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file) }" />
                                                    <template x-if="imagePreview">
                                                        <div
                                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                                            <div class="w-100 h-100 div-position">
                                                                <img :src="imagePreview"
                                                                    alt="{{ __('Header Preview') }}"
                                                                    class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                                <div class="plus__icon bg-white rounded">
                                                                    <img class="w-max h-max"
                                                                        src="{{ asset('assets/images/image-plus.svg') }}"
                                                                        alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                    <template x-if="!imagePreview">
                                                        <div
                                                            class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">

                                                            @php
                                                                $iconGroup = Str::startsWith($follow->icon, 'assets/')
                                                                    ? asset($follow->icon)
                                                                    : asset('storage/' . $follow->icon);
                                                            @endphp

                                                            <div class="w-100 h-100 div-position">
                                                                <img src="{{ $iconGroup }}"
                                                                    alt="{{ __('Current Header') }}"
                                                                    class="image_preview absolute inset-0 rounded template-div" />
                                                                <div class="plus__icon bg-white rounded">
                                                                    <img class="w-max h-max"
                                                                        src="{{ asset('assets/images/image-plus.svg') }}"
                                                                        alt="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>

                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm remove-follow-icon mt-4 w-100">
                                                    <i class="fa fa-trash"></i> {{ __('Remove') }}
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" style="width: 9rem" id="add-follow-btn"
                                    class="btn btn-outline-info btn-sm mb-3">
                                    {{ __('Add follow') }}
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4 p-4 ">
                                <label>{{ __('Footer Information') }}</label>
                                <div class="border rounded p-3">

                                    <label>{{ __('Title') }}</label>
                                    <textarea name="footer_title" id="editor_footer_title" class="form-control" rows="4">{!! $footer->footer_title ?? '' !!}</textarea>
                                    <label class="mt-2">{{ __('Left Side text') }}</label>
                                    <input type="text" name="footer_left_side_text"
                                        value="{{ $footer->footer_left_side_text }}" class="form-control mb-2" />
                                    <label>{{ __('Right Side text') }}</label>
                                    <input type="text" name="footer_right_side_text"
                                        value="{{ $footer->footer_right_side_text }}" class="form-control mb-2" />

                                </div>
                            </div>
                            <div class="col-4 p-4 ">
                                <label>{{ __('Contact Us') }}</label>
                                <div class="border rounded p-3">

                                    <label>{{ __('Address') }}</label>
                                    <input type="text" name="contact_us[address]"
                                        value="{{ $footer->contact_us->address }}" class="form-control mb-2" />

                                    <label>{{ __('Phone Number') }}</label>
                                    <input type="text" name="contact_us[phone_number]"
                                        value="{{ $footer->contact_us->phone_number }}" class="form-control mb-2" />
                                </div>
                            </div>
                        </div>

                        <div class="m-3">
                            <button class="btn btn-primary">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/super-build/ckeditor.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const editorConfig = {
                toolbar: [
                    'undo', 'redo', '|',
                    'italic', '|',
                    'link', 'bulletedList', 'numberedList', '|',
                    'fontColor', 'fontBackgroundColor'
                ],

                fontColor: {
                    columns: 5,
                    documentColors: 10
                },

                fontBackgroundColor: {
                    columns: 5,
                    documentColors: 10
                },

                removePlugins: [
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'DocumentOutline',
                    'AIAdapter',
                    'AIAssistant',
                    'AIContext',
                    'Pagination',
                    'WProofreader',
                    'MathType',
                    'TableOfContents',
                    'FormatPainter',
                    'Template',
                    'SlashCommand',
                    'PasteFromOfficeEnhanced',
                    'PasteFromOfficeEnhancedPropagator',
                    'PasteFromOfficeEnhancedEditing'
                ]

            };

            document
                .querySelectorAll(
                    '#editor_title, #editor_premium_title, #editor_experience_title, #editor_how_it_work_title ,#editor_build_on_trust_title, #editor_promise_title, #editor_join_our_network_title, #editor_get_started_title, #editor_footer_title'
                )
                .forEach(element => {
                    CKEDITOR.ClassicEditor
                        .create(element, editorConfig)
                        .then(() => {
                            console.log('CKEditor initialized:', element.id);
                        })
                        .catch(error => {
                            console.error('CKEditor error:', error);
                        });
                });



        });

        //header section
        document.addEventListener('DOMContentLoaded', () => {
            let imageCount = {{ $imageGroupCounter ?? 0 }};
            const container = document.getElementById('image-group-container');
            document.getElementById('add-image-btn')?.addEventListener('click', () => {
                imageCount++;
                const item = document.createElement('div');
                item.classList.add('col-2', 'mb-3', 'image-item');
                item.innerHTML = `
                <div x-data="{ imagePreview: null }">
                    <div class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview" @click="$refs.image.click()">
                        <input type="file" name="trusted_client_image_group[${imageCount}][img]" style="display:none" accept="image/*" x-ref="image"
                            @change="const file = $event.target.files[0]; if (file) imagePreview = URL.createObjectURL(file)" />
                        <template x-if="imagePreview">
                            <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                <div class="w-100 h-100 div-position">
                                    <img :src="imagePreview" alt="{{ __('Image Preview') }}" class="image_preview rounded absolute inset-0 h-100 w-100" />
                                    <div class="plus__icon bg-white rounded">
                                        <img class="w-max h-max"src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template x-if="!imagePreview">
                            <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">
                                <div class="w-100 h-100 div-position">
                                    <div class="plus__icon bg-white rounded">
                                        <img class="w-max h-max" src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-image mt-4 w-100">
                        <i class="fa fa-trash"></i> {{ __('Remove') }}
                    </button>
                </div>`;
                container.appendChild(item);
                Alpine.initTree(item); // reinitialize Alpine for new items
            });

            document.addEventListener('click', (e) => {
                if (e.target.closest('.remove-image')) {
                    e.target.closest('.image-item').remove();
                }
            });
        });


        //how it work

        let workIndex = {{ count($howItWork->work_steps) }};
        $('#add-work-btn').on('click', function() {
            let html = `
            <div class="col-3 mb-4 work-card-item">
                <div class="border rounded p-3">
                    <label>{{ __('Number') }}</label>
                    <input type="number" name="work_steps[${workIndex}][number]" class="form-control mb-2" />

                    <label>{{ __('Title') }}</label>
                    <input type="text" name="work_steps[${workIndex}][title]" class="form-control mb-2" />

                    <label>{{ __('Subtitle') }}</label>
                    <input type="text" name="work_steps[${workIndex}][sub_title]" class="form-control mb-2" />

                     <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-how-it-work-card mt-2">
                            {{ __('Remove') }}
                        </button>

                </div>
            </div>
            `;

            $('#work-card-container').append(html);

            workIndex++;
        });
        // Removing a feature card for feature section
        $(document).on('click', '.remove-how-it-work-card', function() {
            $(this).closest('.work-card-item').remove();
        });


        // for build section
        let buildIndex = {{ count($buildOnTrust->sample) }};
        $('#add-build-btn').on('click', function() {
            let html = `
                <div class="col-3 mb-4 build-card-item">
                    <div class="border rounded p-3">

                        <label>{{ __(' Title') }}</label>
                        <input type="text" name="sample[${buildIndex}][title]" class="form-control mb-2" />

                        <label>{{ __('Description') }}</label>
                        <textarea name="sample[${buildIndex}][description]" class="form-control" rows="3"
                                                            placeholder="{{ __('Description') }}")"></textarea>

                        <div class="image-item">
                            <div x-data="{ buildImagePreview: null }" class="relative">
                                <div class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview"
                                    @click="$refs.image.click()">

                                    <!-- Hidden File Input -->
                                    <input type="file"
                                        name="sample[${buildIndex}][icon]"
                                        accept="image/*"
                                        style="display:none"
                                        x-ref="image"
                                        @change="const file = $event.target.files[0]; if (file) buildImagePreview = URL.createObjectURL(file)" />

                                    <!-- Image Preview (new upload) -->
                                    <template x-if="buildImagePreview">
                                        <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                            <div class="w-100 h-100 div-position">
                                                <img :src="buildImagePreview" alt="{{ __('Image Preview') }}" class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max"src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!buildImagePreview">
                                        <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">
                                            <div class="w-100 h-100 div-position">
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max" src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>


                                </div>
                                <!-- Remove Button -->
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-feature-card mt-4">
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                `;

            $('#build-card-container').append(html);

            buildIndex++;
        });
        // Removing a feature card for feature section
        $(document).on('click', '.remove-build-card', function() {
            $(this).closest('.build-card-item').remove();
        });



        //join our network list
        let joinListCounter = {{ count($joinOurNet->lists) }};
        const facilityListContainer = document.getElementById('facility-list-container');
        document.getElementById('add-join-list-btn').addEventListener('click', function() {
            joinListCounter++;

            const div = document.createElement('div');
            div.classList.add('col-6', 'my-2', 'facility-list-item');

            div.innerHTML = `
            <div class="input-group">
                <input type="text" class="form-control" name="lists[${joinListCounter}][list]" placeholder="{{ __('List description') }}" />
                <button type="button" style="2.4rem" class="btn btn-sm btn-outline-danger remove-list-button no-hover"><i class="fa fa-trash" style="color:red; width:14px"></i></button>
            </div>
        `;

            facilityListContainer.appendChild(div);
        });

        $(document).on('click', '.remove-list-button', function() {
            $(this).closest('.facility-list-item').remove();
        });


        // for join our network facility section
        let facilityIndex = {{ count($joinOurNet->facilities) }};
        $('#add-facility-btn').on('click', function() {
            let html = `
                <div class="col-3 mb-4 facility-card-item">
                    <div class="border rounded p-3">

                        <label>{{ __(' Title') }}</label>
                        <input type="text" name="facilities[${buildIndex}][title]" class="form-control mb-2" />

                        <label>{{ __('Description') }}</label>
                        <textarea name="facilities[${buildIndex}][description]" class="form-control" rows="3"
                                                            placeholder="{{ __('Description') }}")"></textarea>

                        <div class="image-item">
                            <div x-data="{ facilityImagePreview: null }" class="relative">
                                <div class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview"
                                    @click="$refs.image.click()">

                                    <!-- Hidden File Input -->
                                    <input type="file"
                                        name="facilities[${buildIndex}][icon]"
                                        accept="image/*"
                                        style="display:none"
                                        x-ref="image"
                                        @change="const file = $event.target.files[0]; if (file) facilityImagePreview = URL.createObjectURL(file)" />

                                    <!-- Image Preview (new upload) -->
                                    <template x-if="facilityImagePreview">
                                        <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                            <div class="w-100 h-100 div-position">
                                                <img :src="facilityImagePreview" alt="{{ __('Image Preview') }}" class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max"src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!facilityImagePreview">
                                        <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">
                                            <div class="w-100 h-100 div-position">
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max" src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>


                                </div>
                                <!-- Remove Button -->
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-facility-card mt-4">
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                `;

            $('#facility-card-container').append(html);

            buildIndex++;
        });
        // Removing a feature card for feature section
        $(document).on('click', '.remove-facility-card', function() {
            $(this).closest('.facility-card-item').remove();
        });


        //take with us
        document.addEventListener('DOMContentLoaded', () => {
            let imageCount = {{ $imageCounter ?? 0 }};
            const container = document.getElementById('image-take-group-container');
            document.getElementById('add-image-take-btn')?.addEventListener('click', () => {
                imageCount++;
                const item = document.createElement('div');
                item.classList.add('col-2', 'mb-3', 'take-image-item');
                item.innerHTML = `
                <div x-data="{ imagePreview: null }">
                    <div class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview" @click="$refs.image.click()">
                        <input type="file" name="image_group[${imageCount}][img]" style="display:none" accept="image/*" x-ref="image"
                            @change="const file = $event.target.files[0]; if (file) imagePreview = URL.createObjectURL(file)" />
                        <template x-if="imagePreview">
                            <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                <div class="w-100 h-100 div-position">
                                    <img :src="imagePreview" alt="{{ __('Image Preview') }}" class="image_preview rounded absolute inset-0 h-100 w-100" />
                                    <div class="plus__icon bg-white rounded">
                                        <img class="w-max h-max"src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template x-if="!imagePreview">
                            <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">
                                <div class="w-100 h-100 div-position">
                                    <div class="plus__icon bg-white rounded">
                                        <img class="w-max h-max" src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-image mt-4 w-100">
                        <i class="fa fa-trash"></i> {{ __('Remove') }}
                    </button>
                </div>`;
                container.appendChild(item);
                Alpine.initTree(item); // reinitialize Alpine for new items
            });

            document.addEventListener('click', (e) => {
                if (e.target.closest('.remove-take-group-image')) {
                    e.target.closest('.take-image-item').remove();
                }
            });
        });


        // for take with us section
        let takeInfoIndex = {{ count($takeWith->infos) }};
        $('#add-take-info-btn').on('click', function() {
            let html = `
                <div class="col-3 mb-4 take-info-card-item">
                    <div class="border rounded p-3">

                        <label>{{ __(' Title') }}</label>
                        <input type="text" name="infos[${takeInfoIndex}][title]" class="form-control mb-2" />

                        <label>{{ __('Sub Title') }}</label>
                        <textarea name="infos[${takeInfoIndex}][sub_title]" class="form-control" rows="2"
                                                            placeholder="{{ __('Sub Title') }}")"></textarea>

                        <div class="image-item">
                            <div x-data="{ ImagePreview: null }" class="relative">
                                <div class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview"
                                    @click="$refs.image.click()">

                                    <!-- Hidden File Input -->
                                    <input type="file"
                                        name="infos[${takeInfoIndex}][icon]"
                                        accept="image/*"
                                        style="display:none"
                                        x-ref="image"
                                        @change="const file = $event.target.files[0]; if (file) ImagePreview = URL.createObjectURL(file)" />

                                    <!-- Image Preview (new upload) -->
                                    <template x-if="ImagePreview">
                                        <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                            <div class="w-100 h-100 div-position">
                                                <img :src="ImagePreview" alt="{{ __('Image Preview') }}" class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max"src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!ImagePreview">
                                        <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">
                                            <div class="w-100 h-100 div-position">
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max" src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>


                                </div>
                                <!-- Remove Button -->
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-take-info-card mt-4">
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                `;

            $('#take-info-card-container').append(html);

            takeInfoIndex++;
        });
        // Removing a feature card for feature section
        $(document).on('click', '.remove-take-info-card', function() {
            $(this).closest('.take-info-card-item').remove();
        });


        // for footer section
        let followIndex = {{ count($footer->follow_us) }};
        $('#add-follow-btn').on('click', function() {
            let html = `
                <div class="col-3 mb-4 follow-icon-item">
                    <div class="border rounded p-3">
                        <input type="text" name="follow_us[${followIndex}][link]" class="form-control mb-2" />
                        <div class="image-item">
                            <div x-data="{ ImagePreview: null }" class="relative">
                                <div class="ant-upload ant-upload-select-picture-card flex flex-col justify-center items-center cursor-pointer relative h-32 w-100 image-preview"
                                    @click="$refs.image.click()">

                                    <!-- Hidden File Input -->
                                    <input type="file"
                                        name="follow_us[${followIndex}][icon]"
                                        accept="image/*"
                                        style="display:none"
                                        x-ref="image"
                                        @change="const file = $event.target.files[0]; if (file) ImagePreview = URL.createObjectURL(file)" />

                                    <!-- Image Preview (new upload) -->
                                    <template x-if="ImagePreview">
                                        <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center">
                                            <div class="w-100 h-100 div-position">
                                                <img :src="ImagePreview" alt="{{ __('Image Preview') }}" class="image_preview rounded absolute inset-0 h-100 w-100" />
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max"src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!ImagePreview">
                                        <div class="template-div z-10 text-black rounded absolute d-flex justify-content-center bg-dark">
                                            <div class="w-100 h-100 div-position">
                                                <div class="plus__icon bg-white rounded">
                                                    <img class="w-max h-max" src="{{ asset('assets/images/image-plus.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </template>


                                </div>
                                <!-- Remove Button -->
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-follow-icon mt-4">
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                `;

            $('#follow-icon-container').append(html);

            followIndex++;
        });
        // Removing a feature card for feature section
        $(document).on('click', '.remove-follow-icon', function() {
            $(this).closest('.follow-icon-item').remove();
        });
    </script>
@endpush
