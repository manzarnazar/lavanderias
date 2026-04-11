@php
    $appSetting = App\Models\AppSetting::first();
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fav icon -->
    <link rel="icon" type="image/png" sizes="16x16"
      href="{{ $appSetting?->websiteFaviconPath ?? asset('web/fav-icon.png') }}">

    <!-- custome css -->
    <link rel="stylesheet" href="{{ asset('web/css/login.css') }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('web/css/bootstrap.css') }}">
    <!-- Font awesome -->
    <link rel="stylesheet" href="{{ asset('web/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <title>{{ config('app.name') }} Log In</title>
</head>
<style>
    .terms {
        font-size: 0.675rem;
        margin-top: 0.5rem;
    }

    .terms a {
        margin: 0 4px;
        text-decoration: none;
    }

    .terms a:hover {
        text-decoration: underline;
    }

    .credential-box {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        position: relative;
    }

    .title {
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 14px
    }
</style>

<body>

    <!-- Login-Section -->
    <section class="login-section">
        <form role="form" class="pui-form pt-md-5" id="loginform" method="POST" action="{{ route('login') }}"> @csrf
            <div class="card loginCard">
                <div class="logo-section">
                    <img src="{{ $appSetting?->websiteLogoPath ?? asset('web/logo.png') }}" alt=""
                        width="100%">

                </div>
                <div class="card-body">
                    <div class="page-content text-center">
                        <h2 class="pageTitle mb-3">Dashboard Login</h2>
                        <p class="pagePera">Hay, Enter your details to get login to your account</p>
                    </div>

                    <div class="form-outline form-white mb-4">
                        <input type="text" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                            placeholder="Email">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-outline form-white mb-3">
                        <div class="position-relative passwordInput">
                            <input type="password" name="password" id="password"
                                class="form-control py-2 @error('password') is-invalid @enderror"
                                placeholder="Password">
                            <span class="eye" onclick="showHidePassword()">
                                <i class="far fa-eye-slash" id="togglePassword"></i>
                            </span>
                        </div>



                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="terms mt-2 text-muted small">

                            <a href="{{ route('terms.condition') }}" class="link-primary text-decoration-none">Terms &
                                Conditions</a>
                            and
                            <a href="{{ route('privacy.policy') }}" class="link-primary text-decoration-none">Privacy
                                Policy</a>.
                        </div>

                    </div>

                    @if (config('app.env') == 'local')
                        <div class=" p-2 rounded credential-box" style="border: 1px solid #28c593;">
                            <div class="title" style="border-bottom: 1px solid #f4ebeb">Admin Credentials</div>


                            <div class="d-flex justify-content-between">
                                <div style="font-size: 12px">
                                    <span><strong>{{ __('Email:') }}</strong>
                                        <span>root@readylaundry.com</span></span><br>
                                    <span><strong>{{ __('Password:') }}</strong>
                                        <span>{{ __('secret@123') }}</span></span>
                                </div>
                                <div class="s-flex justify-content-center align-items-center">

                                    <button type="button" class="btn btn-sm btn-primary bgBlue  w-100"
                                        onclick="document.getElementById('email').value = 'root@readylaundry.com'; document.getElementById('password').value = 'secret@123';">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-2 rounded mt-2 credential-box" style="border: 1px solid #28c593;">
                            <div class="title" style="border-bottom: 1px solid #f4ebeb">Shop Credentials</div>
                            <div class="d-flex justify-content-between">
                                <div style="font-size: 12px">
                                    <span><strong>{{ __('Email:') }}</strong>
                                        <span>demo-shop@readylaundry.com</span></span><br>
                                    <span><strong>{{ __('Password:') }}</strong>
                                        <span>{{ __('secret@123') }}</span></span>
                                </div>
                                <div class="s-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary btn-primary w-100 "
                                        onclick="document.getElementById('email').value = 'demo-shop@readylaundry.com'; document.getElementById('password').value = 'secret@123';">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif


                    <style>
                        .bgBlue {
                            background: #28c593;
                            color: #fff !important;
                        }
                    </style>

                    <button type="submit" class="btn loginButton" type="submit">Login</button>

                </div>
            </div>
        </form>
    </section>
    <!--End-Login-Section -->

    <script src="{{ asset('web/js/jquery.min.js') }}"></script>

    <script>
        function showHidePassword() {
            const password = document.getElementById("password");
            const toggle = document.getElementById("togglePassword");

            if (password.type === "password") {
                password.type = "text";
                toggle.classList.remove("fa-eye-slash");
                toggle.classList.add("fa-eye");
            } else {
                password.type = "password";
                toggle.classList.remove("fa-eye");
                toggle.classList.add("fa-eye-slash");
            }
        }

        function copy() {
            $('#email').val('root@laundrymart.com');
            $('#password').val('secret@123');
        }

        function copystore() {
            $('#email').val('root@vendor.com');
            $('#password').val('secret@123');
        }
    </script>

</body>

</html>
