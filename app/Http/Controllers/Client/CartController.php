<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Products;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with(['variant.color', 'variant.size', 'product.images'])
            ->where('user_id', Auth::id())
            ->get();

        return view('Client.cart.ListCart', compact('cartItems'));
    }

public function addToCart(Request $request, $productId)
{
    $product = Products::findOrFail($productId);
    $variantId = $request->input('variant_id');
    $quantity = (int) $request->input('quantity', 1);
    $userId = Auth::id();

        if (!$variantId || !ProductVariant::find($variantId)) {
            return $this->fail($request, 'Vui lòng chọn đầy đủ màu, kích thước hoặc biến thể không tồn tại.');
        }

        $variant = ProductVariant::find($variantId);
        $stock = $variant->quantity;

        $existing = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        $currentQty = $existing?->quantity ?? 0;
        $newQty = $currentQty + $quantity;

        if ($newQty > $stock) {
            return $this->fail($request, 'Không thể thêm. Hiện đã có ' . $currentQty . ', tồn kho chỉ còn ' . $stock);
        }

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

        return $this->success($request, 'Đã thêm vào giỏ hàng!');
    }

    public function updateAll(Request $request)
    {
        $data = $request->input('quantities', []); // dạng [cart_id => qty, …]

        foreach ($data as $id => $qty) {
            $cartItem = Cart::where('id', $id)
                ->where('user_id', Auth::id())
                ->first();

            if ($cartItem) {
                $maxQty = $cartItem->variant->quantity ?? 1;
                $cartItem->update([
                    'quantity' => min($maxQty, max(1, (int) $qty)),
                ]);
            }
        }

        return back()->with('success', 'Cập nhật giỏ hàng thành công.');
    }

    public function remove($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function miniCart()
    {
        $cartItems = Cart::with(['variant.color', 'variant.size', 'product.images'])
            ->where('user_id', Auth::id())
            ->get();

        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $price = $item->variant->sale_price && $item->variant->sale_price < $item->variant->price
                ? $item->variant->sale_price
                : $item->variant->price;

            $items[] = [
                'id'      => $item->id,
                'name'    => $item->product->name,
                'qty'     => $item->quantity,
                'price'   => $price,
                'image'   => $item->product->images->first()
                    ? asset('storage/' . $item->product->images->first()->image)
                    : asset('assets/images/no-image.png'),
                'link'    => route('client.product.detail', $item->product_id),
                'variant' => [
                    'color' => $item->variant->color->name ?? null,
                    'size'  => $item->variant->size->name ?? null,
                ],
            ];

            $subtotal += $price * $item->quantity;
        }

        return response()->json([
            'items'   => $items,
            'subtotal' => $subtotal,
            'count'   => $cartItems->count(),
        ]);
    }

    protected function success(Request $request, $message)
    {
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect()->back()->with('success', $message);
    }

    protected function fail(Request $request, $message)
    {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $message]);
        }
        return redirect()->back()->with('error', $message);
    }
}
