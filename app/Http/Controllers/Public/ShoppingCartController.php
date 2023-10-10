<?php

namespace App\Http\Controllers\Public;
use App\Http\Requests\SaveCartRequest;
use App\Http\Requests\SubmitOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ShoppingCartController extends Controller
{
    public function get(Request $request): JsonResponse
    {
        $cart = ShoppingCart::where('user_id', $request->userId)->first();

        if (!$cart || !$cart->items) {
            return response()->json([]);
        }

        $itemsArray = json_decode($cart->items, true);

        // get products from by cart items
        $cartItems = Product::whereIn('id', $itemsArray)->get();

        return response()->json($cartItems);
    }

    public function save(SaveCartRequest $request): JsonResponse
    {
        $cart = ShoppingCart::updateOrCreate(
            ['user_id' => $request->userId],
            ['items' => json_encode($request->items)]
        );

        return response()->json($cart);
    }

    public function submitOrder(SubmitOrderRequest $request): JsonResponse
    {
        $cart = ShoppingCart::where('user_id', $request->userId)->first();

        if (!$cart || !$cart->items) {
            return response()->json([]);
        }

        $itemsArray = collect($request->items);

        // get products from by cart items
        $cartItems = Product::whereIn('id', $itemsArray->pluck('id'))->get();

        // create an order
        $order = new Order();
        $order->user_id = $request->userId;
        $order->total_price = $request->total;
        $order->save();

        // create order items
        foreach ($cartItems as $cartItem) {
            $order->items()->create([
                'product_id' => $cartItem->id,
                'quantity' => $itemsArray->where('id', $cartItem->id)->pluck('quantity')->first(),
                'price_per_item' => $cartItem->price,
            ]);
        }

        // delete the cart
        $cart->delete();

        return response()->json($order);
    }
}
