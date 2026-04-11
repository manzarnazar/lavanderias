<?php

namespace Database\Seeders;

use App\Enums\WebSettings;
use App\Models\WebSetting;
use Illuminate\Database\Seeder;


class WebSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (WebSettings::cases() as $setting) {
            if($setting->value === 'header'){
                WebSetting::create([
                    'key' => $setting->value,
                    'value' => json_encode([
                        'title' =>'Professional Laundry <br> And <span class="text-mint-600 font-playfair italic">Dry cleaning at your doorstep.</span>' ,
                        'description' => 'Experience hassle-free laundry service with free pickup and delivery. Book in seconds, track in real-time, and enjoy fresh, clean clothes.',
                        'header_img' =>'assets/images/herosection/heroimg.png',
                        'trusted_client_image_group' => [
                            [
                                'img' =>'assets/icons/user_author.png',
                            ],
                            [
                                'img' =>'assets/icons/user_author_1.png',
                            ],
                            [
                                'img' =>'assets/icons/user_author_2.png',
                            ],

                        ],
                    ])
                ]);
            }
            elseif($setting->value === 'premium_services'){
                WebSetting::create([
                    'key' => $setting->value,
                    'value' => json_encode([
                        'title' => 'Our Premium <span>Services</span>',
                        'sub_title' => 'Professional care for all your laundry needs with competitive pricing',
                    ])
                ]);
            }
            elseif($setting->value === 'experience_services'){
                WebSetting::create([
                    'key' => $setting->value,
                    'value' => json_encode([
                        'title' => ' Ready to <span class="text-mint-600 font-playfair italic">Experience Premium Laundry </span> Services?',
                        'sub_title' => 'Join thousands of satisfied customers who trust us with their clothes every day.',
                    ])
                ]);
            }
            elseif($setting->value === 'how_it_works'){
                WebSetting::create([
                    'key' => $setting->value,
                    'value' => json_encode([
                        'right_side_img' => 'assets/images/how-it-works/bg.png',
                        'title' => ' Laundry Done in 4 Simple Steps <br class="hidden lg:block"> It’s Fast, Easy & Convenient',
                        'work_steps' => [
                            [
                                'number' => '01',
                                'title' => 'Schedule Pickup',
                                'sub_title' => 'Choose your preferred time slot and location for doorstep pickup',
                            ],
                            [
                                'number' => '02',
                                'title' => 'We Collect',
                                'sub_title' => 'Our professional team arrives at your doorstep to collect your items',
                            ],
                            [
                                'number' => '03',
                                'title' => 'Clean And Care',
                                'sub_title' => 'Expert cleaning using premium products and advanced techniques',
                            ],
                            [
                                'number' => '04',
                                'title' => 'Fast Delivery',
                                'sub_title' => 'Get your fresh, clean clothes delivered back in 24-48 hours',
                            ],
                        ],
                    ])
                ]);
            }
            elseif($setting->value === 'build_on_trust'){
                WebSetting::create([
                    'key' => $setting->value,
                    'value' => json_encode([
                        'title' => 'Build on Trust and <span>Excellence</span>',
                        'sub_title' => 'Our commitment to quality and customer satisfaction has made us the preferred choice.',
                        'sample' => [
                            [
                                'icon' =>'assets/icons/varified.svg',
                                'title' => 'Verified Stores Only',
                                'description' => 'All stores are background-checked and rated by real customers.',
                            ],
                            [
                                'icon' =>'assets/icons/sparkles.svg',
                                'title' => 'Compare & Choose',
                                'description' => 'Compare stores, prices, and services to find your perfect match.',
                            ],
                            [
                                'icon' =>'assets/icons/tags.svg',
                                'title' => 'Best Price Guarantee',
                                'description' => 'Store competition ensures the best pricing and quality.',
                            ],
                            [
                                'icon' =>'assets/icons/review.svg',
                                'title' => 'Transparent Reviews',
                                'description' => 'Read verified reviews to choose with confidence.',
                            ]
                        ],
                    ])
                ]);
            }
            elseif($setting->value === 'our_promise'){
                WebSetting::create([
                    'key' => $setting->value,
                    'value' => json_encode([
                        'title' => 'Your Laundry, Our Promise <br class="hidden md:block">of <span
                        class="text-mint-600 font-playfair italic">Perfection</span>',
                        'sub_title' => 'We’re dedicated to quality, hygiene, And On-time delivery — always.',
                        'side_image' => 'assets/images/perfection/bg2.png',
                        'background_image' => 'assets/images/common/bg.png'
                    ])
                ]);
            }
            elseif($setting->value === 'join_our_network'){
                WebSetting::create([
                    'key' => $setting->value,
                    'value' => json_encode([
                        'title' => 'Join <span>Our Stores</span> Network',
                        'description' => 'Partner with Laundry and connect with customers who need your services. No setup fees, flexible pricing, and instant access to our growing customer base.',
                        'lists' => [
                            [
                                'list' => 'Commission-based pricing (no hidden fees)',
                            ],
                            [
                                'list' => 'Get paid weekly via direct deposit',
                            ],
                            [
                                'list' => 'Free marketing and customer acquisition',
                            ]
                        ],
                        'facilities' => [
                            [
                                'icon' =>'assets/icons/grow.svg',
                                'title' => 'Grow Your Business',
                                'description' => 'Reach thousands of customers actively searching for laundry services in your area.',
                            ],
                            [
                                'icon' =>'assets/icons/mobile.svg',
                                'title' => 'Mobile-Friendly Tools',
                                'description' => 'Accept orders, update status, and communicate with customers on-the-go.',
                            ],
                            [
                                'icon' =>'assets/icons/people.svg',
                                'title' => 'Manage Orders Easily',
                                'description' => 'Simple dashboard to track orders, manage pricing, and handle customer requests.',
                            ],
                            [
                                'icon' =>'assets/icons/headset.svg',
                                'title' => 'Dedicated Support',
                                'description' => '24/7 store support team to help you succeed and resolve any issues quickly.',
                            ]
                        ],

                    ])
                ]);
            }
            elseif($setting->value === 'take_with_you'){
                WebSetting::create([
                    'key' => $setting->value,
                    'value' => json_encode([
                        'title' => 'Take <span>Laundry</span> With You',
                        'sub_title' => 'Download our mobile app for exclusive features and a seamless laundry experience on the go.',
                        'take_info' => [
                            [
                                'icon' => 'assets/logo/footer-logo.svg',
                                'title' => 'Your Laundry, Just a Tap Away',
                                'sub_title' => 'Everything you need in your pocket',
                            ],
                        ],
                        'infos' => [
                            [
                                'icon' => 'assets/icons/easy-booking.svg',
                                'title' => 'Easy Booking',
                                'sub_title' => 'Schedule pickup and delivery with just a few taps',
                            ],
                            [
                                'icon' => 'assets/icons/clock.svg',
                                'title' => 'Real-Time Tracking',
                                'sub_title' => 'Track your order status from pickup to delivery',
                            ],
                            [
                                'icon' => 'assets/icons/trophy.svg',
                                'title' => 'Loyalty Rewards',
                                'sub_title' => 'Earn points and get exclusive discounts',
                            ],
                            [
                                'icon' => 'assets/icons/shield-check-green.svg',
                                'title' => 'Secure Payments',
                                'sub_title' => 'Safe and convenient payment options',
                            ]
                        ],
                        'footer_title' => 'Get Mobile App on <br> App Store & Google Play',
                        'image_group' => [
                            [
                                'img' => 'assets/images/stores-network/user-1.png',
                            ],
                            [
                                'img' => 'assets/images/stores-network/user-1.png',
                            ],
                            [
                                'img' => 'assets/images/stores-network/user-1.png',
                            ],
                        ],
                        'button_group' => [
                            [
                                'name' => 'Download for iOS',
                                'link' => '#'
                            ],
                            [
                                'name' => 'Download for Android',
                                'link' => '#'
                            ]
                        ],
                        'right_side_image' => 'assets/images/stores-network/mobile.png'

                    ])
                ]);
            }
            elseif($setting->value === 'get_started'){
                WebSetting::create([
                    'key' => $setting->value,
                    'value' => json_encode([
                        'title' => 'Ready to Get Started?',
                        'sub_title' => 'Book your first order and experience hassle-free laundry service',
                    ])
                ]);
            }
            elseif($setting->value === 'footer'){
                WebSetting::create([
                    'key' => $setting->value,
                    'value' => json_encode([
                        'footer_logo' => 'assets/logo/Logo.png',
                        'footer_background' => 'assets/images/footer/laundry.svg',
                        'footer_title' => 'Elevate Your Business with Innovative<br />Web, App, and Software Solutions. Partner for Excellence in Tech',
                        'contact_us' => [
                                'phone_number' => '+8801937203743',
                                'address' =>'123 LaundryStreet,Clean City, CC 12345',
                        ],
                        'follow_us' => [
                            [
                                'icon' => 'assets/icons/facebook.svg',
                                'link' =>'#',
                            ],
                            [
                                'icon' => 'assets/icons/twitter.svg',
                                'link' =>'#',
                            ],
                            [
                                'icon' => 'assets/icons/youtube.svg',
                                'link' =>'#',
                            ],
                            [
                                'icon' => 'assets/icons/pinterest.svg',
                                'link' =>'#',
                            ],
                        ],

                        'footer_left_side_text' => 'Professional laundry services you can trust.',
                        'footer_right_side_text' => 'ⓒ2025 Laundry. All Rights Reserved.',
                    ])
                ]);
            }

        }

    }
}
