<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Products;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// note: Controller xử lý giỏ hàng cho phía client
class CartController extends Controller
{
    // note: Hiển thị trang giỏ hàng
    public function index()
    {
        // note: Lấy tất cả sản phẩm trong giỏ hàng của user hiện tại với relationships
        $cartItems = Cart::with(['variant.color', 'variant.size', 'product.images'])
            ->where('user_id', Auth::id())
            ->get();

        return view('Client.cart.ListCart', compact('cartItems'));
    }

    // note: Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request, $productId)
    {
        $product = Products::findOrFail($productId);
        $variantId = $request->input('variant_id'); // note: ID biến thể (size, màu)
        $quantity = max(1, (int) $request->input('quantity', 1)); // note: Số lượng, tối thiểu là 1

        $userId = Auth::id();

        // note: Kiểm tra biến thể có tồn tại không
        if (!$variantId || !ProductVariant::find($variantId)) {
            return $this->fail($request, 'Vui lòng chọn đầy đủ màu, kích thước hoặc biến thể không tồn tại.');
        }

        $variant = ProductVariant::find($variantId);
        $stock = $variant->quantity; // note: Số lượng tồn kho

        // note: Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $existing = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        $currentQty = $existing?->quantity ?? 0;
        $newQty = $currentQty + $quantity;

        // note: Kiểm tra số lượng không vượt quá tồn kho
        if ($newQty > $stock) {
            return $this->fail($request, 'Không thể thêm. Hiện đã có ' . $currentQty . ', tồn kho chỉ còn ' . $stock);
        }

        // note: Nếu đã có thì tăng số lượng, chưa có thì tạo mới
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

// note: Cập nhật số lượng tất cả sản phẩm trong giỏ hàng
public function updateAll(Request $request)
{
    $data = $request->input('quantities', []); // note: dạng [cart_id => qty, …]

    foreach ($data as $id => $qty) {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($cartItem) {
            $maxQty = $cartItem->variant->quantity ?? 1;
            $qty = max(1, (int) $qty); // note: Đảm bảo số lượng không âm

            // note: Kiểm tra số lượng không vượt quá tồn kho
            if ($qty > $maxQty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng yêu cầu vượt quá tồn kho (' . $maxQty . ') cho sản phẩm ' . $cartItem->product->name,
                ], 422);
            }

            // note: Nếu số lượng = 0 thì xóa khỏi giỏ hàng
            if ($qty == 0) {
                $cartItem->delete();
            } else {
                $cartItem->update(['quantity' => $qty]);
            }
        }
    }

    // note: Lấy lại giỏ hàng để tính toán subtotal
    $cartItems = Cart::with(['variant', 'product'])->where('user_id', Auth::id())->get();
    $subtotal = $cartItems->sum(fn($item) => $item->variant->sale_price * $item->quantity);

    return response()->json([
        'success' => true,
        'message' => 'Cập nhật giỏ hàng thành công.',
        'subtotal' => $subtotal,
    ]);
}

    // note: Xóa sản phẩm khỏi giỏ hàng
    public function remove($id)
    {
        Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    // note: API trả về dữ liệu mini cart (giỏ hàng nhỏ)
    public function miniCart()
    {
        // note: Lấy sản phẩm trong giỏ hàng với relationships
        $cartItems = Cart::with(['variant.color', 'variant.size', 'product.images'])
            ->where('user_id', Auth::id())
            ->get();

        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $item) {
            // note: Tính giá (ưu tiên giá sale nếu có)
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

    // note: Helper method trả về response thành công
    protected function success(Request $request, $message)
    {
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect()->back()->with('success', $message);
    }

    // note: Helper method trả về response thất bại
    protected function fail(Request $request, $message)
    {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $message]);
        }
        return redirect()->back()->with('error', $message);
    }
}
