<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PDF;

class OrderController extends Controller
{
    public function show()
    {
        $orders = Order::with('items', 'user', 'items.product')->paginate(5);

        // get orders with getPdfAssetUrlAttribute attribute
        $orders->getCollection()->transform(function ($order) {
            $order->pdf_asset_url = $order->pdf_asset_url;
            return $order;
        });

        return response()->json($orders);
    }
}
