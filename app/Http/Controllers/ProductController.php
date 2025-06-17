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
            // 'quantity' => 'required|integer|min:0', // XÓA CỘT NÀY NẾU NÓ THUỘC VỀ PRODUCT CHÍNH VÀ BẠN ĐÃ CHUYỂN NÓ XUỐNG VARIANT
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',

            'variants' => 'nullable|array',
            'variants.*.sku' => 'required|string|distinct',
            'variants.*.price' => 'required|numeric',
            'variants.*.sale_price' => 'nullable|numeric',
            'variants.*.quantity' => 'required|integer|min:0', // THÊM VALIDATION CHO QUANTITY CỦA BIẾN THỂ
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
            'quantity' => 0, // Đặt quantity của product chính là 0 hoặc null nếu bạn không dùng nó nữa.
                             // Hoặc bỏ luôn dòng này nếu bạn đã xóa cột quantity khỏi bảng products.
            'brand_id' => $request->input('brand_id'),
            'category_id' => $request->input('category_id'),
            'image' => $imagePath,
        ]);

        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                $variantImagePath = null;
                if (isset($variantData['image']) && $variantData['image'] instanceof \Illuminate\Http\UploadedFile) {
                    $variantImagePath = $variantData['image']->store('variants', 'public');
                }

                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'sale_price' => $variantData['sale_price'] ?? null,
                    'quantity' => $variantData['quantity'], // LƯU QUANTITY CỦA BIẾN THỂ VÀO ĐÂY
                    'stock_status' => $variantData['stock_status'] ?? null,
                    'description' => $variantData['description'] ?? null,
                    'image' => $variantImagePath,
                    'attribute_text' => $variantData['attribute_text'] ?? null,
                ]);

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

  public function edit(Product $product)
{
    $brands = Brand::all();
    $categories = Category::all();
    $attributes = Attribute::with('values')->get();

    // Tải eager load attributeValues cho mỗi biến thể
    $product->load('variants.attributeValues'); // Đây là điều quan trọng!

    return view('admin.products.edit', compact('product', 'brands', 'categories', 'attributes'));
}
    /**
     * Cập nhật sản phẩm.
     */
    public function update(Request $request, Product $product)
    {
        // Debugging: Kiểm tra dữ liệu request trước khi validate
        // dd($request->all());

        $request->validate([
            'name' => 'required|string|unique:products,name,' . $product->id, // Đảm bảo name là duy nhất ngoại trừ chính sản phẩm này
            'price' => 'required|numeric|min:0', // Sử dụng numeric cho giá, có thể có số thập phân
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048', // Quy tắc cho hình ảnh sản phẩm chính

            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id', // ID của biến thể nếu là cập nhật
            'variants.*.sku' => 'required|string|distinct', // SKU phải là duy nhất trong các biến thể
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|lt:variants.*.price', // Giá khuyến mãi phải nhỏ hơn giá thường
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.stock_status' => 'nullable|string|in:in_stock,out_of_stock', // Chỉ cho phép các giá trị này
            'variants.*.description' => 'nullable|string|max:500', // Giới hạn độ dài mô tả
            // Quan trọng: Sử dụng `file` thay vì `image` trong validation nếu bạn đang gửi tệp
            'variants.*.image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'variants.*.attribute_text' => 'nullable|string|max:255',
            'variants.*.attribute_value_ids' => 'nullable|array',
            'variants.*.attribute_value_ids.*' => 'integer|exists:attribute_values,id', // Đảm bảo ID giá trị thuộc tính tồn tại
        ]);

        // Xử lý hình ảnh sản phẩm chính
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        } elseif ($request->has('image_remove')) { // Một trường ẩn nếu bạn muốn người dùng có thể xóa ảnh chính
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
                $imagePath = null;
            }
        }

        // Cập nhật thông tin sản phẩm chính
        $product->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'brand_id' => $request->input('brand_id'),
            'category_id' => $request->input('category_id'),
            'image' => $imagePath,
        ]);

        // Lấy IDs của các biến thể hiện có trong DB
        $currentVariantIdsInDb = $product->variants->pluck('id')->toArray();
        // Lấy IDs của các biến thể được gửi từ request
        $submittedVariantIds = collect($request->input('variants', []))->pluck('id')->filter()->toArray();

        // Xóa các biến thể không còn tồn tại trong request
        $variantsToDelete = array_diff($currentVariantIdsInDb, $submittedVariantIds);
        foreach ($variantsToDelete as $variantIdToDelete) {
            $variantToDelete = ProductVariant::find($variantIdToDelete);
            if ($variantToDelete) {
                if ($variantToDelete->image) {
                    Storage::disk('public')->delete($variantToDelete->image);
                }
                $variantToDelete->attributeValues()->detach(); // Gỡ bỏ các liên kết thuộc tính
                $variantToDelete->delete();
            }
        }

        // Cập nhật hoặc tạo mới các biến thể
        if ($request->has('variants')) {
            foreach ($request->variants as $index => $variantData) {
                $variantImageFile = $request->file("variants.{$index}.image"); // Truy cập tệp hình ảnh biến thể
                $currentVariantImagePath = $variantData['image'] ?? null; // Đường dẫn ảnh hiện tại từ trường ẩn

                $variant = null;
                if (isset($variantData['id']) && $variantData['id']) {
                    // Cập nhật biến thể hiện có
                    $variant = ProductVariant::find($variantData['id']);
                }

                $variantFinalImagePath = $currentVariantImagePath; // Mặc định giữ ảnh cũ

                // Nếu có tệp mới được tải lên
                if ($variantImageFile && $variantImageFile->isValid()) {
                    // Xóa ảnh cũ nếu tồn tại và có ảnh mới tải lên
                    if ($variant && $variant->image) {
                        Storage::disk('public')->delete($variant->image);
                    }
                    $variantFinalImagePath = $variantImageFile->store('product_variants', 'public');
                } elseif (isset($variantData['image_remove']) && $variantData['image_remove'] == '1') {
                    // Nếu người dùng đã chọn xóa ảnh biến thể (ví dụ: thông qua một checkbox ẩn)
                    if ($variant && $variant->image) {
                        Storage::disk('public')->delete($variant->image);
                    }
                    $variantFinalImagePath = null;
                }

                $dataToSave = [
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                    'sale_price' => $variantData['sale_price'] ?? null,
                    'quantity' => $variantData['quantity'],
                    'stock_status' => $variantData['stock_status'] ?? 'in_stock',
                    'description' => $variantData['description'] ?? null,
                    'image' => $variantFinalImagePath,
                    'attribute_text' => $variantData['attribute_text'] ?? null,
                ];

                if ($variant) {
                    $variant->update($dataToSave);
                } else {
                    $variant = ProductVariant::create($dataToSave);
                }

                // Đồng bộ các giá trị thuộc tính cho biến thể
                // Đảm bảo $variant->attributeValues() là mối quan hệ BelongsToMany đến AttributeValue
                if (isset($variantData['attribute_value_ids'])) {
                    $attributeValueIds = is_array($variantData['attribute_value_ids']) ? $variantData['attribute_value_ids'] : [];
                    $variant->attributeValues()->sync($attributeValueIds);
                } else {
                    $variant->attributeValues()->detach(); // Nếu không có IDs nào được gửi, detach tất cả
                }
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Sản phẩm và các biến thể đã được cập nhật thành công!');
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
        $product = Product::with([
            'variants.attributeValues',
            'reviews', // Tải các đánh giá của sản phẩm
            'reviews.user', // Tải thông tin người dùng đã đánh giá (nếu có mối quan hệ trong Review model)
            'comments', // Tải các bình luận của sản phẩm (nếu có)
            'comments.user' // Tải thông tin người dùng đã bình luận (nếu có mối quan hệ trong Comment model)
        ])->findOrFail($id);

        return view('Admin.Products.show', compact('product'));
    }
}
