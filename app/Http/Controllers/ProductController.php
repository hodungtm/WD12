<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValueProductVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
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
        $attributes = Attribute::with('values')->get();
        return view('admin.products.create', compact('brands', 'attributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:products,name',
            'price' => 'required|integer',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',

            // validate biến thể nếu có
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required|string|distinct',
            'variants.*.price' => 'required|numeric',
            'variants.*.sale_price' => 'nullable|numeric',
            'variants.*.stock_status' => 'nullable|string',
            'variants.*.description' => 'nullable|string',
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
            'image' => $imagePath,
        ]);

        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                // 1. Tạo variant trước
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'sale_price' => $variantData['sale_price'],
                    'stock_status' => $variantData['stock_status'],
                    'description' => $variantData['description'],
                    'image' => $variantData['image'],
                    'attribute_text' => $variantData['attribute_text'],
                ]);

                // 2. Sau khi tạo xong thì mới có ID để lưu ProductVariantValue
                if (!empty($variantData['attribute_value_ids'])) {
                    foreach ($variantData['attribute_value_ids'] as $valueId) {
                        AttributeValueProductVariant::create([
                            'product_variant_id' => $variant->id, // ✅ Bây giờ $variant đã tồn tại
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
