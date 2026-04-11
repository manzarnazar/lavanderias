 <div class="hidden lg:block col-span-12 lg:col-span-4 ">
     <div class="flex flex-col gap-6 items-center p-4 rounded-xl sticky top-24 bg-white">
         <div class="flex flex-col ">

             <div class="flex flex-col items-center justify-center gap-6">
                 <div class="rounded-full overflow-hidden w-32 h-32">
                     <img src="{{ $customer->profilePhotoPath ?? '../assets/images/dashboard/user.png' }}" alt=""
                         class="w-full h-full object-cover">
                 </div>

                 <div class="flex justify-center items-center flex-col">
                     <p class="text-lg font-semibold text-center text-neutral-700">
                         {{ $customer->first_name }} {{ $customer->last_name }}
                     </p>
                     <p class="text-base text-left text-neutral-500">{{ $customer->email }}</p>

                     <div
                         class="flex justify-center items-center  w-fit h-[30px] gap-1 p-2 rounded-[25px] bg-neutral-900 mt-[15px]">
                         <img src="../assets/icons/star-gold.svg" alt="" class="h-3 w-3">
                         <p class="text-xs text-left text-[#eab800]">Premium Member</p>

                     </div>
                 </div>

             </div>
         </div>
         <div class="space-y-[10px] w-full">
             <a href="{{ route('my-dashboard') }}"
                    class="cursor-pointer px-5 py-3.5 rounded-xl flex justify-start items-center gap-[15px]
                    {{ request()->routeIs('my-dashboard') ? 'bg-mint-600' : 'bg-neutral-50' }}
                    w-full group transition-all duration-150 hover:bg-mint-600">

                        <img src="../assets/icons/user.svg" alt=""
                            class="w-5 h-auto {{ request()->routeIs('my-dashboard') ? 'brightness-0 invert' : '' }}
                            group-hover:brightness-0 group-hover:invert">

                        <p class="flex-grow-0 flex-shrink-0 text-base font-medium text-center
                        {{ request()->routeIs('my-dashboard') ? 'text-white' : 'text-neutral-700' }}
                        group-hover:text-white transition-all duration-150">
                            Overview
                        </p>
                </a>
                

             <a href="{{ route('my-orders') }}"
                 class="{{ request()->routeIs('my-orders') ? 'bg-mint-600' : '' }} cursosr-pointer px-5 py-3.5 rounded-xl flex justify-start items-center gap-[15px] bg-neutral-50 w-full group transition-all duration-150 hover:bg-mint-600">
                 <img src="../assets/icons/calendar2.svg" alt=""
                     class="{{ request()->routeIs('my-orders') ? 'brightness-0 invert' : '' }} w-5 h-auto group-hover:brightness-0 group-hover:invert">
                 <p
                     class="{{ request()->routeIs('my-orders') ? 'text-white' : '' }} flex-grow-0 flex-shrink-0 text-base font-medium text-center text-neutral-700 group-hover:text-white transition-all duration-150">
                     My Orders</p>
             </a>
             <a href="{{ route('manage-addresses') }}"
                 class="px-5 py-3.5 rounded-xl flex justify-start items-center gap-[15px] {{ request()->routeIs('manage-addresses') ? 'bg-mint-600' : 'bg-neutral-50 ' }} w-full group transition-all duration-150 hover:bg-mint-600">

                 <img src="../assets/icons/map-pin-gray.svg" alt=""
                     class="w-5 h-auto {{ request()->routeIs('manage-addresses') ? 'brightness-0 invert' : '' }} group-hover:brightness-0 group-hover:invert">
                 <p
                     class="flex-grow-0 flex-shrink-0 text-base font-medium text-center group-hover:text-white {{ request()->routeIs('manage-addresses') ? 'text-white' : '' }} transition-all duration-150">
                     Addresses</p>
             </a>

             <a href="{{ route('my-favourite') }}"
                 class="{{ request()->routeIs('my-favourite') ? 'bg-mint-600' : '' }} cursosr-pointer px-5 py-3.5 rounded-xl flex justify-start items-center gap-[15px] bg-neutral-50 w-full group transition-all duration-150 hover:bg-mint-600">
                 <img src="../assets/icons/heart.svg" alt=""
                     class="{{ request()->routeIs('my-favourite') ? 'brightness-0 invert' : '' }} w-5 h-auto group-hover:brightness-0 group-hover:invert">
                 <p
                     class="{{ request()->routeIs('my-favourite') ? 'text-white' : '' }} flex-grow-0 flex-shrink-0 text-base font-medium text-center text-neutral-700 group-hover:text-white transition-all duration-150">
                     Favorites</p>
             </a>
             <a href="{{ route('my-settings') }}"
                 class="px-5 py-3.5 rounded-xl flex justify-start items-center gap-[15px]
   {{ request()->routeIs('my-settings') ? 'bg-mint-600' : 'bg-neutral-50' }}
   w-full group transition-all duration-150 hover:bg-mint-600">

                 <img src="../assets/icons/settings.svg" alt=""
                     class="w-5 h-auto {{ request()->routeIs('my-settings') ? 'brightness-0 invert' : '' }}
         group-hover:brightness-0 group-hover:invert">

                 <p
                     class="flex-grow-0 flex-shrink-0 text-base font-medium text-center
       {{ request()->routeIs('my-settings') ? 'text-white' : 'text-neutral-700' }}
       group-hover:text-white transition-all duration-150">
                     Settings
                 </p>
             </a>

             <form action="{{ route('logout-web') }}" method="post">
                 @csrf
                 <button
                     class="px-5 py-3.5 rounded-xl flex justify-start items-center gap-[15px] bg-neutral-50 w-full group transition-all duration-150 hover:bg-mint-600">

                     <img src="../assets/icons/sign-out.svg" alt=""
                         class="w-5 h-auto group-hover:brightness-0 group-hover:invert">
                     <p
                         class="flex-grow-0 flex-shrink-0 text-base font-medium text-center text-neutral-700 group-hover:text-white transition-all duration-150">
                         Sign Out</p>
                 </button>
             </form>
         </div>

     </div>
 </div>
