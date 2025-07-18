<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderCompletedMail;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Order_items; // Đổi tên thành OrderItem nếu đúng chuẩn PSR-4

use App\Models\Products;
use App\Models\ProductVariant;
use App\Models\Receiver;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Models\Size; // Thêm Model Size
use App\Models\Color; // Thêm Model Color

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use \Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shippingMethod', 'orderItems']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            Log::info('Searching for order_code: ' . $search);
            $query->where('order_code', 'LIKE', '%' . $search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('shipping_method')) {
            $query->where('shipping_method_id', $request->shipping_method);
        }
        // Sắp xếp ngày tạo
        if ($request->sort_created === 'asc') {
            $query->orderBy('created_at', 'asc');
        } elseif ($request->sort_created === 'desc') {
            $query->orderBy('created_at', 'desc');
        }
        // Sắp xếp tổng tiền
        if ($request->sort_total) {
            $query->orderBy('final_amount', $request->sort_total);
        }
        $perPage = $request->input('per_page', 10);
        $orders = $query->paginate($perPage)->appends($request->query());
        $shippingMethods = ShippingMethod::all();
        return view('admin.orders.index', compact('orders', 'shippingMethods'));
    }

    public function show($id)
    {
        $order = Order::with([
            'user',
            'shippingMethod',
            'orderItems.product',
            'orderItems.productVariant.color', // Đảm bảo tải mối quan hệ color
            'orderItems.productVariant.size',  // Đảm bảo tải mối quan hệ size
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::with([
            'user',               // Người đặt
            'orderItems',         // Danh sách sản phẩm snapshot
            'shippingMethod',     // Phương thức vận chuyển
            // 'discount',           // Mã giảm giá (nếu có)
        ])->findOrFail($id);
        $users = User::all(); // Lấy danh sách người dùng

        $shippingMethods = ShippingMethod::all(); // Lấy tất cả phương thức vận chuyển

        return view('admin.orders.edit', compact(
            'order',
            'users',
            'shippingMethods',
            // 'discounts'
        ));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $originalStatus = $order->status;
        $originalPaymentStatus = $order->payment_status;

        $rules = [
            'status' => [
                'required',
                'string',
                Rule::in(['Đang chờ', 'Đang xử lý', 'Đang giao hàng', 'Đã giao hàng', 'Hoàn thành', 'Đã hủy']),
            ],
            'payment_status' => [
                'required',
                'string',
                Rule::in(['Chờ thanh toán', 'Đã thanh toán']),
            ],
            'note' => 'nullable|string|max:1000',
        ];

        $validatedData = $request->validate($rules);

        // Ràng buộc logic trạng thái
        if (
            $validatedData['payment_status'] === 'Chờ thanh toán' &&
            in_array($validatedData['status'], ['Đang giao hàng', 'Hoàn thành'])
        ) {
            return redirect()->back()->withErrors([
                'status' => 'Không thể chuyển trạng thái đơn hàng sang "' . $validatedData['status'] . '" khi trạng thái thanh toán là "Chờ thanh toán".'
            ])->withInput();
        }

        if ($originalPaymentStatus === 'Đã thanh toán' && $validatedData['payment_status'] === 'Chờ thanh toán') {
            return redirect()->back()->withErrors([
                'payment_status' => 'Không thể chuyển trạng thái thanh toán từ "Đã thanh toán" về "Chờ thanh toán".'
            ])->withInput();
        }

        if ($originalStatus === 'Đã hủy' && $validatedData['status'] !== 'Đã hủy') {
            return redirect()->back()->withErrors([
                'status' => 'Không thể thay đổi trạng thái của đơn hàng đã bị hủy.'
            ])->withInput();
        }

        if ($originalStatus === 'Đang giao hàng' && !in_array($validatedData['status'], ['Hoàn thành', 'Đã hủy', 'Đang giao hàng'])) {
            return redirect()->back()->withErrors([
                'status' => 'Đơn hàng đang giao chỉ có thể chuyển sang "Hoàn thành", "Đã hủy" hoặc giữ nguyên "Đang giao hàng".'
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            $order->update([
                'status'         => $validatedData['status'],
                'payment_status' => $validatedData['payment_status'],
                'note'           => $validatedData['note'] ?? null,
            ]);
            // Gửi email nếu đơn hàng chuyển sang "Hoàn thành"
            if (
                $originalStatus !== 'Hoàn thành' &&
                $validatedData['status'] === 'Hoàn thành' &&
                $order->user && $order->user->email
            ) {
                Mail::to($order->user->email)->send(new OrderCompletedMail($order));
            }

            DB::commit();
            return redirect()->route('admin.orders.edit', $order->id)
                ->with('success', 'Cập nhật đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Lỗi khi cập nhật đơn hàng: " . $e->getMessage(), [
                'order_id' => $order->id,
                'request_data' => $request->all()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật đơn hàng: ' . $e->getMessage())
                ->withInput();
        }
    }

    
}
