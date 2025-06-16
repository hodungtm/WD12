<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Dashboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Lọc theo thời gian
        $from = $request->input('from');
        $to = $request->input('to');

        $orderQuery = Order::query();
        $reviewQuery = Review::query();


        if ($from && $to) {
            $orderQuery->whereBetween('created_at', [$from, $to]);
            $reviewQuery->whereBetween('created_at', [$from, $to]);
           
        }

        $totalOrders = $orderQuery->count();
        $totalUsers = User::count();
        $totalReviews = $reviewQuery->count();


        // Biểu đồ đơn hàng theo thời gian
        $ordersByDate = $orderQuery->selectRaw("DATE(created_at) as date, COUNT(*) as count")
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        // Thống kê theo trạng thái đơn hàng
       $orderStatus = DB::table('orders')
    ->select('status', DB::raw('COUNT(*) as count'))
    ->groupBy('status')
    ->get();

        // Top sản phẩm bán chạy
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('COUNT(order_items.product_id) as total_orders'))
            ->groupBy('products.name')
            ->orderByDesc('total_orders')
            ->limit(5)
            ->get();
 $totalComments = Comment::when($from && $to, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from, $to]);
            })->count();
        return view('admin.dashboard.index', compact(
    'totalOrders', 'totalUsers', 'totalReviews', 'totalComments',
    'ordersByDate', 'orderStatus', 'topProducts',
    'from', 'to'
));

    }
}
