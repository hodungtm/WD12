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

    // RẤT QUAN TRỌNG: Eager load attributeValues cho các biến thể
    $product->load('variants.attributeValues');

    return view('admin.products.edit', compact('product', 'brands', 'categories', 'attributes'));
}
    /**
     * Cập nhật sản phẩm.
     */
     public function update(Request $request, Product $product)
    {
        // Debugging: Kiểm tra dữ liệu request trước khi validate
        // dd($request->all());

        // Important: Adjust validation rules to match the new input names
        $request->validate([
            'name' => 'required|string|unique:products,name,' . $product->id,
            'price' => 'required|numeric|min:0',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048', // Main product image

            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variants,id',
            'variants.*.sku' => 'required|string', // Removed 'distinct' here, as it validates against the whole request, not just new/updated variants. You might need custom validation for cross-variant SKU uniqueness if critical.
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|lt:variants.*.price',
            'variants.*.quantity' => 'required|integer|min:0',
            'variants.*.stock_status' => 'nullable|string|in:in_stock,out_of_stock',
            'variants.*.description' => 'nullable|string|max:500',
            'variants.*.attribute_text' => 'nullable|string|max:255',
            'variants.*.attribute_value_ids' => 'nullable|array',
            'variants.*.attribute_value_ids.*' => 'integer|exists:attribute_values,id',

            // New validation for variant images:
            // new_image_file will only exist if a new file was uploaded via the modal
            'variants.*.new_image_file' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            // image_remove is a flag (0 or 1)
            'variants.*.image_remove' => 'nullable|boolean', // Assuming 0 or 1 from JS
            // image_path_only is the existing path or 'TEMP_NEW_IMAGE'
            'variants.*.image_path_only' => 'nullable|string', // Used for existing path or 'TEMP_NEW_IMAGE'

            // New: Validation for variants_to_delete array
            'variants_to_delete' => 'nullable|array',
            'variants_to_delete.*' => 'integer|exists:product_variants,id',
        ]);

        // Xử lý hình ảnh sản phẩm chính
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        } elseif ($request->has('image_remove')) { // Một trường ẩn nếu bạn muốn người dùng có thể xóa ảnh chính
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
                $imagePath = null;
            }
        }

        // Cập nhật thông tin sản phẩm chính
        $product->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'), // Ensure you have a 'description' input for the main product if needed
            'price' => $request->input('price'),
            'brand_id' => $request->input('brand_id'),
            'category_id' => $request->input('category_id'),
            'image' => $imagePath,
        ]);

        // --- Xử lý xóa biến thể (variants_to_delete) ---
        // Lấy IDs của các biến thể được đánh dấu để xóa từ request
        $variantIdsToDelete = $request->input('variants_to_delete', []);
        foreach ($variantIdsToDelete as $variantId) {
            $variantToDelete = ProductVariant::find($variantId);
            if ($variantToDelete) {
                // Xóa ảnh của biến thể trước khi xóa bản ghi
                if ($variantToDelete->image && Storage::disk('public')->exists($variantToDelete->image)) {
                    Storage::disk('public')->delete($variantToDelete->image);
                }
                $variantToDelete->attributeValues()->detach(); // Gỡ bỏ các liên kết thuộc tính
                $variantToDelete->delete();
            }
        }

        // --- Cập nhật hoặc tạo mới các biến thể ---
        $submittedVariants = $request->input('variants', []);
        $processedVariantIds = []; // To keep track of variants that were updated/created in this loop

        foreach ($submittedVariants as $index => $variantData) {
            $variantId = $variantData['id'] ?? null;
            $variant = null;

            if ($variantId) {
                // Cập nhật biến thể hiện có
                $variant = ProductVariant::find($variantId);
            }

            // Determine the final image path for the variant
            $variantFinalImagePath = $variant ? $variant->image : null; // Start with current DB image or null

            // 1. Check if the 'delete_image' flag is set for this variant
            $deleteImageFlag = isset($variantData['image_remove']) && (bool)$variantData['image_remove'];

            // 2. Check if a new image file was uploaded for this variant
            $newImageFile = $request->file("variants.{$index}.new_image_file");

            if ($deleteImageFlag) {
                // If marked for deletion, delete old image and set path to null
                if ($variant && $variant->image && Storage::disk('public')->exists($variant->image)) {
                    Storage::disk('public')->delete($variant->image);
                }
                $variantFinalImagePath = null;
            } elseif ($newImageFile && $newImageFile->isValid()) {
                // If a new image is provided, delete old one (if exists) and store new one
                if ($variant && $variant->image && Storage::disk('public')->exists($variant->image)) {
                    Storage::disk('public')->delete($variant->image);
                }
                $variantFinalImagePath = $newImageFile->store('product_variants', 'public');
            }
            // If neither delete_image nor new_image_file, the $variantFinalImagePath remains as its original (from $variant->image)


            $dataToSave = [
                'product_id' => $product->id,
                'sku' => $variantData['sku'],
                'price' => $variantData['price'],
                'sale_price' => $variantData['sale_price'] ?? null,
                'quantity' => $variantData['quantity'],
                'stock_status' => $variantData['stock_status'] ?? 'in_stock',
                'description' => $variantData['description'] ?? null,
                'image' => $variantFinalImagePath, // Use the determined image path
                'attribute_text' => $variantData['attribute_text'] ?? null,
            ];

            if ($variant) {
                $variant->update($dataToSave);
            } else {
                $variant = ProductVariant::create($dataToSave);
            }

            // Store the ID of the processed variant
            $processedVariantIds[] = $variant->id;

            // Đồng bộ các giá trị thuộc tính cho biến thể
            if (isset($variantData['attribute_value_ids'])) {
                $attributeValueIds = is_array($variantData['attribute_value_ids']) ? $variantData['attribute_value_ids'] : [];
                $variant->attributeValues()->sync($attributeValueIds);
            } else {
                $variant->attributeValues()->detach(); // Nếu không có IDs nào được gửi, detach tất cả
            }
        }

        // --- Handle variants that were in the DB but not submitted in the request ---
        // This covers cases where variant rows were removed from the table directly without using the 'delete' button
        // First, get all current variant IDs for this product from the database
        $currentProductVariantIds = $product->variants()->pluck('id')->toArray();

        // Find variants that are in the database but were not processed in the current request
        $orphanedVariantIds = array_diff($currentProductVariantIds, $processedVariantIds);

        foreach ($orphanedVariantIds as $orphanedVariantId) {
            $orphanedVariant = ProductVariant::find($orphanedVariantId);
            if ($orphanedVariant) {
                if ($orphanedVariant->image && Storage::disk('public')->exists($orphanedVariant->image)) {
                    Storage::disk('public')->delete($orphanedVariant->image);
                }
                $orphanedVariant->attributeValues()->detach();
                $orphanedVariant->delete();
            }
        }

        return redirect()
            ->route('admin.product.index')
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
            // 'comments.' // Tải thông tin người dùng đã bình luận (nếu có mối quan hệ trong Comment model)
        ])->findOrFail($id);

        return view('Admin.Products.show', compact('product'));
    }
}
