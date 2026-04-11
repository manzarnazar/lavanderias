<?php

namespace App\Enums;
use App\Enums\Attributes\EnumAttributes;

enum WebSettings: string
{
    case Header = 'header';
    case PremiumServices = 'premium_services';
    case ExperienceServices = 'experience_services';
    case HowItWorks = 'how_it_works';
    case BuildOnTrust = 'build_on_trust';
    case OurPromise = 'our_promise';
    case JoinOurNetwork = 'join_our_network';
    case TakeWithYou = 'take_with_you';
    case GetStarted = 'get_started';
    case Footer = 'footer';

}
