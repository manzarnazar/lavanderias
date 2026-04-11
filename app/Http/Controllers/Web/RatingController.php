<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Models\Rating;
use App\Repositories\OrderRepository;
use App\Repositories\RatingRepository;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(RatingRequest $request)
    {

        $order = (new OrderRepository())->findById($request->order_id);

        if ($order->order_status->value == 'Delivered') {
            $rating = (new RatingRepository())->storeByRequest($request, $order);

        }

        return redirect()->back()->with('success', 'Your review has been submitted!');
    }
}
