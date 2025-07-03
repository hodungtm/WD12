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
        // 'variant.images',     // load ảnh của biến thể nếu có
        'variant.color',
        'variant.size',
        'product.images'      // load ảnh của sản phẩm
    ])->get();

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

    $cart = session()->get('cart', []);

    // Tạo khóa riêng cho mỗi biến thể sản phẩm
    $cartKey = $productId . '-' . $variantId;

    // Nếu sản phẩm đã có thì tăng số lượng
    if (isset($cart[$cartKey])) {
        $cart[$cartKey]['quantity'] += $quantity;
    } else {
        $cart[$cartKey] = [
            'product_id'  => $productId,
            'variant_id'  => $variantId,
            'quantity'    => $quantity,
            'name'        => $product->name,
            'image'       => optional($product->images->first())->image,
            'price'       => ProductVariant::find($variantId)?->price ?? $product->price,
        ];
    }

    session()->put('cart', $cart);

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
