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


  public function addToCart(Request $request, $productId)
{
    $product = Products::findOrFail($productId);
    $variantId = $request->input('variant_id');
    $quantity = (int) $request->input('quantity', 1);

    if ($variantId) {
        $variant = ProductVariant::find($variantId);
        if (!$variant) {
            return back()->withErrors(['variant_id' => 'Biến thể không tồn tại.']);
        }

        $stock = $variant->quantity;
    } else {
        $stock = $product->quantity;
    }

    $userId = Auth::id();

    // Kiểm tra giỏ hàng hiện có
    $existing = Cart::where('user_id', $userId)
        ->where('product_id', $productId)
        ->where('variant_id', $variantId)
        ->first();

    $currentQty = $existing ? $existing->quantity : 0;
    $newQty = $currentQty + $quantity;

    if ($newQty > $stock) {
      return back()->with('error', 'Không thể thêm ' . $quantity . ' sản phẩm. Hiện bạn đã có ' . $currentQty . ' sản phẩm trong giỏ. Tồn kho chỉ còn ' . $stock . '.');


    }

    // Thêm hoặc cập nhật giỏ hàng
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
