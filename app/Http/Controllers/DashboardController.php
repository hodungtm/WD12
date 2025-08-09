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
        $categoryId = $request->get('category_id');
        $quickSearch = $request->get('q');

        // Xác định khoảng thời gian
        if ($type === 'month') {
            $currentStart = $now->copy()->startOfMonth();
            $currentEnd = $now->copy()->endOfMonth();
            $previousStart = $now->copy()->subMonth()->startOfMonth();
            $previousEnd = $now->copy()->subMonth()->endOfMonth();
            $labels = collect(range(1, 12))->map(fn($m) => sprintf('%02d', $m));
            $dates = collect();
        } elseif ($type === 'year') {
            $currentStart = $now->copy()->startOfYear();
            $currentEnd = $now->copy()->endOfYear();
            $previousStart = $now->copy()->subYear()->startOfYear();
            $previousEnd = $now->copy()->subYear()->endOfYear();
            $startYear = $now->year - 5;
            $labels = collect(range($startYear, $now->year));
            $dates = collect();
        } elseif ($type === 'today') {
            $selectedDate = $request->get('date', $now->toDateString());
            $currentStart = Carbon::parse($selectedDate)->startOfDay();
            $currentEnd = Carbon::parse($selectedDate)->endOfDay();
            $previousStart = Carbon::parse($selectedDate)->copy()->subDay()->startOfDay();
            $previousEnd = Carbon::parse($selectedDate)->copy()->subDay()->endOfDay();
            $labels = collect([Carbon::parse($selectedDate)->format('d/m')]);
            $dates = collect([Carbon::parse($selectedDate)->toDateString()]);
        } elseif ($type === 'custom') {
            $startDate = $request->get('start_date', $now->subDays(7)->toDateString());
            $currentStart = Carbon::parse($startDate)->startOfDay();
            $currentEnd = Carbon::parse($request->get('end_date', $now->toDateString()))->endOfDay();
            $previousStart = $currentStart->copy()->subDays($currentEnd->diffInDays($currentStart));
            $previousEnd = $currentStart->copy()->subDay()->endOfDay();
            $dates = collect();
            $labels = collect();
            $current = $currentStart->copy();
            while ($current <= $currentEnd) {
                $dates->push($current->toDateString());
                $labels->push($current->format('d/m'));
                $current->addDay();
            }
        } else { // week
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

        // Tổng doanh thu
        $totalRevenueQuery = Order::where('status', 'Hoàn thành')
            ->whereBetween('created_at', [$currentStart, $currentEnd]);
        if ($categoryId) {
            $totalRevenueQuery->whereHas('orderItems', function ($q) use ($categoryId) {
                $q->whereHas('product', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId)->withTrashed();
                });
            });
        }
        if ($quickSearch) {
            $totalRevenueQuery->where(function ($q) use ($quickSearch) {
                $q->where('id', $quickSearch)
                  ->orWhere('user_id', $quickSearch)
                  ->orWhere('status', 'like', "%$quickSearch%");
            });
        }
        $totalRevenue = $totalRevenueQuery->sum('final_amount');

        // Tổng đơn hàng
        $ordersCountQuery = Order::where('status', 'Hoàn thành')
            ->whereBetween('created_at', [$currentStart, $currentEnd]);
        if ($categoryId) {
            $ordersCountQuery->whereHas('orderItems', function ($q) use ($categoryId) {
                $q->whereHas('product', function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId)->withTrashed();
                });
            });
        }
        if ($quickSearch) {
            $ordersCountQuery->where(function ($q) use ($quickSearch) {
                $q->where('id', $quickSearch)
                  ->orWhere('user_id', $quickSearch)
                  ->orWhere('status', 'like', "%$quickSearch%");
            });
        }
        $ordersCount = $ordersCountQuery->count();

        // Tổng sản phẩm
        $productsCountQuery = Products::whereBetween('created_at', [$currentStart, $currentEnd]);
        if ($categoryId) {
            $productsCountQuery->where('category_id', $categoryId);
        }
        if ($quickSearch) {
            $productsCountQuery->where(function ($q) use ($quickSearch) {
                $q->where('name', 'like', "%$quickSearch%")
                  ->orWhere('id', $quickSearch)
                  ->orWhere('product_code', 'like', "%$quickSearch%")
                  ->orWhereHas('category', function ($q) use ($quickSearch) {
                      $q->where('ten_danh_muc', 'like', "%$quickSearch%");
                  });
            });
        }
        $productsCount = $productsCountQuery->count();

        // Tổng khách hàng
        $usersCountQuery = User::whereBetween('created_at', [$currentStart, $currentEnd]);
        if ($quickSearch) {
            $usersCountQuery->where(function ($q) use ($quickSearch) {
                $q->where('name', 'like', "%$quickSearch%")
                  ->orWhere('email', 'like', "%$quickSearch%");
            });
        }
        $usersCount = $usersCountQuery->count();

        // Dữ liệu so sánh với kỳ trước
        $previousRevenue = Order::where('status', 'Hoàn thành')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('orderItems', function ($q) use ($categoryId) {
                    $q->whereHas('product', function ($q) use ($categoryId) {
                        $q->where('category_id', $categoryId)->withTrashed();
                    });
                });
            })
            ->sum('final_amount');

        $previousOrders = Order::where('status', 'Hoàn thành')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('orderItems', function ($q) use ($categoryId) {
                    $q->whereHas('product', function ($q) use ($categoryId) {
                        $q->where('category_id', $categoryId)->withTrashed();
                    });
                });
            })
            ->count();

        $previousUsers = User::whereBetween('created_at', [$previousStart, $previousEnd])->count();
        $previousProducts = Products::whereBetween('created_at', [$previousStart, $previousEnd])
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->count();

        $percentRevenue = $previousRevenue > 0 ? round((($totalRevenue - $previousRevenue) / $previousRevenue) * 100, 2) : 0;
        $percentOrders = $previousOrders > 0 ? round((($ordersCount - $previousOrders) / $previousOrders) * 100, 2) : 0;
        $percentUsers = $previousUsers > 0 ? round((($usersCount - $previousUsers) / $previousUsers) * 100, 2) : 0;
        $percentProducts = $previousProducts > 0 ? round((($productsCount - $previousProducts) / $previousProducts) * 100, 2) : 0;

        // Dữ liệu biểu đồ
        if ($type === 'month') {
            $revenueData = $labels->map(fn($m) => Order::whereMonth('created_at', $m)
                ->whereYear('created_at', $now->year)
                ->where('status', 'Hoàn thành')
                ->when($categoryId, function ($q) use ($categoryId) {
                    $q->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                })
                ->sum('final_amount'));
            $orderData = $labels->map(fn($m) => Order::whereMonth('created_at', $m)
                ->whereYear('created_at', $now->year)
                ->where('status', 'Hoàn thành')
                ->when($categoryId, function ($q) use ($categoryId) {
                    $q->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                })
                ->count());
            $userData = $labels->map(fn($m) => User::whereMonth('created_at', $m)
                ->whereYear('created_at', $now->year)
                ->count());
            $productData = $labels->map(fn($m) => Products::whereMonth('created_at', $m)
                ->whereYear('created_at', $now->year)
                ->when($categoryId, function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                })
                ->count());
        } elseif ($type === 'year') {
            $revenueData = $labels->map(fn($y) => Order::whereYear('created_at', $y)
                ->where('status', 'Hoàn thành')
                ->when($categoryId, function ($q) use ($categoryId) {
                    $q->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                })
                ->sum('final_amount'));
            $orderData = $labels->map(fn($y) => Order::whereYear('created_at', $y)
                ->where('status', 'Hoàn thành')
                ->when($categoryId, function ($q) use ($categoryId) {
                    $q->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                })
                ->count());
            $userData = $labels->map(fn($y) => User::whereYear('created_at', $y)->count());
            $productData = $labels->map(fn($y) => Products::whereYear('created_at', $y)
                ->when($categoryId, function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                })
                ->count());
        } else { // today, week, custom
            $revenueData = $dates->map(fn($date) => Order::whereDate('created_at', $date)
                ->where('status', 'Hoàn thành')
                ->when($categoryId, function ($q) use ($categoryId) {
                    $q->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                })
                ->sum('final_amount'));
            $orderData = $dates->map(fn($date) => Order::whereDate('created_at', $date)
                ->where('status', 'Hoàn thành')
                ->when($categoryId, function ($q) use ($categoryId) {
                    $q->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                })
                ->count());
            $userData = $dates->map(fn($date) => User::whereDate('created_at', $date)->count());
            $productData = $dates->map(fn($date) => Products::whereDate('created_at', $date)
                ->when($categoryId, function ($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                })
                ->count());
        }

        // Top sản phẩm bán chạy
        $topProductsQuery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'Hoàn thành')
            ->select('order_items.product_id', 'order_items.product_name', DB::raw('SUM(order_items.quantity) as sold'))
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('sold')
            ->whereBetween('order_items.created_at', [$currentStart, $currentEnd]);
        if ($categoryId) {
            $topProductsQuery->whereHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId)->withTrashed();
            });
        }
        $topProducts = $topProductsQuery->take(5)->get();

        // Top sản phẩm bán kém nhất
        $worstProductsQuery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'Hoàn thành')
            ->select('order_items.product_id', 'order_items.product_name', DB::raw('SUM(order_items.quantity) as sold'))
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderBy('sold', 'asc')
            ->whereBetween('order_items.created_at', [$currentStart, $currentEnd]);
        if ($categoryId) {
            $worstProductsQuery->whereHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId)->withTrashed();
            });
        }
        $worstProducts = $worstProductsQuery->take(5)->get();

        // Sản phẩm sắp hết hàng
        $lowStockQuery = ProductVariant::where('quantity', '<=', 5)->with(['product' => function ($query) {
            $query->withTrashed();
        }]);
        if ($categoryId) {
            $lowStockQuery->whereHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId)->withTrashed();
            });
        }
        $lowStock = $lowStockQuery->get();

        // Tổng tồn kho theo sản phẩm
        $totalStockPerProductQuery = ProductVariant::with(['product' => function ($query) {
            $query->withTrashed();
        }])
            ->select('product_id', DB::raw('SUM(quantity) as total_stock'))
            ->whereHas('product')
            ->groupBy('product_id');
        if ($categoryId) {
            $totalStockPerProductQuery->whereHas('product', function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId)->withTrashed();
            });
        }
        $totalStockPerProduct = $totalStockPerProductQuery->get();

        // Đơn hàng theo trạng thái
        $totalPendingOrders = Order::where('status', 'Đang chờ')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();
        $totalShippingOrders = Order::where('status', 'Đang giao hàng')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();
        $totalCompletedOrders = Order::where('status', 'Hoàn thành')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();
        $totalCancelledOrders = Order::where('status', 'Đã hủy')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();

        // Doanh thu theo thời gian
        $todayRevenue = Order::whereDate('created_at', now())
            ->where('status', 'Hoàn thành')
            ->sum('final_amount');
        $thisWeekRevenue = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'Hoàn thành')
            ->sum('final_amount');
        $thisMonthRevenue = Order::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('status', 'Hoàn thành')
            ->sum('final_amount');

        // Top danh mục bán chạy
        $topCategories = DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'Hoàn thành')
            ->whereBetween('order_items.created_at', [$currentStart, $currentEnd])
            ->select('categories.id', 'categories.ten_danh_muc', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('categories.id', 'categories.ten_danh_muc')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // Khách hàng mới
        $newCustomers = User::whereDate('created_at', now())->get();

        // Khách hàng chi tiêu nhiều nhất
        $topCustomers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.status', 'Hoàn thành')
            ->whereBetween('orders.created_at', [$currentStart, $currentEnd])
            ->select('users.id', 'users.name', 'users.email', DB::raw('SUM(orders.final_amount) as total_spent'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get();

        // Mã giảm giá đang hoạt động
        $activeDiscountList = Discount::whereDate('end_date', '>=', now())
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get()
            ->map(function ($discount) {
                $discount->discount_percent = $discount->type === 'percent' ? $discount->value : null;
                return $discount;
            });

        // Thống kê đánh giá
        $reviewStats = DB::table('reviews')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->selectRaw('COUNT(*) as total_reviews, 
                         SUM(CASE WHEN so_sao >= 4 THEN 1 ELSE 0 END) as good_reviews, 
                         SUM(CASE WHEN so_sao <= 2 THEN 1 ELSE 0 END) as bad_reviews')
            ->first();

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
            'worstProducts',
            'lowStock',
            'totalPendingOrders',
            'totalShippingOrders',
            'totalCompletedOrders',
            'totalCancelledOrders',
            'todayRevenue',
            'thisWeekRevenue',
            'thisMonthRevenue',
            'topCategories',
            'totalStockPerProduct',
            'newCustomers',
            'topCustomers',
            'activeDiscountList',
            'reviewStats',
            'categories'
        ));
    }
}
