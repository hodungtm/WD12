<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Products;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $topProducts = ProductVariant::select('product_id', DB::raw('SUM(quantity) as sold'))
            ->groupBy('product_id')
            ->orderByDesc('sold')
            ->with('product')
            ->take(5)
            ->get();

        $lowStock = ProductVariant::where('quantity', '<=', 5)->with('product')->get();

        return view('admin.dashboard.index', compact(
            'totalRevenue', 'ordersCount', 'productsCount', 'usersCount',
            'labels', 'revenueData', 'orderData', 'userData', 'productData',
            'percentRevenue', 'percentOrders', 'percentUsers', 'percentProducts',
            'type', 'topProducts', 'lowStock'
        ));
    }
}
