<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
    public function checked($id)
    {
        try {   
            $cartItem = CartItem::find($id);
            if (!$cartItem) {
                return response()->json([
                    'message' => 'Cart item not found',
                ], 404);
            }
    
            // Đảo trạng thái: 0 thành 1, 1 thành 0
            $cartItem->checked = $cartItem->checked ? 0 : 1;
    
            $cartItem->save();
    
            return response()->json([
                'message' => 'Cart item checked status toggled successfully',
                'data' => $cartItem,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred while toggling cart item checked status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }    
    public function checkedAll()
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

            foreach ($cart->cartItems as $item) {
                $item->checked = $item->checked = 1;
                $item->save();
            }

            return response()->json([
                'message' => 'All cart items checked status toggled successfully',
                'data' => $cart,
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Error occurred while toggling cart item checked status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function quantity($id)
    {
        try {
            $cartItem = CartItem::find($id);
            if (!$cartItem) {
                return response()->json([
                    'message' => 'Cart item not found',
                ], 404);
            }

            $quantity = request()->input('quantity');
            if ($quantity < 1) {
                return response()->json([
                    'message' => 'Quantity must be greater than 0',
                ], 400);
            }

            $cartItem->quantity = $quantity;
            $cartItem->total = $cartItem->quantity * $cartItem->product->price;
            $cartItem->save();

            return response()->json([
                'message' => 'Cart item quantity updated successfully',
                'data' => $cartItem,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred while updating cart item quantity',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function delete($id)
    {
        try {
            $cartItem = CartItem::find($id);
            if (!$cartItem) {
                return response()->json([
                    'message' => 'Cart item not found',
                ], 404);
            }

            $cartItem->delete();

            return response()->json([
                'message' => 'Cart item deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred while deleting cart item',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function count()
    {
        try {
            $user = Auth::id();
            if (!$user) {
                return response()->json([
                    "message" => "Vui lòng đăng nhập để sử dụng tài nguyên"
                ], 401);
            }

            $cart = Cart::where('user_id', $user)
                ->whereHas('cartItems', function ($query) {
                    // Kiểm tra nếu Cart có CartItems
                    $query->whereNotNull('id');
                })
                ->withCount('cartItems') // Đếm số lượng CartItem
                // ->withSum('cartItems', 'quantity')
                ->first();
            
            Log::info('Cart Query Result:', ['cart' => $cart]);

            // Kiểm tra xem giỏ hàng có tồn tại không
            if ($cart) {
                $totalItems = $cart->cart_items_count; // Lấy số lượng CartItem
            } else {
                $totalItems = 0; // Nếu giỏ hàng không tồn tại hoặc trống
            }

            return response()->json([
                "message" => "Số lượng sản phẩm trong giỏ hàng",
                "data" => $totalItems
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi đếm số lượng giỏ hàng',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
