  <style>
      .menu_link {
          color: #4b5563;
          font-weight: 500;
          transition: color .2s ease;
      }

      .menu_link:hover,
      .menu_link_active {
          color: #006CBA;
      }
  </style>

  <nav class="bg-white sticky top-0 py-4 md:py-6 z-40 ">
      <section class="max-w-2lg mx-auto px-4 xl:px-0 flex items-center justify-between">
          <a href="{{ route('home') }}" class="w-auto  md:w-auto md:h-8 inline-block" style="height: 3rem">
              <img src="{{ $appSetting?->websiteLogoPath ?? './assets/logo/lav_logo.png' }}" alt=""
                  class="h-full w-full">
                  {{-- <img src="{{ './assets/logo/lav_logo.png' }}" alt=""
                  class="h-full w-full"> --}}
          </a>


          <div class=" items-center justify-center gap-[36px] hidden lg:flex">
              <a class="menu_link {{ request()->routeIs('home') ? 'menu_link_active' : '' }}"
                  href={{ route('home') }}>Home</a>
              <a class="menu_link {{ request()->routeIs('all-services') ? 'menu_link_active' : '' }}"
                  href="{{ route('all-services') }}">Services</a>
              <a class="menu_link getLocationBtn {{ request()->routeIs('nearest-stores') ? 'menu_link_active' : '' }}"
                href="javascript:void(0)">Nearest Store</a>
              <a class="menu_link {{ request()->routeIs('faq') ? 'menu_link_active' : '' }}"
                  href="{{ route('faq', ['slug' => 'all']) }}">FAQ</a>
              <a class="menu_link {{ request()->routeIs('contact') ? 'menu_link_active' : '' }}"
                  href="{{ route('contact') }}">Contact</a>
          </div>


          @if (Auth::check() && Auth::user()->hasRole('customer'))
              <div class="hidden lg:flex justify-center items-center gap-[10px]">
                  @php
                      $user = auth()->user()->customer;
                      $customer = auth()->user();
                      $favouriteStores = $user->favouriteStore ?? 0;
                  @endphp

                  <div class="relative group">
                      <!-- Heart Button -->
                      <button
                          class="w-12 h-12 rounded-full flex justify-center items-center border border-mint-100 transition-all duration-150 hover:bg-mint-600 group">
                          <img src="../assets/icons/heart-green.svg"
                              class="h-[18px] w-[18px] group-hover:filter group-hover:invert group-hover:brightness-0">

                          <span  id="fav-count"
                              class="absolute -top-1 -right-2 bg-mint-600 text-xs text-white w-5 h-5 rounded-full flex justify-center items-center">
                              {{ $favouriteStores?->count() }}
                          </span>
                      </button>

                      <!-- Dropdown -->
                      <div
                          class="absolute right-0 mt-3 w-[300px] bg-white rounded-2xl shadow-xl border border-neutral-200 opacity-0 invisible scale-95 group-hover:opacity-100 group-hover:visible group-hover:scale-100 transition-all duration-200 z-50">
                          <div class="max-h-[400px] overflow-y-auto p-4">

                              @if (!$favouriteStores->isEmpty())
                                  @foreach ($favouriteStores as $store)
                                      <div
                                          class="py-3 flex justify-between items-center gap-4 border-b border-neutral-200 last:border-0">
                                          <div class="flex-1">
                                              <p class="text-sm font-semibold text-neutral-700">
                                                  {{ $store->name ?? '' }}
                                              </p>

                                              <div class="flex gap-2 mt-1">
                                                  <p class="text-sm text-neutral-500 flex items-center gap-1">
                                                      <img src="../assets/icons/star-gold.svg" alt="">
                                                      {{ round($store->ratings->avg('rating') * 2) / 2 }}
                                                      rating
                                                  </p>
                                              </div>

                                              <a href="{{ route('store-services', $store->slug) }}"
                                                  class="block md:hidden mt-2 w-full h-9 rounded-xl text-xs font-medium text-center text-neutral-500
                                                     border-[1.5px] border-neutral-200 px-4 py-2 transition-all hover:bg-mint-600 hover:text-white">
                                                  Book Again
                                              </a>
                                          </div>

                                          <a href="{{ route('store-services', $store->slug) }}"
                                              class="hidden md:block w-32 h-9 rounded-xl text-xs font-medium text-center text-neutral-500
                                                 border-[1.5px] border-neutral-200 px-4 py-2 transition-all
                                                     hover:bg-mint-600 hover:text-white">
                                              Book Again
                                          </a>
                                      </div>
                                  @endforeach
                              @else
                                  <p class="text-sm text-neutral-500 text-center py-6">No favourite shop available </p>
                              @endif
                          </div>
                      </div>
                  </div>


                  <div class="relative group">
                      <!-- Cart Button -->
                      <button id="cartBtn"
                          class="w-12 h-12 rounded-full flex justify-center items-center border border-mint-100
               transition-all duration-150 hover:bg-mint-600 group relative">
                          <img src="../assets/icons/basket-green.svg"
                              class="h-[18px] w-[18px] group-hover:filter group-hover:invert group-hover:brightness-0">

                          <span id="cartCount"
                              class="absolute -top-1 -right-2 bg-mint-600 text-xs text-white w-5 h-5 rounded-full
                   flex justify-center items-center hidden">
                              0
                          </span>
                      </button>

                      <!-- Dropdown -->
                      <div id="cartDropdown"
                          class="absolute right-0 mt-3 w-[320px] bg-white rounded-2xl shadow-xl border border-neutral-200 opacity-0 invisible scale-95
                            group-hover:opacity-100 group-hover:visible group-hover:scale-100
                            transition-all duration-200 z-50">
                          <div id="cartItems" class="p-4 max-h-[300px] overflow-y-auto"></div>

                          <div class="p-4 border-t" id="goBtn">
                              <form id="goToCartForm" method="GET">
                                  <button type="submit"
                                      class="block w-full text-center h-10 rounded-xl text-sm font-medium border border-neutral-200 text-neutral-600 transition-all hover:bg-mint-600 hover:text-white">
                                      <span id="cartBtn">Go to Cart</span>
                                  </button>
                              </form>
                          </div>
                      </div>
                  </div>

                  <a href="{{ route('my-dashboard') }}"
                      class="w-12 h-12 rounded-full flex justify-center items-center border border-mint-100 transition-all duration-150 hover:bg-mint-600 group">
                      <img src="../assets/icons/user-green.svg" alt=""
                          class="h-[18px] w-[18px] group-hover:filter group-hover:invert group-hover:brightness-0">
                  </a>
              </div>
          @else
              <div class="hidden lg:flex justify-center items-center gap-4">
                  <a class="btn_solid cursor-pointer" href="{{ route('sign-in') }}">
                      <p>Sign In</p>

                      <img src="../assets/icons/arrow-left.svg" alt="">
                  </a>

                  <a class="btn_outline cursor-pointer" href="{{ route('register') }}">
                      <p>Sign Up</p>
                  </a>
              </div>
          @endif

          <!-- sidebar opening button  -->
          <button
              class="h-10 w-10 border border-primary2-600 rounded p-1 flex lg:hidden flex-col justify-around items-center  "
              onclick="toggleSidebar()">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="size-7 text-primary2-600">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                      class="text-primary2-600" />
              </svg>

          </button>
      </section>
  </nav>


  <script>
    document.querySelector('.getLocationBtn').addEventListener('click', function(e) {
    e.preventDefault(); // prevent default link behavior

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Redirect to nearest-stores with coordinates
                window.location.href = `/nearest-stores?latitude=${lat}&longitude=${lng}`;
            },
            function(error) {
                if (error.code === error.PERMISSION_DENIED) {
                    const lastDenied = localStorage.getItem('locationDeniedTime');
                    const now = Date.now();

                    // Check if 1 hour has passed (3600000 ms)
                    if (!lastDenied || now - lastDenied > 3600000) {
                        alert("Location access denied. Showing default nearest stores.");
                        // Store the current time
                        localStorage.setItem('locationDeniedTime', now);
                    }
                    window.location.href = `/nearest-stores`;
                }
            }
        );
    } else {
        alert("Geolocation not supported. Showing default nearest stores.");
        window.location.href = `/nearest-stores`;
    }
});

window.updateHeaderFavourite = function(isFavourite) {
    const countElement = document.getElementById('fav-count');
    let currentCount = parseInt(countElement.innerText) || 0;

    if (isFavourite) {
        currentCount += 1;
    } else {
        currentCount -= 1;
        if (currentCount < 0) currentCount = 0;
    }

    countElement.innerText = currentCount;
};


  </script>
