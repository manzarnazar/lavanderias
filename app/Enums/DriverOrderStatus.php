<?php

namespace App\Enums;

enum DriverOrderStatus: string
{
    case TO_PICKUP = 'To-Pickup';
    case PICKING_UP = 'Picking Up';
    case START_PICKING_UP = 'Start Picking Up';
    case PICKED_UP = 'Picked up';
    case DROPED_IN_STORE = 'Droped in Store';
    case TO_DELIVER = 'To Deliver';
    case START_DELIVERING = 'Start Delivering';
    case DELIVERED = 'Delivered';
    case DELIVERING = 'Delivering';
}
