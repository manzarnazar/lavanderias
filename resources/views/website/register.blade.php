<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry</title>
    <!-- Inter font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Poppins:wght@100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- accordion cdn  -->


    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>


    <script src="../tailwind.config.js"></script>

    <!-- custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/sign-in.css">
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ $appSetting?->websiteFaviconPath ?? './assets/logo/lav_logo.png' }}">


</head>

<body class="bg-neutral-50">
    <main>
        <div class="rs-sigin-area max-w-2lg  mx-auto mt-4 md:mt-[50px] mb-6 px-4 xl:px-0">
            <div class="col-span-12">
                <div class="grid grid-cols-12 gap-7">
                    <div class="col-span-12 md:col-span-6">
                        <div
                            class="sigin-left-side bg-mint-600 rounded-3xl p-6
                            lg:py-[56.5px] lg:px-[48px] relative overflow-hidden">
                            <div class="content">
                                <div
                                    class="logo w-[70px] h-[70px] md:w-[86px] md:h-[86px] bg-white rounded-lg md:rounded-2xl text-center flex items-center justify-center mb-4 md:mb-[30px]">
                                    <a href="{{ route('home') }}" class="w-auto  md:w-auto md:h-8 inline-block"
                                        style="height: 3rem">
                                        <img src="{{ $appSetting?->websiteFaviconPath ?? './assets/logo/lav_logo.png' }}"
                                            alt="" class="h-full w-full">
                                        {{-- <img src="{{ './assets/logo/lav_logo.png' }}" alt=""
                  class="h-full w-full"> --}}
                                    </a>
                                </div>
                                <h2
                                    class="text-xl md:text-[28px] font-semibold leading-[120%] text-neutral-100 mb-[10px]">
                                    Welcome to Laundry
                                </h2>
                                <p class="text-base md:text-lg font-normal leading-[140%] text-neutral-100 mb-[24px]">
                                    Your trusted partner for professional laundry and dry cleaning services
                                </p>
                                <div class="list">
                                    <ul>
                                        <li class="mb-[16px]">
                                            <a href="#"
                                                class="text-sm md:text-base font-medium leading-[130%] text-neutral-100 flex items-center gap-[10px]">
                                                <i
                                                    class="w-9 h-9 flex items-center justify-center bg-[rgba(255,255,255,0.16)] backdrop-blur-md rounded-full">
                                                    <img class="h-content " src="../assets/icons/white-check-box.svg"
                                                        alt="">
                                                </i>
                                                Free pickup and delivery
                                            </a>
                                        </li>
                                        <li class="mb-[16px]">
                                            <a href="#"
                                                class="text-sm md:text-base font-medium leading-[130%] text-neutral-100 flex items-center gap-[10px]">
                                                <i
                                                    class="w-9 h-9 flex items-center justify-center bg-[rgba(255,255,255,0.16)] backdrop-blur-md rounded-full">
                                                    <img class="h-content " src="../assets/icons/white-check-box.svg"
                                                        alt="">
                                                </i>
                                                24-48 hour turnaround
                                            </a>
                                        </li>
                                        <li class="mb-[16px]">
                                            <a href="#"
                                                class="text-sm md:text-base font-medium leading-[130%] text-neutral-100 flex items-center gap-[10px]">
                                                <i
                                                    class="w-9 h-9 flex items-center justify-center bg-[rgba(255,255,255,0.16)] backdrop-blur-md rounded-full">
                                                    <img class="h-content " src="../assets/icons/white-check-box.svg"
                                                        alt="">
                                                </i>
                                                100% satisfaction guarantee
                                            </a>
                                        </li>
                                        <li class="mb-6">
                                            <a href="#"
                                                class="text-sm md:text-base font-medium leading-[130%] text-neutral-100 flex items-center gap-[10px]">
                                                <i
                                                    class="w-9 h-9 flex items-center justify-center bg-[rgba(255,255,255,0.16)] backdrop-blur-md rounded-full">
                                                    <img class="h-content " src="../assets/icons/white-check-box.svg"
                                                        alt="">
                                                </i>
                                                Eco-friendly cleaning
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="box bg-[rgba(255,255,255,0.16)] backdrop-blur-md rounded-2xl p-4 md:p-6">
                                    <div class="log-in-customers flex items-center gap-[10px] mb-4">
                                        <div class="log-in-customers-img flex items-center">
                                            <img class="w-9 h-9 object-cover border border-white rounded-full"
                                                src="../assets/images/login/login-author-01.png" alt="">
                                            <img class="w-9 h-9 object-cover border border-white rounded-full"
                                                src="../assets/images/login/login-author-02.png" alt="">
                                            <img class="w-9 h-9 object-cover border border-white rounded-full"
                                                src="../assets/images/login/login-author-03.png" alt="">
                                        </div>
                                        <div>
                                            <h3
                                                class="text-sm md:text-base font-normal leading-[100%] text-neutral-50 flex items-center mb-[5px]">
                                                Trusted by
                                            </h3>
                                            <h4
                                                class="text-base md:text-lg font-semibold leading-[100%] text-neutral-50 flex items-center">
                                                {{ $customers->count() }}+ customers
                                            </h4>
                                        </div>
                                    </div>
                                    @php
                                        if ($ratings->isEmpty()) {
                                            $avgRating = 0;
                                        } else {
                                            $avgRating = round(($ratings->sum('rating') / $ratings->count()) * 2) / 2;
                                        }
                                        $fullStars = floor($avgRating);
                                        $halfStar = $avgRating - $fullStars == 0.5;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                    @endphp
                                    <div class="log-in-rat flex items-center  ">
                                        @for ($i = 0; $i < $fullStars; $i++)
                                            <img src="../assets/icons/star.svg" alt="Full Star">
                                        @endfor
                                        @if ($halfStar)
                                            <img src="../assets/icons/star-half.svg" alt="Half Star">
                                        @endif
                                        <span
                                            class="text-sm font-medium leading-[140%] text-neutral-50 flex items-center ml-2">
                                            {{ $avgRating }}/5 Rating
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <div class="sigin-right-side bg-white  rounded-3xl
                            p-6 relative">
                            <form action="{{ route('register-user') }}" method="post" class="content">
                                @csrf
                                <h3
                                    class="text-xl md:text-[28px]  font-semibold leading-[120%] text-neutral-500 mb-[10px] text-center">
                                    Get Started
                                </h3>
                                <p
                                    class="text-base md:text-lg font-normal leading-[140%] text-neutral-500 mb-[24px] text-center">
                                    Create your account to start using our services
                                </p>
                                <div
                                    class="login-tab flex items-center justify-between [52px] leading-[40px] p-[6px] bg-mint-50 rounded-[50px] mb-6">
                                    <a
                                        class="text-base font-medium leading-[40px] w-[49%] text-center inline-block text-neutral-500 h-[40px]">
                                        Log In
                                    </a>
                                    <a
                                        class="text-base font-medium leading-[40px] w-[49%] text-center inline-block text-neutral-500 h-[40px] active">
                                        Register
                                    </a>
                                </div>
                                <div class="register-btn mb-6 flex gap-3">
                                    <a id="btn-customer"
                                        class="register-option {{ request()->routeIs('register') ? 'active' : '' }} text-lg font-normal leading-[140%] text-neutral-700 h-[108px] inline-block w-[50%] flex flex-col gap-[10px] items-center justify-center border-[1.5px] border-solid border-neutral-200 rounded-xl"
                                        href="{{ route('register') }}">
                                        <img class="w-[fit-content]" src="../assets/icons/user-grey.svg"
                                            alt="">
                                        Customer
                                    </a>

                                    <a id="btn-vendor"
                                        class="{{ request()->routeIs('register-vendor') ? 'active' : '' }} register-option text-lg font-normal leading-[140%] text-neutral-700 h-[108px] inline-block w-[50%] flex flex-col gap-[10px] items-center justify-center border-[1.5px] border-solid border-neutral-200 rounded-xl"
                                        href="{{ route('register-vendor') }}">
                                        <img class="w-[fit-content]" src="../assets/icons/shop-grey.svg"
                                            alt="">
                                        Vendor
                                    </a>

                                    <input type="hidden" id="customer" name="customer" value="1">

                                </div>

                                <div class="input-items mb-4 flex flex-wrap md:flex-nowrap gap-[0] md:gap-3">
                                    <div class="w-full md:w-[50%] mb-4 md:mb-0">
                                        <label
                                            class="block text-base md:text-lg font-medium leading-[120%] text-neutral-700 mb-[10px]">First
                                            Name<span style="color:red">*</span></label>
                                        <div class="relative">
                                            <input class="w-full !pl-4" type="text" name="first_name"
                                                placeholder="John" value="{{ old('first_name') }}">
                                            @error('first_name')
                                                <span class="text-danger" style="color:red">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="w-full md:w-[50%]">
                                        <label
                                            class="block text-base md:text-lg font-medium leading-[120%] text-neutral-700 mb-[10px]">Last
                                            Name</label>
                                        <div class="relative">
                                            <input class="w-full !pl-4" type="text" name="last_name"
                                                placeholder="Doe" value="{{ old('last_name') }}">
                                            @error('last_name')
                                                <span class="text-danger" style="color:red">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="input-items mb-4">
                                    <label
                                        class="block text-base md:text-lg font-medium leading-[120%] text-neutral-700 mb-[10px]">Email
                                        Address<span style="color:red">*</span></label>
                                    <div class="relative">
                                        <img class="absolute" src="../assets/icons/envelope-grey.svg" alt="">
                                        <input class="w-full" type="email" name="email"
                                            placeholder="Enter your email address" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="text-danger" style="color:red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="input-items mb-4">
                                    <label
                                        class="block text-base md:text-lg font-medium leading-[120%] text-neutral-700 mb-[10px]">Phone
                                        Number</label>
                                    <div class="relative">
                                        <img class="absolute" src="../assets/icons/phone-grey.svg" alt="">
                                        <input class="w-full" type="number" name="mobile"
                                            placeholder="+1 (555) 123-4567" value="{{ old('mobile') }}">
                                        @error('mobile')
                                            <span class="text-danger" style="color:red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="input-items mb-6 toggle__password relative">
                                    <label
                                        class="block text-base md:text-lg font-medium leading-[120%] text-neutral-700 mb-[10px]">
                                        Password <span style="color:red">*</span>
                                    </label>
                                    <div class="relative">
                                        <!-- Lock icon left side -->
                                        <img class="absolute left-3 top-1/2 -translate-y-1/2"
                                            src="../assets/icons/lock.svg" alt="lock icon">

                                        <!-- Password input -->
                                        <input class="w-full pl-10 pr-10" type="password" name="password"
                                            id="login_password" placeholder="Enter password">

                                        <!-- Eye icon right side -->
                                        <span class="absolute top-1/2 right-3 -translate-y-1/2 cursor-pointer"
                                            onclick="togglePassword('login_password','toggleLoginPasswordIcon')">
                                            <i class="far fa-eye-slash" id="toggleLoginPasswordIcon"></i>
                                        </span>

                                        <!-- Validation error -->
                                        @error('password')
                                            <span class="text-danger" style="color:red">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex items-start gap-[10px] mb-6">
                                    <label for="card-1-container" class="flex items-center">
                                        <input type="checkbox" name="card-1-container" id="card-1-container"
                                            class="peer sr-only">
                                        <img src="../assets/icons/unchecked.svg" alt=""
                                            class="w-4 h-4 mt-[3px] flex-[0_0_auto] peer-checked:hidden relative z-10">
                                        <img src="../assets/icons/checked.svg" alt=""
                                            class="w-4 h-4 mt-[3px] flex-[0_0_auto] hidden peer-checked:block relative z-10">
                                    </label>
                                    <div class="w-50">
                                        <span class="text-base font-normal leading-[150%] text-neutral-500">
                                            I agree to the <a class="text-mint-600"
                                                href="{{ route('terms.condition') }}">Terms of
                                                Service</a>
                                            and <a class="text-mint-600" href="{{ route('privacy.policy') }}">Privacy
                                                Policy</a>
                                        </span> <br>
                                        @error('card-1-container')
                                            <span class="text-red-600 text-sm">
                                                You must agree to the Terms & Privacy Policy
                                            </span>
                                        @enderror
                                    </div>


                                </div>


                                <button type="submit"
                                    class="flex items-center w-full h-[48px] bg-mint-600 text-white justify-center gap-[10px] ml-auto text-base text-mint-600 font-semibold leading-[133%] h-[32px] w-[110px] border-[1.50px] border-neutral-100 rounded-xl mb-4 md:mb-6">
                                    Create Account <img class="filter brightness-0 invert w-[14px] h-[14px]"
                                        src="../assets/icons/green-right-arrow.svg" alt="">
                                </button>
                                <div class="block text-center">
                                    <span
                                        class="text-base md:text-lg font-normal leading-[140%] text-neutral-500">Already
                                        have an account?
                                        <a href="{{ route('sign-in') }}"
                                            class="text-base md:text-lg font-semibold leading-[100%] text-mint-600">Sign
                                            In</a></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @php
        $user = Auth::user();
    @endphp
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Laravel outputs the logged-in user as JSON
        const user = @json($user);
        const isCustomer = @json($user?->hasRole('customer') ?? false);

        if (user && isCustomer) {
            const lastRoute = localStorage.getItem('lastRoute');

            if (lastRoute) {
                localStorage.removeItem('lastRoute');
                window.location.href = lastRoute;
            } else {
                window.location.href = '/'
            }
        }

    });

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



</html>
