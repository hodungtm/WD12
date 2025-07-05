<?php

namespace App\Http\Controllers\Client;

use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class ListProductClientController extends Controller
{
    public function index(Request $request)
    {
        // Query sản phẩm kèm quan hệ
        $query = Products::query()
            ->with(['category', 'images', 'variants'])
            ->where('status', 1);

        // Tìm kiếm tên sản phẩm
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Lọc theo màu
        if ($request->filled('color')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('color_id', $request->color);
            });
        }

        // Lọc theo size
        if ($request->filled('size')) {
            $query->whereHas('variants', function ($q) use ($request) {
                $q->where('size_id', $request->size);
            });
        }
        $minPrice = ProductVariant::min('price');
        $maxPrice = ProductVariant::max('price');
        // Lọc theo khoảng giá
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->whereHas('variants', function ($q) use ($request) {
                if ($request->filled('price_min')) {
                    $q->where('price', '>=', $request->price_min);
                }
                if ($request->filled('price_max')) {
                    $q->where('price', '<=', $request->price_max);
                }
            });
        }

        // Xử lý sắp xếp theo giá từ bảng variants
        $perPage = 9;

        if (in_array($request->sort, ['price_asc', 'price_desc'])) {
            $products = $query->get();

            // Sắp xếp bằng PHP theo giá variant đầu tiên (hoặc nhỏ nhất)
            $products = $products->sortBy(function ($product) {
                return $product->variants->min('price') ?? PHP_INT_MAX;
            });

            if ($request->sort == 'price_desc') {
                $products = $products->reverse();
            }

            // Phân trang thủ công
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $paged = $products->forPage($currentPage, $perPage)->values();

            $products = new LengthAwarePaginator(
                $paged,
                $products->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } else {
            // Sắp xếp bình thường
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }

            $products = $query->paginate($perPage)->withQueryString();
        }

        $categories = Category::all();
        $colors = Color::all();
        $sizes = Size::all();

        return view('Client.Product.ListProductClient', compact(
            'products',
            'categories',
            'colors',
            'sizes',
            'minPrice',
            'maxPrice'
        ));
    }
}
