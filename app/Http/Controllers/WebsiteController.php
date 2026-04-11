<?php

namespace App\Http\Controllers;

use App\Events\OrderMailEvent;
use App\Events\UserMailEvent;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminDeviceKey;
use App\Models\AppSetting;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\Order;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Store;
use App\Models\User;
use App\Repositories\AddressRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\DeviceKeyRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentGatewayRepository;
use App\Repositories\RatingRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\SettingRepository;
use App\Repositories\StoreRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Repositories\VerificationCodeRepository;
use App\Repositories\WebSettingRepository;
use App\Services\NotificationServices;
use App\Services\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class WebsiteController extends Controller
{
    public function index()
    {
        $customers = (new CustomerRepository())->getAll();
        $ratings = (new RatingRepository())->getAll();
        $shops = (new StoreRepository())->getAll();

        $services = (new ServiceRepository())->getAllAdditional();

        $userLat = request('lat');
        $userLng = request('lng');

        $topStores = (new OrderRepository())->getTopStores($userLat, $userLng);

        $webSettings = (new WebSettingRepository())->getAll();
        foreach ($webSettings as $setting) {
            $decoded = json_decode($setting->value);
            $setting->decoded_value = $decoded;
            $setting->key = $setting->key ?? null;
        }

        return view('website.index', compact('customers', 'ratings', 'shops', 'services', 'topStores', 'webSettings'));
    }
    public function services()
    {
        $serviceData = (new ServiceRepository())->getActiveServices();
        return view('website.all-services', compact('serviceData'));
    }
    public function storeService($storeSlug)
    {
        $serviceData = (new ServiceRepository())->getByStore($storeSlug);
        $store = (new StoreRepository())->model()->where('slug', $storeSlug)->first();

        return view('website.services', compact('serviceData', 'store'));
    }
    public function searchServices(Request $request)
    {
        $query = $request->q;

        $services = (new ServiceRepository())->model()->where('is_active', 1)
            ->where('slug', 'LIKE', "%$query%")
            ->limit(20)
            ->get();
        return response()->json($services);
    }
    public function searchStoreServices($store, Request $request)
    {
        $query = $request->q;
        $services = (new ServiceRepository())->model()->where('is_active', 1)
            ->whereHas('stores', function ($q) use ($store) {
                $q->where('stores.slug', $store);
            })
            ->where('slug', 'LIKE', "%$query%")
            ->limit(20)
            ->get();

        return response()->json($services);
    }
    public function variantStoreServices($serviceSlug, Request $request)
    {

        if ($request->selectedStoreSlug == null) {
            $deliveryCharge = (new StoreRepository())->model()->where('slug', request('store_slug'))->first();
        } else {
            $deliveryCharge = (new StoreRepository())->model()->where('slug', $request->selectedStoreSlug)->first();
        }
        $storeSlug = $request->selectedStoreSlug ?? $request->query('store_slug');
        $store = Store::where('slug', $storeSlug)->first();

        $serviceData = (new ServiceRepository())->getProductBySlug($serviceSlug);


        return view('website.variant-services', compact('serviceData', 'deliveryCharge', 'store'));
    }
    public function nearestStore(Request $request, $serviceSlug)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $nearestStores = (new StoreRepository())->getNearestStoresByService($serviceSlug, $request);

        return view('website.nearest-stores', compact('nearestStores'));
    }


    public function stores(Request $request)
    {
        $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');

        if ($latitude && $longitude) {
            $nearestStores = (new StoreRepository())->getNearestStores($request);
        } else {
            $nearestStores = [];
        }

        return view('website.nearest-stores', compact('nearestStores'));
    }


    public function signIn()
    {

        $customers = (new CustomerRepository())->getAll();
        $ratings = (new RatingRepository())->getAll();

        return view('website.sign-in', compact('customers', 'ratings'));
    }
    public function register()
    {
        $customers = (new CustomerRepository())->getAll();
        $ratings = (new RatingRepository())->getAll();
        return view('website.register', compact('customers', 'ratings'));
    }
    public function forgotPassword()
    {
        $customers = (new CustomerRepository())->getAll();
        $ratings = (new RatingRepository())->getAll();
        return view('website.forgot-password', compact('customers', 'ratings'));
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'This email is not registered with us.'
        ]);

        $otp = rand(100000, 999999);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp,
                'created_at' => now()
            ]
        );

        // Send email
        Mail::raw("Your OTP code is: $otp", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Password Reset OTP');
        });



        session([
            'otp' => $otp,
            'email' => $request->email
        ]);

        return redirect()->back()->with('success', 'OTP sent');
    }

    // public function confirmOtp(Request $request)
    // {
    //     $request->validate([
    //         'otp1' => 'required|digits:1',
    //         'otp2' => 'required|digits:1',
    //         'otp3' => 'required|digits:1',
    //         'otp4' => 'required|digits:1',
    //         'otp5' => 'required|digits:1',
    //         'otp6' => 'required|digits:1',
    //     ]);
    //     $otp = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4 . $request->otp5 . $request->otp6;
    //     $email = session('email');

    //     // Check OTP in database
    //     $otpRecord = DB::table('password_resets')
    //         ->where('email', $email)
    //         ->where('token', $otp)
    //         ->where('created_at', '>=', now()->subMinutes(5))
    //         ->first();

    //     if (!$otpRecord) {
    //         return back()->withErrors(['otp' => 'Invalid or expired OTP']);
    //     }

    //     session(['otp_verified' => true]);
    //     session()->forget('otp');


    //     return redirect()->back()->with('success', 'Confirm OTP');
    // }

    public function confirmOtp(Request $request)
    {
        $request->validate([
            'otp1' => 'required|digits:1',
            'otp2' => 'required|digits:1',
            'otp3' => 'required|digits:1',
            'otp4' => 'required|digits:1',
            'otp5' => 'required|digits:1',
            'otp6' => 'required|digits:1',
        ]);

        $otp = $request->otp1 . $request->otp2 . $request->otp3 . $request->otp4 . $request->otp5 . $request->otp6;
        $email = session('email');

        $otpRecord = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $otp)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        if (!$otpRecord) {
            // Return JSON error for AJAX
            return response()->json([
                'error' => 'Invalid or expired OTP'
            ], 422); // 422 = validation error
        }

        session(['otp_verified' => true]);
        session()->forget('otp');

        // Return JSON success
        return response()->json([
            'success' => true,
            'message' => 'OTP confirmed'
        ]);
    }


    public function setPassword(Request $request)
    {

        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!session('otp_verified')) {
            return redirect()->route('forgot-password')->withErrors(['otp' => 'OTP not verified!']);
        }

        $email = session('email');

        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return redirect()->route('forgot-password')->withErrors(['email' => 'User not found']);
        }

        DB::table('users')
            ->where('email', $email)
            ->update([
                'password' => Hash::make($request->new_password)
            ]);


        session()->forget(['otp', 'otp_verified', 'email']);

        return redirect()->route('sign-in')->with('success', 'Password updated successfully. You can now login.');
    }

    public function registerVendor()
    {
        $customers = (new CustomerRepository())->getAll();
        $ratings = (new RatingRepository())->getAll();
        return view('website.register-vendor', compact('customers', 'ratings'));
    }

    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider)
    {
        $socialUser = Socialite::driver($provider)->user();
        $user = User::updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ],
            [
                'first_name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'mobile_verified_at' => now(),
            ],
        );

        if (!$user->hasRole('customer')) {
            $user->assignRole('customer');
        }

        if (!$user->customer) {
            (new CustomerRepository())->storeByUser($user);
        }

        auth()->login($user);

        return redirect()->back()->with('success', 'Login Successful');
    }

    public function registerUser(Request $request)
    {

        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'nullable|string|max:255',
            'password'       => 'required|min:6',
            'profile_photo'  => 'nullable|image|mimes:jpg,jpeg,png',
            'email'          => 'required|email:rfc,dns|unique:users,email',
            'mobile'         => 'nullable|string|unique:users,mobile',
            'business_name'  => 'required_if:customer,0|string|max:255',
            'card-1-container'          => 'accepted',
        ]);

        $contact = $request->email ?? $request->mobile;

        $user = (new UserRepository())->registerUser($request);

        if ($request->boolean('customer')) {
            (new CustomerRepository())->storeByUser($user);
            $user->assignRole('customer');
        } else {
            (new StoreRepository())->storeByWeb($request, $user);
        }

        $verificationCode = (new VerificationCodeRepository())
            ->findOrCreateByContact($contact);

        $user->update([
            'mobile_verified_at' => now(),
        ]);

        if ($request->device_key) {
            (new DeviceKeyRepository())->storeByRequest(
                $request->boolean('customer') ? $user->customer : $user->vendor,
                $request
            );
        }

        if ($request->mobile && config('app.sms_two_step_verification')) {
            $message = 'Welcome to ' . config('app.name') . "\nYour OTP is " . $verificationCode->otp;
            SMS::sendSms($request->mobile, $message);
        } elseif (config('app.mail_two_step_verification')) {
            UserMailEvent::dispatch($user, $verificationCode->otp);
        }

        Auth::login($user);

        if ($request->input('vendor') == '1') {

            $appSetting = AppSetting::first();
            $businessSystem = $appSetting?->business_based_on;

            if ($businessSystem === 'commission') {

                return redirect('/root')->with('success', 'Vendor Registration Successful');
            } elseif ($businessSystem === 'subscription') {

                return redirect('/subscription-purchase')->with('success', 'Vendor Registration Successful');
            }
        }

        return redirect()->back()->with('success', 'Customer Registration Successful');
    }

    public function logout()
    {
        $user = auth()->user();
        if ($user) {
            auth()->logout();
            return redirect()->route('home')->with('success', 'User logout successfully');
        }
        return redirect()->back()->with('success', 'User not found');
    }
    public function updateUser(Request $request)
    {
        $request->validate([
            'email'          => 'required|email',
            'mobile' => 'nullable|string|unique:users,mobile,' . auth()->id(),

        ]);
        (new UserRepository())->updateByUser($request, auth()->user());
        return redirect()->back()->with('success', 'User updated Successful');
    }
    public function signInUser(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|min:6',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        $user = Auth::user();

        if ($request->login_as === 'customer' && ! $user->hasRole('customer')) {

            return back()->withErrors([
                'login' => 'This account is not Valid.',
            ]);
        }

        if ($user->customer && $request->device_key) {
            (new DeviceKeyRepository())->storeByRequest($user->customer, $request);
        }

        return redirect()->back()->with('success', 'Login Successful');
    }

    public function checkout($storeSlug)
    {
        $store = (new StoreRepository())->model()->where('slug', $storeSlug)->first();
        $addresses = (new AddressRepository())->getAll();
        $storeId = $store->id;

        $repository = new PaymentGatewayRepository();

        $gateways = $repository->query()
            ->where('is_active', 1)
            ->whereHas('store_payment_gateways', function ($query) use ($storeId) {
                $query->where('store_id', $storeId)->where('is_active', 1);
            })
            ->get();

        return view('website.checkout', compact('store', 'addresses', 'gateways'));
    }




    public function validateCoupon(Request $request)
    {

        $request->validate([
            'promo_code' => 'required|string',
        ]);
        $store = (new StoreRepository())->model()->where('slug', $request->store_slug)->first();

        $coupon = Coupon::where('code', strtoupper($request->promo_code))
            ->where('started_at', '<=', now())
            ->where('expired_at', '>=', now())
            ->where('store_id', $store->id)
            ->first();
        if ($coupon) {
            return $this->json('Coupon applied successfully!', [
                'status' => 'success',
                'discount' => $coupon->discount,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid or expired promo code.',
        ], 400);
    }
    public function deliveryAddress(AddressRequest $request)
    {

        $addresses = auth()->user()->customer->addresses;
        if (! $addresses->isEmpty() && $addresses->count() >= 3) {
            return $this->json('sorry, you can\'t add more address');
        }
        (new AddressRepository())->storeByRequest($request);

        return redirect()->back()->with('success', 'Address is added successfully');
    }
    public function deliveryAddressUpdate(AddressRequest $request, $address)
    {
        $address = (new AddressRepository())->find($address);

        (new AddressRepository())->updateByRequest($address, $request);


        return redirect()->back()->with('success', 'Address is updated successfully');
    }
    public function pickSchedule(Request $request)
    {
        $date = $request->date;
        $store = (new StoreRepository())->model()->where('slug', $request->store_slug)->first();
        $hours = $this->getAvailableTimes($store, $date, 'pickup');

        if ($hours->isEmpty()) {
            return response()->json([
                'message' => 'Sorry, our service is not available',
                'slots' => []
            ]);
        }

        return response()->json([
            'slots' => $hours->values()
        ]);
    }
    public function deliverySchedule(Request $request)
    {
        $date = $request->date;
        $store = (new StoreRepository())->model()->where('slug', $request->store_slug)->first();

        $hours = $this->getAvailableTimes($store, $date, 'delivery');

        if ($hours->isEmpty()) {
            return response()->json([
                'message' => 'Sorry, our service is not available',
                'slots' => []
            ]);
        }

        return response()->json([
            'slots' => $hours->values()
        ]);
    }
    public function order(Request $request)
    {
        $appSetting = AppSetting::first();
        $maxCompletionTime = $appSetting->pick_to_delivery_gap;
        $pickDate = $request->input('pick_date');
        $deliveryDate = $request->input('delivery_date');
        $pickDate = Carbon::parse($pickDate);
        $deliveryDate = Carbon::parse($deliveryDate);
        $actualDistance = $pickDate->diffInDays($deliveryDate);
        $expectedDistance = $maxCompletionTime;

        if ($actualDistance <= $expectedDistance) {
            $newDeliveryDate = $pickDate->addDays($expectedDistance);
            $newDeliveryDate->addDay();

            $deliveryDate = $newDeliveryDate->toDateString();
            return response()->json([
                'message' => 'You should select the delivery date',
                'adjusted_delivery_date' => $deliveryDate,

            ], 200);
        }

        $store = (new StoreRepository())->model()->where('slug', $request->store_slug)->first();
        $availablePickTime = $this->checkPickTime($request->pick_date, $request->pick_hour, $store->id);
        $availableDeliveryTime = $this->checkDeliveryTime($request->delivery_date, $request->delivery_hour, $store->id);

        if ($availablePickTime && $availableDeliveryTime) {

            $order = (new OrderRepository())->storeByRequest($request, $availablePickTime['free_times'][0], $availableDeliveryTime['free_times'][0]);
            $transaction = (new TransactionRepository())->storeForOrder($order);

            if ($request->has('additional_service_id')) {
                $order->additionals()->sync($request->additional_service_id);
            }

            $quantity = $order->products->sum('pivot.quantity');
            $store = $order->store;
            $filePath = 'pdf/order' . $order->id . $order->prefix . $order->order_code . rand(10000, 99999) . '.pdf';
            $invoiceName = $store->user?->invoice?->invoice_name ?? 'invoice1';
            $appSetting = AppSetting::first();
            $pdf = PDF::loadView('pdf.' . $invoiceName, compact('order', 'quantity', 'store', 'appSetting'));

            Storage::put($filePath, $pdf->output());

            $order->update([
                'invoice_path' => $filePath,
            ]);

            $deviceKeys = AdminDeviceKey::all();

            $message = "Hello,\r" . 'New order added from ' . $order->customer->name . ".\r" . "Total amount :   $order->total_amount \r" . 'Pick Date: ' . Carbon::parse($order->pick_date)->format('d F Y') . ' - ' . $order->getTime($order->pick_hour) . "\r" . 'Delivery Date: ' . Carbon::parse($order->delivery_date)->format('d F Y') . ' - ' . $order->getTime($order->delivery_hour);

            $keys = $deviceKeys->pluck('key')->toArray();
            $title = 'New Order Added';

            (new NotificationServices())->sendNotification($message, $keys, $title);

            (new NotificationRepository())->storeByRequest($order->customer->id, $message, $title);

            OrderMailEvent::dispatch($order);


            if ($order->payment_type != 'cash') {
                $paymentUrl = route('pos.payment.api', ['order' => $order->id, 'gateway' => $order->payment_type, 'system' => 'website']);
                return $this->json('success', [
                    'message' => 'Order is added successfully',
                    'payment_url' => $paymentUrl,
                    'payment_type' => $order->payment_type,
                    'order' => $order,
                ]);
            } else {
                return $this->json('success', [
                    'order' => $order,
                ]);
            }
        }
    }
    private function checkPickTime($date, $slot, $storeId)
    {
        $store = (new StoreRepository())->find($storeId);
        $freeTimes = $this->checkAvailableTimes($date, $store, $slot, 'pickup');
        return [
            'available' => count($freeTimes) > 0,
            'free_times' => $freeTimes
        ];
    }
    private function checkDeliveryTime($date, $slot, $storeId)
    {
        $store = (new StoreRepository())->find($storeId);
        $freeTimes = $this->checkAvailableTimes($date, $store, $slot, 'delivery');

        return [
            'available' => count($freeTimes) > 0,
            'free_times' => $freeTimes
        ];
    }
    public function orderDetails()
    {
        $customer = auth()->user()->customer;
        $order = $customer->orders()->latest()->first();
        $review = $order->rating()
            ->where('customer_id', $customer->id)
            ->first();
        return view('website.order-details', compact('order', 'review'));
    }
    public function orderInvoice($orderID)
    {
        $order = Order::withoutGlobalScopes()->where('id', $orderID)->first();

        $quantity = $order->products->sum('pivot.quantity');

        $invoice = auth()->user()->invoice;

        $store = $order->store;
        $appSetting = AppSetting::first();

        $appSetting = AppSetting::first();
        if ($invoice?->type == 'pos') {
            return view('pdf.posIvoice', compact('quantity', 'order', 'store', 'appSetting'));
        }

        $invoiceName = $invoice?->invoice_name ?? 'invoice1';

        $pdf = PDF::loadView('pdf.' . $invoiceName, compact('order', 'quantity', 'store', 'appSetting'))
            ->setPaper('a4', 'portrait');

        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('isPhpEnabled', true);

        $pdf->getDomPDF()->getOptions()->set('font-family', 'Noto Sans Bengali');

        return $pdf->stream($order->prefix . $order->order_code . ' - invioce.pdf');
    }
    public function orderDetail($orderSlug)
    {
        $customer = auth()->user()->customer;
        $order = $customer->orders()->where('slug', $orderSlug)->firstOrfail();
        $review = $order->rating()
            ->where('customer_id', $customer->id)
            ->first();


        return view('website.order-details', compact('order', 'review'));
    }
    public function myDashboard()
    {
        $customer = auth()->user();
        return view('website.my-dashboard', compact('customer'));
    }

    public function myOrders()
    {

        $customer = auth()->user()->customer;
        $orders = $customer->orders()->get();
        $activeOrders = $customer->orders()->where('order_status', 'Processing')->get();
        $completedOrders = $customer->orders()->where('order_status', 'Delivered')->get();
        $cancelledOrders = $customer->orders()->where('order_status', 'Cancelled')->get();
        if (request('order_no')) {
            $filterOrders = $customer->orders()->where('order_code', 'LIKE', '%' . request('order_no') . '%')->get();
        } else {
            $filterOrders = (new OrderRepository())->getBySearch(request('status'))->sortByDesc('created_at')->values();
        }

        return view('website.my-orders', compact('orders', 'activeOrders', 'completedOrders', 'cancelledOrders', 'filterOrders'));
    }

    public function manageAddresses()
    {
        $customer = auth()->user()->customer;
        $addresses = $customer->addresses;
        return view('website.manage-addresses', compact('addresses'));
    }
    public function defaultAddress($addressId)
    {
        $customer = auth()->user()->customer;
        $addresses = $customer->addresses;
        $address = $addresses->where('id', $addressId)->first();
        $address->update(['is_default' => true]);
        foreach ($addresses as $addr) {
            if ($addr->id != $addressId) {
                $addr->update(['is_default' => false]);
            }
        }
        return redirect()->route('manage-addresses')->with('success', 'Address updated successfully');
    }
    public function deleteAddress($addressId)
    {
        $customer = auth()->user()->customer;
        $addresses = $customer->addresses;
        $address = $addresses->where('id', $addressId)->first();
        $address->delete();
        return redirect()->route('manage-addresses')->with('success', 'Address deleted successfully');
    }

    public function favouriteStore($storeSlug)
    {
        $customer = auth()->user()->customer;
        $store = (new StoreRepository())->model()->where('slug', $storeSlug)->first();

        if ($customer->favouriteStore()->where('store_id', $store->id)->exists()) {
            $customer->favouriteStore()->detach($store->id);
            $isFavourite = false;
            $message = 'Store removed from favourites.';
        } else {
            $customer->favouriteStore()->attach($store->id);
            $isFavourite = true;
            $message = 'Store added to favourites successfully.';
        }

        return response()->json([
            'success' => true,
            'is_favourite' => $isFavourite,
            'total_favourites' => $customer->favouriteStore()->count(),
            'message' => $message,
        ]);
    }


    public function myFavourites()
    {
        $user = auth()->user()->customer;
        $customer = auth()->user();
        $favouriteStores = $user->favouriteStore;

        return view('website.my-favourite', compact('favouriteStores', 'customer'));
    }

    public function mySettings()
    {
        $customer = auth()->user();
        return view('website.my-settings', compact('customer'));
    }

    public function promotionNotify($notify)
    {
        auth()->user()->update([
            'promotion_notify' => $notify
        ]);

        return response()->json(['success' => true]);
    }
    public function updateNotify($notify)
    {
        auth()->user()->update([
            'order_update_notify' => $notify
        ]);

        return response()->json(['success' => true]);
    }

    public function faq(Request $request)
    {
        $slug = $request->query('slug');

        $faqData = Faq::all();

        if ($slug == 'all') {
            $faqs = Faq::all();
        } elseif ($slug) {
            $faqs = Faq::where('slug', $slug)->get();
        } else {
            $faqs = Faq::all();
        }

        return view('website.faq', compact('faqData', 'faqs', 'slug'));
    }


    public function contact()
    {
        $setting = (new SettingRepository())->findBySlug('contact-us');
        return view('website.contact', compact('setting'));
    }
    public function contactUs(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject'   => 'required|string|max:20',
            'phone_number'   => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        Contact::create($data);
        return redirect()->back()->with('success', 'Successfully send your message');
    }
    public function allServices()
    {
        return view('website.contact');
    }

    public function getAvailableTimes($store, $date, $type)
    {
        $day = Carbon::parse($date)->format('l');
        $schedule = $store->schedules()
            ->where('is_active', true)
            ->where('day', $day)
            ->where('type', $type)
            ->first();

        $today = ($type === 'pickup') ? date('Y-m-d') : now()->addDay()->format('Y-m-d');

        if (!$schedule || $date < $today) {
            return [];
        }

        $i = ($type === 'pickup' && $today == Carbon::parse($date)->format('Y-m-d')) ? date('H') + 1 : $schedule->start_time;

        $typeSystem = $type === 'pickup' ? 'picked' : 'delivery';
        $orders = (new OrderRepository())->getByDatePickOrDelivery($date, $typeSystem);
        $hours = collect([]);


        for ($i; $i < ($schedule->end_time - 1); $i += 2) {
            $per = 0;
            foreach ($orders as $order) {
                $hourDate = $type === 'pickup' ? $order->pick_hour : $order->delivery_hour;
                $hour = Carbon::parse($hourDate)->format('H');
                if ($i == $hour || $i + 1 == $hour) {
                    $per++;
                }
            }


            if ($per < $schedule->per_hour) {
                $hours[] = [
                    'hour' => (string) $i . '-' . (string) ($i + 1),
                    'title' => sprintf('%02s', $i) . ':00' . ' - ' . sprintf('%02s', $i + 1) . ':59',
                ];
            }
        }



        return $hours;
    }

    public function checkAvailableTimes($date, $store, $slot, $type)
    {
        $day = Carbon::parse($date)->format('l');

        $schedule = $store->schedules()
            ->where('is_active', true)
            ->where('day', $day)
            ->where('type', $type)
            ->first();

        if (!$schedule) {
            return [
                'available' => false,
                'free_times' => []
            ];
        }

        [$startHour, $endHour] = explode('-', $slot);
        $startHour = (int)$startHour;
        $endHour   = (int)$endHour;

        $slotDuration = ($endHour - $startHour + 1) * 60;

        $interval = $slotDuration / $schedule->per_hour;
        if ($type == 'pickup') {
            $orders = $store->orders()
                ->where('pick_date', Carbon::parse($date)->format('Y-m-d'))
                ->get();
        } else {
            $orders = $store->orders()
                ->where('delivery_date', Carbon::parse($date)->format('Y-m-d'))
                ->get();
        }


        $freeTimes = [];

        for ($m = 0; $m < $slotDuration; $m += $interval) {

            $hour = $startHour + floor($m / 60);
            $minute = $m % 60;

            $time = sprintf('%02d:%02d:00', $hour, $minute);

            $isTaken = false;
            $slotStart = Carbon::createFromTime($hour, $minute, 0);
            $slotEnd = $slotStart->copy()->addMinutes($interval - 1);


            foreach ($orders as $order) {
                $hour = $type == 'pickup' ? $order->pick_hour : $order->delivery_hour;
                if (Carbon::parse($hour)->between($slotStart, $slotEnd)) {
                    $isTaken = true;
                    break;
                }
            }

            if (!$isTaken) {
                $freeTimes[] = $time;
            }
        }

        return $freeTimes;
    }

    public function removeFavouriteStore($storeSlug)
    {
        $customer = auth()->user()->customer;
        $store = (new StoreRepository())->model()->where('slug', $storeSlug)->first();

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store not found.',
            ]);
        }

        // Remove only if it exists
        if ($customer->favouriteStore()->where('store_id', $store->id)->exists()) {
            $customer->favouriteStore()->detach($store->id);

            return response()->json([
                'success' => true,
                'message' => 'Store removed from favourites.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Store is not in favourites.',
        ]);
    }

   
}
