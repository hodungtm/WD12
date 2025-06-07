<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductDimension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {

        $products = Product::with(['category', 'variants'])->paginate(10);
        return view('Admin.products.index', compact('products'));
    }

    public function create()
    {
         $categories = Category::all();
    $sizes = Size::all();
    $colors = Color::all();

    return view('Admin.products.create', compact('categories', 'sizes', 'colors'));
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'nullable|numeric|min:0',
        'sale_price' => 'nullable|numeric|min:0|lt:price',
        'slug' => 'required|string|unique:products,slug',
        'type' => 'required|string',
        'brand' => 'nullable|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'status' => 'required|in:active,inactive',
        'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'image_path.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {
        // Lưu ảnh chính
        $imagePath = null;
        if ($request->hasFile('image_product')) {
            $imagePath = $request->file('image_product')->store('products', 'public');
        }

        // Tạo sản phẩm chính
        $product = Product::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'type' => $request->type,
            'brand' => $request->brand,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'description' => $request->description,
            'image_product' => $imagePath,
        ]);

        // Lưu ảnh phụ
        if ($request->hasFile('image_path')) {
            foreach ($request->file('image_path') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        // Lưu biến thể
       $sizes = $request->input('selected_size', []);
$colors = $request->input('selected_color', []);
$quantities = $request->input('variant_quantity', []);
$variant_prices = $request->input('variant_price', []);
$variant_sale_prices = $request->input('variant_sale_price', []);

for ($i = 0; $i < count($sizes); $i++) {
    if (!empty($sizes[$i]) && !empty($colors[$i])) {
        // Lấy ID thực tế từ tên (hoặc mã) size & color
        $size = Size::where('name', $sizes[$i])->first();
        $color = Color::where('code', $colors[$i])->first();

        ProductVariant::create([
            'product_id' => $product->id,
            'size_id' => $size?->id,
            'color_id' => $color?->id,
            'quantity' => $quantities[$i] ?? 0,
            'variant_price' => $variant_prices[$i] ?? 0,
            'variant_sale_price' => $variant_sale_prices[$i] ?? 0,
        ]);
    }
}

        DB::commit();
        return redirect()->route('Admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
    }
}



public function edit($id)
{
    $product = Product::with([
        'variants.color', 
        'variants.size', 
        'category',
        'images'
    ])->findOrFail($id);

    $categories = Category::all();
    $colors = Color::all();
    $sizes = Size::all();

    return view('Admin.Products.edit', compact('product', 'categories', 'colors', 'sizes'));
}

   public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'nullable|numeric|min:0',
        'sale_price' => 'nullable|numeric|min:0|lt:price',
        'slug' => 'required|string|unique:products,slug,' . $id,
        'type' => 'required|string',
        'brand' => 'nullable|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'status' => 'required|in:active,inactive',
        'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'image_path.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string',
    ]);

    DB::beginTransaction();

    try {
        $product = Product::findOrFail($id);

        // Cập nhật ảnh chính
       if ($request->hasFile('image_product')) {
    if ($product->image_product) {
        Storage::disk('public')->delete($product->image_product);
    }
    $imagePath = $request->file('image_product')->store('products', 'public');
    $product->image_product = $imagePath;
}

        // Cập nhật thông tin sản phẩm
        $product->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'type' => $request->type,
            'brand' => $request->brand,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'description' => $request->description,
            'image_product' => $product->image_product, // nếu ảnh có thể thay đổi
        ]);

        // Cập nhật ảnh phụ - xóa ảnh cũ, thêm ảnh mới
        if ($request->hasFile('image_path')) {
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }

            foreach ($request->file('image_path') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        // Cập nhật biến thể
        $product->variants()->delete(); // Xóa toàn bộ biến thể cũ

        $sizes = $request->input('selected_size', []);
        $colors = $request->input('selected_color', []);
        $quantities = $request->input('variant_quantity', []);
        $variant_prices = $request->input('variant_price', []);
        $variant_sale_prices = $request->input('variant_sale_price', []);

        for ($i = 0; $i < count($sizes); $i++) {
            if (!empty($sizes[$i]) && !empty($colors[$i])) {
                $size = Size::where('name', $sizes[$i])->first();
                $color = Color::where('code', $colors[$i])->first();

                ProductVariant::create([
                    'product_id' => $product->id,
                    'size_id' => $size?->id,
                    'color_id' => $color?->id,
                    'quantity' => $quantities[$i] ?? 0,
                    'variant_price' => $variant_prices[$i] ?? 0,
                    'variant_sale_price' => $variant_sale_prices[$i] ?? 0,
                ]);
            }
        }

        DB::commit();
        return redirect()->route('Admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
    }
}


    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);


        if ($product->image_product) {
            Storage::disk('public')->delete($product->image_product);
        }

        $product->delete();

        return redirect()->route('Admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'images', 'variants.color', 'variants.size'])->findOrFail($id);
        return view('Admin.products.show', compact('product'));
    }
}
