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
        $search = $request->input('search');

        $query = Products::with(['category', 'images', 'variants.size', 'variants.color'])
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
        }

        $products = $query->paginate(10)->appends($request->query());

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
        'category_id' => 'required|exists:categories,id',
        'description' => 'required|string|max:1000', // hoặc nullable nếu không bắt buộc
        'images' => 'required|array|min:1',
        'images.*' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
        'sizes' => 'required|array|min:1',
        'colors' => 'required|array|min:1',
    ], [
        'name.required' => 'Vui lòng nhập tên sản phẩm.',
        'category_id.required' => 'Vui lòng chọn danh mục.',
        'category_id.exists' => 'Danh mục không hợp lệ.',
        'description.required' => 'Vui lòng nhập mô tả sản phẩm.',
        'images.required' => 'Vui lòng chọn ít nhất một ảnh.',
        'images.*.image' => 'Tệp tải lên phải là hình ảnh hợp lệ (jpg, png, gif...).',
        'sizes.required' => 'Vui lòng chọn ít nhất một size.',
        'colors.required' => 'Vui lòng chọn ít nhất một màu.',
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
     $error = 'Chỉ có thể xóa sản phẩm Khi Đã Xóa Mềm!';
    if ($product->status == 1) {
        return redirect()->route('products.index')->with('error', 'Chỉ có thể xóa sản phẩm khi đã xóa mềm!');
    }

    $product->delete();

    return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
}


      public function softDelete($id)
{
    $product = Products::findOrFail($id);
    $product->status = 0; // Đổi trạng thái sang "đã xóa" hoặc "ẩn"
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
}
