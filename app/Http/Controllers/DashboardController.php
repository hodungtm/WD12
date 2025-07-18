<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Products;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Discount;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'week');
        $now = Carbon::now();

        if ($type === 'month') {
            $currentStart = $now->copy()->startOfMonth();
            $currentEnd = $now->copy()->endOfMonth();
            $previousStart = $now->copy()->subMonth()->startOfMonth();
            $previousEnd = $now->copy()->subMonth()->endOfMonth();
            $labels = collect(range(1, 12))->map(fn($m) => sprintf('%02d', $m));
        } elseif ($type === 'year') {
            $currentStart = $now->copy()->startOfYear();
            $currentEnd = $now->copy()->endOfYear();
            $previousStart = $now->copy()->subYear()->startOfYear();
            $previousEnd = $now->copy()->subYear()->endOfYear();
            $startYear = $now->year - 5;
            $labels = collect(range($startYear, $now->year));
        } elseif ($type === 'today') {
            $selectedDate = $request->get('date', $now->toDateString());
            $currentStart = Carbon::parse($selectedDate)->startOfDay();
            $currentEnd = Carbon::parse($selectedDate)->endOfDay();
            $previousStart = Carbon::parse($selectedDate)->copy()->subDay()->startOfDay();
            $previousEnd = Carbon::parse($selectedDate)->copy()->subDay()->endOfDay();
            $labels = collect([Carbon::parse($selectedDate)->format('d/m')]);
            $dates = collect([Carbon::parse($selectedDate)->toDateString()]);
        } else {
            $currentStart = $now->copy()->startOfWeek(Carbon::MONDAY);
            $currentEnd = $now->copy()->endOfWeek(Carbon::SUNDAY);
            $previousStart = $now->copy()->subWeek()->startOfWeek(Carbon::MONDAY);
            $previousEnd = $now->copy()->subWeek()->endOfWeek(Carbon::SUNDAY);
            $dates = collect();
            $labels = collect();
            for ($i = 0; $i < 7; $i++) {
                $day = $currentStart->copy()->addDays($i);
                $dates->push($day->toDateString());
                $labels->push($day->format('d/m'));
            }
        }

        $totalRevenue = Order::where('status', 'Hoàn thành')->whereBetween('created_at', [$currentStart, $currentEnd])->sum('final_amount');
        $ordersCount = Order::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $productsCount = Products::whereBetween('created_at', [$currentStart, $currentEnd])->count();
        $usersCount = User::whereBetween('created_at', [$currentStart, $currentEnd])->count();

        $previousRevenue = Order::where('status', 'Hoàn thành')->whereBetween('created_at', [$previousStart, $previousEnd])->sum('final_amount');
        $previousOrders = Order::whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $previousUsers = User::whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $previousProducts = Products::whereBetween('created_at', [$previousStart, $previousEnd])->count();

        $percentRevenue = $previousRevenue > 0 ? round((($totalRevenue - $previousRevenue) / $previousRevenue) * 100, 2) : 0;
        $percentOrders = $previousOrders > 0 ? round((($ordersCount - $previousOrders) / $previousOrders) * 100, 2) : 0;
        $percentUsers = $previousUsers > 0 ? round((($usersCount - $previousUsers) / $previousUsers) * 100, 2) : 0;
        $percentProducts = $previousProducts > 0 ? round((($productsCount - $previousProducts) / $previousProducts) * 100, 2) : 0;

        if ($type === 'month') {
            $revenueData = $labels->map(fn($m) => Order::whereMonth('created_at', $m)->whereYear('created_at', $now->year)->where('status', 'Hoàn thành')->sum('final_amount'));
            $orderData = $labels->map(fn($m) => Order::whereMonth('created_at', $m)->whereYear('created_at', $now->year)->count());
            $userData = $labels->map(fn($m) => User::whereMonth('created_at', $m)->whereYear('created_at', $now->year)->count());
            $productData = $labels->map(fn($m) => Products::whereMonth('created_at', $m)->whereYear('created_at', $now->year)->count());
        } elseif ($type === 'year') {
            $revenueData = $labels->map(fn($y) => Order::whereYear('created_at', $y)->where('status', 'Hoàn thành')->sum('final_amount'));
            $orderData = $labels->map(fn($y) => Order::whereYear('created_at', $y)->count());
            $userData = $labels->map(fn($y) => User::whereYear('created_at', $y)->count());
            $productData = $labels->map(fn($y) => Products::whereYear('created_at', $y)->count());
        } else {
            $revenueData = $dates->map(fn($date) => Order::whereDate('created_at', $date)->where('status', 'Hoàn thành')->sum('final_amount'));
            $orderData = $dates->map(fn($date) => Order::whereDate('created_at', $date)->count());
            $userData = $dates->map(fn($date) => User::whereDate('created_at', $date)->count());
            $productData = $dates->map(fn($date) => Products::whereDate('created_at', $date)->count());
        }

        $categoryId = $request->get('category_id');

        // Top sản phẩm bán chạy theo danh mục và theo thời gian
        $topProductsQuery = ProductVariant::select('product_id', DB::raw('SUM(quantity) as sold'))
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('sold');
        if ($categoryId) {
            $topProductsQuery->whereHas('product', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        // Lọc theo thời gian
        if ($type === 'month') {
            $topProductsQuery->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year);
        } elseif ($type === 'year') {
            $topProductsQuery->whereYear('created_at', $now->year);
        } elseif ($type === 'today') {
            $selectedDate = $request->get('date', $now->toDateString());
            $topProductsQuery->whereDate('created_at', $selectedDate);
        } else { // week
            $topProductsQuery->whereBetween('created_at', [$currentStart, $currentEnd]);
        }
        $topProducts = $topProductsQuery->take(5)->get();

        // Sản phẩm sắp hết hàng theo danh mục
        $lowStockQuery = ProductVariant::where('quantity', '<=', 5)->with('product');
        if ($categoryId) {
            $lowStockQuery->whereHas('product', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        $lowStock = $lowStockQuery->get();

        // Tổng tồn kho theo sản phẩm theo danh mục
        $totalStockPerProductQuery = ProductVariant::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_stock'))
            ->whereHas('product');
        if ($categoryId) {
            $totalStockPerProductQuery->whereHas('product', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        $totalStockPerProduct = $totalStockPerProductQuery->groupBy('product_id')->get();

        // XÓA: $orderStatus = $request->get('order_status');
        // XÓA: Đếm đơn hàng theo trạng thái filter (không còn filter trạng thái đơn hàng)
        $totalPendingOrders = Order::where('status', 'Đang chờ')->count();
        $totalShippingOrders = Order::where('status', 'Đang giao hàng')->count();
        $totalCompletedOrders = Order::where('status', 'Hoàn thành')->count();

        $todayRevenue = Order::whereDate('created_at', now())
            ->where('status', 'Hoàn thành')
            ->sum('final_amount');

        $thisWeekRevenue = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'Hoàn thành')
            ->sum('final_amount');

        $thisMonthRevenue = Order::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('status', 'Hoàn thành')
            ->sum('final_amount');

        $topCategories = DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->select('categories.ten_danh_muc', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('categories.id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

            $newCustomers = User::whereDate('created_at', now())->get();

        $topCustomers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('users.name', 'users.email', DB::raw('SUM(orders.final_amount) as total_spent'))
            ->groupBy('users.id')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        $activeDiscounts = DB::table('discounts')
            ->whereDate('end_date', '>=', now())
            ->count();

        $reviewStats = DB::table('reviews')
            ->selectRaw('COUNT(*) as total_reviews, 
                 SUM(CASE WHEN so_sao >= 4 THEN 1 ELSE 0 END) as good_reviews, 
                 SUM(CASE WHEN so_sao <= 2 THEN 1 ELSE 0 END) as bad_reviews')
            ->first();

        // Lấy danh sách mã giảm giá đang hoạt động
        $activeDiscountList = Discount::whereDate('end_date', '>=', now())
            ->orderBy('end_date', 'asc')
            ->take(20)
            ->get()
            ->map(function($discount) {
                if ($discount->type === 'percent') {
                    $display_value = $discount->value . '%';
                } else {
                    $display_value = number_format($discount->value, 0, ',', '.') . 'đ';
                }
                $discount->display_value = $display_value;
                return $discount;
            });

        $quickSearch = $request->get('q');

        // Quick search cho sản phẩm
        $productsQuery = Products::query();
        if ($quickSearch) {
            $productsQuery->where('name', 'like', "%$quickSearch%")
                ->orWhere('id', $quickSearch)
                ->orWhere('product_code', 'like', "%$quickSearch%")
                ->orWhereHas('category', function($q) use ($quickSearch) {
                    $q->where('ten_danh_muc', 'like', "%$quickSearch%") ;
                });
        }
        $productsCount = $productsQuery->whereBetween('created_at', [$currentStart, $currentEnd])->count();

        // Quick search cho đơn hàng
        $ordersQuery = Order::query();
        if ($quickSearch) {
            $ordersQuery->where('id', $quickSearch)
                ->orWhere('user_id', $quickSearch)
                ->orWhere('status', 'like', "%$quickSearch%") ;
        }
        $ordersCount = $ordersQuery->whereBetween('created_at', [$currentStart, $currentEnd])->count();

        // Quick search cho khách hàng
        $usersQuery = User::query();
        if ($quickSearch) {
            $usersQuery->where('name', 'like', "%$quickSearch%")
                ->orWhere('email', 'like', "%$quickSearch%") ;
        }
        $usersCount = $usersQuery->whereBetween('created_at', [$currentStart, $currentEnd])->count();

        // XÓA: Lấy danh sách mã giảm giá đang hoạt động (áp dụng filter loại/trạng thái)
        $activeDiscountList = Discount::whereDate('end_date', '>=', now())
            ->orderBy('end_date', 'asc')
            ->take(20)
            ->get()
            ->map(function($discount) {
                if ($discount->type === 'percent') {
                    $display_value = $discount->value . '%';
                } else {
                    $display_value = number_format($discount->value, 0, ',', '.') . 'đ';
                }
                $discount->display_value = $display_value;
                return $discount;
            });

        $categories = Category::orderBy('ten_danh_muc')->get();

        return view('admin.dashboard.index', compact(
            'totalRevenue',
            'ordersCount',
            'productsCount',
            'usersCount',
            'labels',
            'revenueData',
            'orderData',
            'userData',
            'productData',
            'percentRevenue',
            'percentOrders',
            'percentUsers',
            'percentProducts',
            'type',
            'topProducts',
            'lowStock',
            'totalPendingOrders',
            'totalShippingOrders',
            'totalCompletedOrders',
            'todayRevenue',
            'thisWeekRevenue',
            'thisMonthRevenue',
            'topCategories',
            'totalStockPerProduct',
            'newCustomers',
            'topCustomers',
            'activeDiscounts',
            'activeDiscountList',
            'reviewStats',
            'categories'
        ));
    }
}
