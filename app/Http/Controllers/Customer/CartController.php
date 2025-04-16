<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user_id = Auth::id();
            $product_id = $request->input('product_id');
            $color_id = $request->input('color_id');
            $quantity = (int) $request->input('quantity');

            if (!$user_id || !$product_id || !$color_id || !$quantity) {
                return response()->json([
                    'message' => 'Missing required parameters',
                ], 400);
            }

            $product = Product::find($product_id);
            if (!$product) {
                return response()->json([
                    'message' => 'Product not found',
                ], 404);
            }
            $color = Color::where('id', $color_id)
                ->where('product_id', $product_id)
                ->first();
            if (!$color) {
                return response()->json([
                    'message' => 'Color not found for this product',
                ], 404);
            }
            $cart = Cart::firstOrCreate(['user_id' => $user_id]);

            $cartItem = $cart->cartItems()
                ->where('product_id', $product_id)
                ->where('color_id', $color_id)
                ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->total = $cartItem->quantity * $product->price;
                $cartItem->save();
            } else {
                $cart->cartItems()->create([
                    'cart_id' => $cart->id,
                    'product_id' => $product_id,
                    'color_id' => $color_id,
                    'quantity' => $quantity,
                    'total' => $quantity * $product->price,
                ]);
            }

            return response()->json([
                'message' => 'Product added to cart successfully',
                'data' => [
                    'cart_id' => $cart->id,
                    'product_id' => $product_id,
                    'color_id' => $color_id,
                    'quantity' => $quantity,
                    'total' => $quantity * $product->price,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred while adding to cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function index()
    {
        try {
            $user_id = Auth::id();
            if (!$user_id) {
                return response()->json([
                    'message' => 'User not authenticated',
                ], 401);
            }

            $cart = Cart::with(['cartItems.product', 'cartItems.color', 'cartItems.product.images'])
                ->where('user_id', $user_id)
                ->first();

            if (!$cart) {
                return response()->json([
                    'message' => 'Cart not found',
                ], 404);
            }

            return response()->json([
                'message' => 'Cart retrieved successfully',
                'data' => $cart,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred while fetching cart',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
