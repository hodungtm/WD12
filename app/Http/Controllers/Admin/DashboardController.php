<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
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
        $status = $request->get('status');
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
        $totalRevenueQuery = Order::query();
        if ($status) {
            $totalRevenueQuery->where('status', $status);
        } 
        $totalRevenueQuery->whereBetween('created_at', [$currentStart, $currentEnd]);
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
        $ordersCountQuery = Order::query();
        if ($status) {
            $ordersCountQuery->where('status', $status);
        }
        $ordersCountQuery->whereBetween('created_at', [$currentStart, $currentEnd]);
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
        $productsCountQuery = Product::whereBetween('created_at', [$currentStart, $currentEnd]);
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
        $previousRevenue = Order::query()
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('orderItems', function ($q) use ($categoryId) {
                    $q->whereHas('product', function ($q) use ($categoryId) {
                        $q->where('category_id', $categoryId)->withTrashed();
                    });
                });
            })
            ->sum('final_amount');

        $previousOrders = Order::query()
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
        $previousProducts = Product::whereBetween('created_at', [$previousStart, $previousEnd])
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
            $labels = collect(range(1, 12))->map(function($month) {
                return "Tháng $month";
            });

            $revenueData = $labels->map(function($month, $index) use ($status, $categoryId) {
                $query = Order::whereMonth('created_at', $index + 1)
                    ->whereYear('created_at', now()->year);
                
                if ($status) {
                    $query->where('status', $status);
                }

                if ($categoryId) {
                    $query->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                }
                
                return $query->sum('final_amount');
            });

            $orderData = $labels->map(function($month, $index) use ($status, $categoryId) {
                $query = Order::whereMonth('created_at', $index + 1)
                    ->whereYear('created_at', now()->year);
                
                if ($status) {
                    $query->where('status', $status);
                }

                if ($categoryId) {
                    $query->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                }
                
                return $query->count();
            });
        } elseif ($type === 'week') {
            $dates = collect();
            for ($i = 6; $i >= 0; $i--) {
                $dates->push(now()->subDays($i)->format('Y-m-d'));
            }
            $labels = $dates->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            });

            $revenueData = $dates->map(function($date) use ($status, $categoryId) {
                $query = Order::whereDate('created_at', $date);
                
                if ($status) {
                    $query->where('status', $status);
                }

                if ($categoryId) {
                    $query->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                }
                
                return $query->sum('final_amount');
            });

            $orderData = $dates->map(function($date) use ($status, $categoryId) {
                $query = Order::whereDate('created_at', $date);
                
                if ($status) {
                    $query->where('status', $status);
                }

                if ($categoryId) {
                    $query->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                }
                
                return $query->count();
            });
        } elseif ($type === 'today') {
            $selectedDate = $request->get('date', $now->toDateString());
            $currentStart = Carbon::parse($selectedDate)->startOfDay();
            $currentEnd = Carbon::parse($selectedDate)->endOfDay();
            $previousStart = Carbon::parse($selectedDate)->copy()->subDay()->startOfDay();
            $previousEnd = Carbon::parse($selectedDate)->copy()->subDay()->endOfDay();
            $labels = collect([Carbon::parse($selectedDate)->format('d/m')]);
            $dates = collect([Carbon::parse($selectedDate)->toDateString()]);

            $revenueData = $labels->map(function($date) use ($status, $categoryId) {
                $query = Order::whereDate('created_at', $date);
        
                // Thêm điều kiện lọc theo trạng thái
                if ($status) {
                    $query->where('status', $status);
                } else {
                    $query->where('status', 'Hoàn thành');
                }

                if ($categoryId) {
                    $query->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                }
        
                return $query->sum('final_amount');
            });
            
            $orderData = $labels->map(function($date) use ($status, $categoryId) {
                $query = Order::whereDate('created_at', $date);
        
                // Thêm điều kiện lọc theo trạng thái
                if ($status) {
                    $query->where('status', $status);
                } else {
                    $query->where('status', 'Hoàn thành');
                }

                if ($categoryId) {
                    $query->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                }
        
                return $query->count();
            });
        } else { // custom
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

            $revenueData = $dates->map(function($date) use ($status, $categoryId) {
                $query = Order::whereDate('created_at', $date);
        
                // Thêm điều kiện lọc theo trạng thái
                if ($status) {
                    $query->where('status', $status);
                } else {
                    $query->where('status', 'Hoàn thành');
                }

                if ($categoryId) {
                    $query->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                }
        
                return $query->sum('final_amount');
            });
            
            $orderData = $dates->map(function($date) use ($status, $categoryId) {
                $query = Order::whereDate('created_at', $date);
        
                // Thêm điều kiện lọc theo trạng thái
                if ($status) {
                    $query->where('status', $status);
                } else {
                    $query->where('status', 'Hoàn thành');
                }

                if ($categoryId) {
                    $query->whereHas('orderItems', function ($q) use ($categoryId) {
                        $q->whereHas('product', function ($q) use ($categoryId) {
                            $q->where('category_id', $categoryId)->withTrashed();
                        });
                    });
                }
        
                return $query->count();
            });
        }

        // Top sản phẩm bán chạy
        $topProductsQuery = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id') // Thêm join với products
            ->where('orders.status', 'Hoàn thành')
            ->select('order_items.product_id', 'order_items.product_name', DB::raw('SUM(order_items.quantity) as sold'))
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('sold')
            ->whereBetween('order_items.created_at', [$currentStart, $currentEnd]);

        if ($categoryId) {
            $topProductsQuery->where('products.category_id', $categoryId);
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
        $totalConfirmedOrders = Order::where('status', 'Xác nhận đơn')  // Thêm dòng này
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
        $todayRevenueQuery = Order::whereDate('created_at', now());
        $thisWeekRevenueQuery = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        $thisMonthRevenueQuery = Order::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);

        // Áp dụng điều kiện lọc trạng thái
        if ($status) {
            $todayRevenueQuery->where('status', $status);
            $thisWeekRevenueQuery->where('status', $status);
            $thisMonthRevenueQuery->where('status', $status);
        }

        // Áp dụng điều kiện lọc danh mục nếu có
        if ($categoryId) {
            $categoryFilter = function($query) use ($categoryId) {
                $query->whereHas('orderItems', function ($q) use ($categoryId) {
                    $q->whereHas('product', function ($q) use ($categoryId) {
                        $q->where('category_id', $categoryId)->withTrashed();
                    });
                });
            };
            
            $todayRevenueQuery->where($categoryFilter);
            $thisWeekRevenueQuery->where($categoryFilter);
            $thisMonthRevenueQuery->where($categoryFilter);
        }

        // Tính tổng doanh thu
        $todayRevenue = $todayRevenueQuery->sum('final_amount');
        $thisWeekRevenue = $thisWeekRevenueQuery->sum('final_amount');
        $thisMonthRevenue = $thisMonthRevenueQuery->sum('final_amount');

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

        // Cập nhật các thống kê theo trạng thái
        $statusCounts = Order::whereBetween('created_at', [$currentStart, $currentEnd])
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalPendingOrders = $statusCounts['Đang chờ'] ?? 0;
        $totalConfirmedOrders = $statusCounts['Xác nhận đơn'] ?? 0;  // Thêm dòng này
        $totalShippingOrders = $statusCounts['Đang giao hàng'] ?? 0;
        $totalCompletedOrders = $statusCounts['Hoàn thành'] ?? 0;
        $totalCancelledOrders = $statusCounts['Đã hủy'] ?? 0;

        // Tính toán doanh thu và đơn hàng theo trạng thái
        $revenueByStatus = Order::whereBetween('created_at', [$currentStart, $currentEnd])
            ->select('status', DB::raw('SUM(final_amount) as total_amount'))
            ->groupBy('status')
            ->get()
            ->pluck('total_amount', 'status')
            ->toArray();

        $ordersByStatus = Order::whereBetween('created_at', [$currentStart, $currentEnd])
            ->select('status', DB::raw('COUNT(*) as total_count'))
            ->groupBy('status')
            ->get()
            ->pluck('total_count', 'status')
            ->toArray();

        $userData = collect(); // Hoặc khởi tạo với dữ liệu thích hợp
        $productData = collect(); // Hoặc khởi tạo với dữ liệu thích hợp

        return view('admin.dashboard.index', compact(
            'type',
            'totalRevenue',
            'ordersCount',
            'productsCount',
            'usersCount',
            'percentRevenue',
            'percentOrders',
            'percentUsers',
            'percentProducts',
            'labels',
            'revenueData',
            'orderData',
            'categories',
            'topProducts',
            'worstProducts',
            'lowStock',
            'totalPendingOrders',
            'totalConfirmedOrders',
            'totalShippingOrders',
            'totalCompletedOrders',
            'totalCancelledOrders',
            'todayRevenue',          // Add these
            'thisWeekRevenue',       // three revenue
            'thisMonthRevenue',      // variables
            'topCategories',
            'newCustomers',
            'topCustomers',
            'activeDiscountList',
            'reviewStats',
            'totalStockPerProduct',
            'userData',
            'productData',
            'revenueByStatus',
            'ordersByStatus'
        ));
    }
}
