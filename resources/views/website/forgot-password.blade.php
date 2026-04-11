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
        href="{{ $appSetting?->websiteFaviconPath ?? './assets/logo/Logo.svg' }}">


</head>

<body class="bg-neutral-50">
    <main>
        <div class="rs-sigin-area max-w-2lg  mx-auto mt-[50px] mb-6 px-4 xl:px-0">
            <div class="col-span-12">
                <div class="grid grid-cols-12 gap-6 md:gap-7">
                    <div class="col-span-12 md:col-span-6">
                        <div
                            class="sigin-left-side bg-mint-600 rounded-3xl p-6
                            lg:py-[56.5px] lg:px-[48px] relative overflow-hidden">
                            <div class="content">
                                <div
                                    class="logo w-[70px] h-[70px] md:w-[86px] md:h-[86px] bg-white rounded-lg md:rounded-2xl text-center flex items-center justify-center mb-4 md:mb-[30px]">
                                    <a href="{{ route('home') }}" class="w-auto  md:w-auto md:h-8 inline-block"
                                        style="height: 3rem">
                                        <img src="{{ $appSetting?->websiteFaviconPath ?? './assets/logo/Logo.svg' }}"
                                            alt="" class="h-full w-full">
                                        {{-- <img src="{{ './assets/logo/Logo.svg' }}" alt=""
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
                        <div
                            class="sigin-right-side bg-white rounded-3xl
                            p-10 relative h-full flex justify-center items-center flex-col ">

                            @if (session('otp'))
                                <form action="{{ route('confirm-otp') }}" method="post" class="content">
                                    @csrf
                                    <h3
                                        class="text-xl md:text-[28px]  font-semibold leading-[120%] text-neutral-500 mb-[30px] text-center">
                                        Email Verification!
                                    </h3>
                                    <p
                                        class="text-base md:text-lg font-normal leading-[140%] text-neutral-500 mb-[24px] text-center">
                                        Enter the 6 Digit OTP Code that just we sent to your email
                                        {{ session('email') }}
                                    </p>

                                    <div class="input-items input-items-input mb-5">
                                        <div id="otp">
                                            <input class="verification__input !p-0" type="text" maxlength="1"
                                                name="otp1" placeholder="_" />
                                            <input class="verification__input !p-0" type="text" maxlength="1"
                                                name="otp2" placeholder="_" />
                                            <input class="verification__input !p-0" type="text" maxlength="1"
                                                name="otp3" placeholder="_" />
                                            <input class="verification__input !p-0" type="text" maxlength="1"
                                                name="otp4" placeholder="_" />
                                            <input class="verification__input !p-0" type="text" maxlength="1"
                                                name="otp5" placeholder="_" />
                                            <input class="verification__input !p-0" type="text" maxlength="1"
                                                name="otp6" placeholder="_" />
                                        </div>


                                    </div>
                                    @error('otp')
                                        <p style="color:red; text-align:center; margin-top:10px;">
                                            {{ $message }}
                                        </p>
                                    @enderror
                                    <p id="otp-timer"
                                        class="text-base md:text-lg font-normal leading-[140%] text-neutral-500 mb-[24px] text-center">
                                        You can resend OTP in 00:30
                                    </p>



                                    <button type="submit" id="confirm-btn"
                                        class=" mt-5 flex items-center w-full h-[48px] bg-mint-600 text-white justify-center gap-[10px] ml-auto text-base text-mint-600 font-semibold leading-[133%] h-[32px] w-[110px] border-[1.50px] border-neutral-100 rounded-xl mb-4 md:mb-6">
                                        Confirm OTP <img class="filter brightness-0 invert w-[14px] h-[14px]"
                                            src="../assets/icons/green-right-arrow.svg" alt="">
                                    </button>


                                </form>
                                <div class="w-full" style="display: none" id="send-btn">
                                    <form action="{{ route('forgot-send-otp') }}" method="post" class="content">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ session('email') }}">
                                        <button type="submit"
                                            class=" flex items-center w-full h-[48px] bg-mint-600 text-white justify-center gap-[10px] ml-auto text-base text-mint-600 font-semibold leading-[133%] h-[32px] w-[110px] border-[1.50px] border-neutral-100 rounded-xl">
                                            Re-Send OTP <img class="filter brightness-0 invert w-[14px] h-[14px]"
                                                src="../assets/icons/green-right-arrow.svg" alt="">
                                        </button>
                                    </form>
                                </div>
                            @elseif(session('otp_verified'))
                                <form id="password-form" action="{{ route('set-password') }}" method="post"
                                    class="content">
                                    @csrf
                                    <h3
                                        class="text-xl md:text-[28px]  font-semibold leading-[120%] text-neutral-500 mb-[30px] text-center">
                                        Create New Password
                                    </h3>
                                    <p
                                        class="text-base md:text-lg font-normal leading-[140%] text-neutral-500 mb-[24px] text-center">
                                        Type and confirm a secure new password for your account.
                                    </p>


                                    <div class="input-items-zero mb-10 toggle__password">
                                        <label
                                            class="block text-base md:text-lg font-medium leading-[120%] text-neutral-700 mb-[10px]">
                                            Create new password
                                        </label>

                                        <input class="w-full" type="password" name="new_password" id="new_password"
                                            placeholder="********">

                                        @error('new_password')
                                            <p class="text-danger" style="color:red">{{ $message }}</p>
                                        @enderror


                                        <span onclick="togglePassword('new_password','toggleNewPasswordIcon')">
                                            <i class="far fa-eye-slash" id="toggleNewPasswordIcon"></i>
                                        </span>


                                    </div>


                                    <div class="input-items-zero mb-10 toggle__password">
                                        <label
                                            class="block text-base md:text-lg font-medium leading-[120%] text-neutral-700 mb-[10px]">
                                            Confirm password
                                        </label>

                                        <input class="w-full" type="password" name="new_password_confirmation"
                                            id="new_password_confirmation" placeholder="********">

                                        <span
                                            onclick="togglePassword('new_password_confirmation','toggleConfirmNewPasswordIcon')">
                                            <i class="far fa-eye-slash" id="toggleConfirmNewPasswordIcon"></i>
                                        </span>

                                        @error('new_password_confirmation')
                                            <span class="text-danger" style="color:red">{{ $message }}</span>
                                        @enderror
                                    </div>



                                    <button type="submit"
                                        class="mt-5 flex items-center w-full h-[48px] bg-mint-600 text-white justify-center gap-[10px] ml-auto text-base text-mint-600 font-semibold leading-[133%] h-[32px] w-[110px] border-[1.50px] border-neutral-100 rounded-xl mb-4 md:mb-6">
                                        Set Password <img class="filter brightness-0 invert w-[14px] h-[14px]"
                                            src="../assets/icons/green-right-arrow.svg" alt="">
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('forgot-send-otp') }}" method="post" class="content">
                                    @csrf
                                    <h3
                                        class="text-xl md:text-[28px]  font-semibold leading-[120%] text-neutral-500 mb-[30px] text-center">
                                        Forgot Password
                                    </h3>
                                    <p
                                        class="text-base md:text-lg font-normal leading-[140%] text-neutral-500 mb-[24px] text-center">
                                        Enter the email address that you used when register to your account. You will
                                        receive a OTP code.
                                    </p>

                                    <div class="input-items mb-10">
                                        <label
                                            class="block text-base md:text-lg font-medium leading-[120%] text-neutral-700 mb-[10px]">Email
                                            Address</label>
                                        <div class="relative">
                                            <img class="absolute" src="../assets/icons/envelope-grey.svg"
                                                alt="">
                                            <input class="w-full" type="email" name="email"
                                                placeholder="Enter your email address">
                                            @error('email')
                                                <span class="text-danger" style="color:red">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <button type="submit"
                                        class="mt-5 flex items-center w-full h-[48px] bg-mint-600 text-white justify-center gap-[10px] ml-auto text-base text-mint-600 font-semibold leading-[133%] h-[32px] w-[110px] border-[1.50px] border-neutral-100 rounded-xl mb-4 md:mb-6">
                                        Send OTP <img class="filter brightness-0 invert w-[14px] h-[14px]"
                                            src="../assets/icons/green-right-arrow.svg" alt="">
                                    </button>
                                </form>
                            @endif




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<style>
    .toggle__password {
        position: relative;
    }

    .toggle__password input {
        padding-right: 2.5rem;
        /* space for eye icon */
    }

    .toggle__password span i {
        color: #888;
    }

    .toggle__password span:hover i {
        color: #555;
    }

    .toggle__password {
        position: relative;
    }

    .toggle__password span {
        position: absolute;
        right: 16px;
        position: absolute;
        top: 70%;
        transform: translateY(-50%);
    }

    #otp {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .verification__input {
        width: 40px;
        height: 50px;
        text-align: center;
        font-size: 24px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>


