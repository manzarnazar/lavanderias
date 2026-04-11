@extends('layouts.app')

@section('content')
    <div class="container-fluid my-4 my-md-0">
        <div class="row h-100vh align-items-center">
            <div class="col-md-8 m-auto">
                <form @can('profile.update') action="{{ route('profile.update') }}" @endcan method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card shadow rounded-12 border-0">
                        <div class="card-header py-3">
                            <h3 class="m-0">{{ __('Edit_Personal_Information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('First_Name') }}<span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="first_name"
                                            value="{{ $user->first_name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Last_Name') }}</label>
                                        <input class="form-control" type="text" name="last_name"
                                            value="{{ $user->last_name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Mobile') }}<span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="mobile"
                                            value="{{ $user->mobile }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('Email_Address') }}</label>
                                        <input class="form-control" type="text" name="email"
                                            value="{{ $user->email }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('Profile_Photo') }}</label>

                                        <!-- Image Preview -->
                                        <div class="mb-3">
                                            <img id="photoPreview"
                                                src="{{ $user->profile_photo_path ?? 'https://via.placeholder.com/120' }}"
                                                alt="Profile Preview" width="120" height="120"
                                                class="rounded-circle border">
                                        </div>

                                        <!-- File Input -->
                                        <input class="form-control @error('profile_photo') is-invalid @enderror"
                                            type="file" name="profile_photo" id="profilePhotoInput" accept="image/*">

                                        @error('profile_photo')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between py-3">
                            <a href="{{route('profile.index')}}" class="btn btn-danger">{{ __('Back') }}</a>
                            @can('profile.update')
                                <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                            @endcan
                        </div>
                </form>
            </div>
        </div>
    </div>

    </div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById("profilePhotoInput");
        const preview = document.getElementById("photoPreview");

        input.addEventListener("change", function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                };

                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
