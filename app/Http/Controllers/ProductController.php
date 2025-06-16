<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValueProductVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = Product::with('brand')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    // Hiển thị form thêm sản phẩm
    public function create()
    {
        $brands = Brand::all();
         $categories = Category::all();
        $attributes = Attribute::with('values')->get();
        return view('admin.products.create', compact('brands', 'attributes','categories'));
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:products,name',
        'price' => 'required|integer',
        'quantity' => 'required|integer|min:0',
        'brand_id' => 'required|exists:brands,id',
        'category_id' => 'required|exists:categories,id', // ✅ validate danh mục
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',

        // validate biến thể nếu có
        'variants' => 'nullable|array',
        'variants.*.sku' => 'required|string|distinct',
        'variants.*.price' => 'required|numeric',
        'variants.*.sale_price' => 'nullable|numeric',
        'variants.*.stock_status' => 'nullable|string',
        'variants.*.description' => 'nullable|string',
        'variants.*.image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        'variants.*.attribute_text' => 'nullable|string',
        'variants.*.attribute_value_ids' => 'nullable|array',
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $image->store('products', 'public');
    }

    $product = Product::create([
        'name' => $request->input('name'),
        'slug' => Str::slug($request->input('name')),
        'description' => $request->input('description'),
        'price' => $request->input('price'),
        'quantity' => $request->input('quantity'),
        'brand_id' => $request->input('brand_id'),
        'category_id' => $request->input('category_id'), // ✅ thêm category
        'image' => $imagePath,
    ]);

    if ($request->has('variants')) {
        foreach ($request->variants as $variantData) {
            $variantImagePath = null;

            // ✅ xử lý ảnh biến thể nếu có
            if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                $variantImagePath = $variantData['image']->store('variants', 'public');
            }

            // Tạo biến thể
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantData['sku'],
                'price' => $variantData['price'],
                'sale_price' => $variantData['sale_price'] ?? null,
                'stock_status' => $variantData['stock_status'] ?? null,
                'description' => $variantData['description'] ?? null,
                'image' => $variantImagePath,
                'attribute_text' => $variantData['attribute_text'] ?? null,
            ]);

            // Lưu thuộc tính biến thể
            if (!empty($variantData['attribute_value_ids'])) {
                foreach ($variantData['attribute_value_ids'] as $valueId) {
                    AttributeValueProductVariant::create([
                        'product_variant_id' => $variant->id,
                        'attribute_value_id' => $valueId,
                    ]);
                }
            }
        }
    }

    return redirect()
        ->route('admin.product.index')
        ->with('success', 'Thêm sản phẩm và biến thể thành công');
}




    // Xóa sản phẩm
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.product.index')
            ->with('success', 'Xóa sản phẩm thành công');
    }

    public function show($id)
    {
        $product = Product::with(['variants.attributeValues'])->findOrFail($id);
        return view('Admin.Products.show', compact('product'));
    }
}
