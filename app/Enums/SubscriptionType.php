<?php

namespace App\Enums;

enum SubscriptionType: string
{
    case WEEKLY = 'Weekly';
    case MONTHLY = 'Monthly';
    case YEARLY = 'Yearly';
    case HALF_YEARLY = 'Half Yearly';
    case QUARTERLY = 'Quarterly';
}