<script>
    let timeLeft = 30;
    const timerElement = document.getElementById('otp-timer');
    const sendElement = document.getElementById('send-btn');
    const confirmElement = document.getElementById('confirm-btn');
    const form = document.querySelector('form'); // OTP form
    const inputs = document.querySelectorAll('#otp input');
    let countdown;


    if (sessionStorage.getItem('otp_time_left')) {
        timeLeft = parseInt(sessionStorage.getItem('otp_time_left'));
    } else {
        sessionStorage.setItem('otp_time_left', timeLeft);
    }


    confirmElement.disabled = true;
    confirmElement.style.opacity = "0.5";
    confirmElement.style.cursor = "not-allowed";


    function checkOTPInputs() {
        let allFilled = Array.from(inputs).every(input => input.value.trim() !== "");
        confirmElement.disabled = !allFilled;
        confirmElement.style.opacity = allFilled ? "1" : "0.5";
        confirmElement.style.cursor = allFilled ? "pointer" : "not-allowed";
    }


    countdown = setInterval(() => {

        if (timeLeft >= 0) {

            let seconds = timeLeft < 10 ? '0' + timeLeft : timeLeft;
            timerElement.textContent = `OTP will send within 00:${seconds}`;


            sessionStorage.setItem('otp_time_left', timeLeft);

            timeLeft--;

        } else {

            clearInterval(countdown);


            sessionStorage.removeItem('otp_time_left');

            timerElement.textContent = "Didn't receive OTP? Resend now";
            sendElement.style.display = '';
            confirmElement.style.display = 'none';
        }

    }, 1000);


    inputs.forEach((input, index) => {
        input.addEventListener('keydown', (e) => {
            if (e.key === "Backspace" && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value && index < inputs.length - 1) inputs[index + 1].focus();
            checkOTPInputs();
        });
    });


    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
                method: form.method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                let oldError = document.getElementById('otp-error');
                if (oldError) oldError.remove();

                if (data.errors || data.error) {

                    const p = document.createElement('p');
                    p.id = 'otp-error';
                    p.style.color = 'red';
                    p.style.textAlign = 'center';
                    p.style.marginTop = '10px';
                    p.textContent = data.errors?.otp || data.error || 'Invalid OTP';
                    form.appendChild(p);

                    inputs.forEach(input => input.value = '');
                    inputs[0].focus();
                    checkOTPInputs();

                } else {

                    clearInterval(countdown);


                    sessionStorage.removeItem('otp_time_left');

                    window.location.reload();
                }
            })
            .catch(err => console.log(err));
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
