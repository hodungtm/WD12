<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Products;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Comment;

class CartController extends Controller
{
public function index()
{
    $cartItems = Cart::with([
        'variant.color',
        'variant.size',
        'product.images'
    ])
    ->where('user_id', Auth::id()) // Chỉ lấy giỏ hàng của user hiện tại
    ->get();

    return view('Client.cart.ListCart', compact('cartItems'));
}

    // public function addToCart(Request $request, $productId)
    // {
    //     $product = Products::findOrFail($productId);
    //     $variantId = $request->input('variant_id');

    //     // Validate variant exists
    //     if ($variantId && !ProductVariant::where('id', $variantId)->exists()) {
    //         return back()->withErrors(['variant_id' => 'Selected variant does not exist.']);
    //     }

    //     Cart::create([
    //         'user_id' => Auth::id(),
    //         'product_id' => $product->id,
    //         'variant_id' => $variantId,
    //         'quantity' => 1, // Default quantity
    //     ]);

    //     return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    // }

   public function addToCart(Request $request, $productId)
{
    $product = Products::findOrFail($productId);
    $variantId = $request->input('variant_id');
    $quantity = $request->input('quantity', 1);

    if ($variantId && !ProductVariant::where('id', $variantId)->exists()) {
        return back()->withErrors(['variant_id' => 'Biến thể không tồn tại.']);
    }

    $userId = Auth::id();

    // Tìm xem đã có trong giỏ chưa
    $existing = Cart::where('user_id', $userId)
        ->where('product_id', $productId)
        ->where('variant_id', $variantId)
        ->first();

    if ($existing) {
        $existing->increment('quantity', $quantity);
    } else {
        Cart::create([
            'user_id'    => $userId,
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity'   => $quantity,
        ]);
    }

    return redirect()->route('client.cart.index')->with('success', 'Đã thêm vào giỏ hàng.');
}

   public function updateQuantity(Request $request, $id)
{
    $cartItem = Cart::where('id', $id)->firstOrFail();
    $cartItem->update([
        'quantity' => $request->input('quantity')
    ]);
    return back()->with('success', 'Cập nhật số lượng thành công.');
}

public function remove($id)
{
    Cart::where('id', $id)->where('user_id', Auth::id())->delete();
    return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
}

}
