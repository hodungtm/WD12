<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $query = Products::with(['category', 'images', 'variants.size', 'variants.color']);

        // Lọc theo từ khóa
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                  ->orWhereHas('category', function ($q2) use ($keyword) {
                      $q2->where('ten_danh_muc', 'like', "%$keyword%");
                  });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Phân trang
        $perPage = $request->input('per_page', 10);
        $products = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function admin()
    {
        return view('admin.home');
    }

    public function create()
    {
        $categories = Category::all();
        $sizes = Size::all();
        $colors = Color::all();

        return view('admin.products.create', compact('categories', 'sizes', 'colors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variant_sizes' => 'required|array',
            'variant_colors' => 'required|array',
            'variant_prices' => 'required|array',
            'variant_sale_prices' => 'required|array',
            'variant_quantities' => 'required|array',
        ]);

        $product_code = $this->generateProductCode();

        $product = Products::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'product_code' => $product_code,
            'status' => $request->input('status', 1),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imagePath = $imageFile->store('product_images', 'public');
                $product->images()->create(['image' => $imagePath]);
            }
        }

        for ($i = 0; $i < count($request->variant_sizes); $i++) {
            ProductVariant::create([
                'product_id' => $product->id,
                'size_id' => $request->variant_sizes[$i],
                'color_id' => $request->variant_colors[$i],
                'price' => $request->variant_prices[$i],
                'sale_price' => $request->variant_sale_prices[$i],
                'quantity' => $request->variant_quantities[$i],
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    private function generateProductCode()
    {
        do {
            $code = str_pad(rand(0, 999999), rand(4, 6), '0', STR_PAD_LEFT);
        } while (Products::where('product_code', $code)->exists());

        return $code;
    }

    public function show($id)
    {
        $product = Products::with(['category', 'images', 'variants.size', 'variants.color'])->findOrFail($id);
        $minPrice = $product->variants->isNotEmpty() ? $product->variants->min('price') : null;

        $relatedProducts = Products::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->latest()->take(10)->get();

        return view('admin.products.show', compact('product', 'relatedProducts', 'minPrice'));
    }

    public function edit($id)
    {
        $product = Products::with(['images', 'variants.size', 'variants.color'])->findOrFail($id);

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::all(),
            'sizes' => Size::all(),
            'colors' => Color::all(),
            'selectedSizes' => $product->variants->pluck('size_id')->unique()->toArray(),
            'selectedColors' => $product->variants->pluck('color_id')->unique()->toArray(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:0,1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variant_sizes' => 'required|array',
            'variant_colors' => 'required|array',
            'variant_prices' => 'required|array',
            'variant_sale_prices' => 'required|array',
            'variant_quantities' => 'required|array',
        ]);

        foreach ($validated['variant_prices'] as $index => $price) {
            $sale = $validated['variant_sale_prices'][$index];
            if ($sale > $price) {
                return redirect()->back()->withInput()->withErrors([
                    'variant_sale_prices.' . $index => 'Giá sale không được cao hơn giá gốc (biến thể ' . ($index + 1) . ').'
                ]);
            }
        }

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'status' => $validated['status'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('product_images', 'public');
                $product->images()->create(['image' => $path]);
            }
        }

        $product->variants()->delete();

        for ($i = 0; $i < count($validated['variant_sizes']); $i++) {
            $product->variants()->create([
                'size_id' => $validated['variant_sizes'][$i],
                'color_id' => $validated['variant_colors'][$i],
                'price' => $validated['variant_prices'][$i],
                'sale_price' => $validated['variant_sale_prices'][$i],
                'quantity' => $validated['variant_quantities'][$i],
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = Products::findOrFail($id);

        if ($product->status == 1) {
            return redirect()->route('products.index')->with('error', 'Chỉ có thể xóa sản phẩm khi đã xóa mềm!');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    public function softDelete($id)
    {
        $product = Products::findOrFail($id);
        $product->status = 0;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Xóa mềm Thành Công.');
    }

    public function destroyImage($id)
    {
        $image = ProductImage::findOrFail($id);

        if (Storage::exists('public/' . $image->image)) {
            Storage::delete('public/' . $image->image);
        }

        $image->delete();

        return back()->with('success', 'Ảnh đã được xóa');
    }

    public function softDeleteSelected(Request $request)
    {
        $ids = $request->input('selected_products');

        if (empty($ids) || !is_array($ids)) {
            return redirect()->route('products.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để xóa.');
        }

        $updated = Products::whereIn('id', $ids)->update(['status' => 0]);

        return redirect()->route('products.index')->with('success', "Đã xóa mềm {$updated} sản phẩm.");
    }
}
