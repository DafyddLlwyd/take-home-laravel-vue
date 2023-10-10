<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductRequest;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Illuminate\View\View;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * product index page
     */
    public function index(): View
    {
        return view('layouts.store');
    }

    /**
     * Display a list of products.
     */
    public function list(): JsonResponse
    {
        $products = Product::with('category', 'tags')->get();

        return response()->json($products);
    }

    /**
     * Display a product.
     */
    public function show(Int $productID): JsonResponse
    {
        $product = Product::with('category', 'tags')->find($productID);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
}
