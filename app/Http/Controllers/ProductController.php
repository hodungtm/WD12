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

        return view('Admin.products.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:clothes,shoes,shirt,pants,dress,socks',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'image_product' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_path.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'required|string|max:255',

            'selected_size' => 'required|array',
            'selected_size.*' => 'required|string',
            'selected_color' => 'required|array',
            'selected_color.*' => 'required|string',
            'variant_quantity' => 'required|array',
            'variant_quantity.*' => 'required|integer|min:1',
            'variant_price' => 'required|array',
            'variant_price.*' => 'required|numeric|min:0',
            'variant_sale_price' => 'required|array',
            'variant_sale_price.*' => 'required|numeric|min:0',
            'description' => 'required|string|min:10',
            'slug' => 'required|string|max:255|unique:products,slug',
        ], [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'type.required' => 'Loại sản phẩm không được để trống.',
            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0.',
            'sale_price.required' => 'Vui lòng nhập giá sản phẩm giảm giá.',
            'sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'sale_price.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',
            'image_product.required' => 'Vui lòng chọn ảnh chính.',
            'image_product.image' => 'Ảnh chính phải là file hình ảnh.',
            'image_product.mimes' => 'Ảnh chính phải có định dạng jpeg, png, jpg hoặc gif.',
            'image_product.max' => 'Ảnh chính không được vượt quá 2MB.',
            'image_path.*.image' => 'Ảnh phụ phải là file hình ảnh.',
            'image_path.*.mimes' => 'Ảnh phụ phải có định dạng jpeg, png, jpg hoặc gif.',
            'image_path.*.max' => 'Ảnh phụ không được vượt quá 2MB.',
            'brand.required' => 'Vui lòng nhập thương hiệu.',
            'description.required' => 'Vui lòng nhập mô tả sản phẩm.',
            'description.min' => 'Mô tả sản phẩm phải có ít nhất 10 ký tự.',
            'slug.required' => 'Vui lòng nhập slug.',
            'slug.unique' => 'Slug đã tồn tại.',

            // Thông báo lỗi cho biến thể
            'selected_size.required' => 'Vui lòng nhập size cho biến thể.',
            'selected_size.*.required' => 'Vui lòng nhập size cho từng biến thể.',
            'selected_color.required' => 'Vui lòng nhập màu sắc cho biến thể.',
            'selected_color.*.required' => 'Vui lòng nhập màu sắc cho từng biến thể.',
            'variant_quantity.required' => 'Vui lòng nhập số lượng cho biến thể.',
            'variant_quantity.*.required' => 'Vui lòng nhập số lượng cho từng biến thể.',
            'variant_quantity.*.integer' => 'Số lượng phải là số nguyên.',
            'variant_quantity.*.min' => 'Số lượng phải lớn hơn 0.',
            'variant_price.required' => 'Vui lòng nhập giá cho biến thể.',
            'variant_price.*.required' => 'Vui lòng nhập giá cho từng biến thể.',
            'variant_price.*.numeric' => 'Giá biến thể phải là số.',
            'variant_price.*.min' => 'Giá biến thể phải lớn hơn hoặc bằng 0.',
            'variant_sale_price.required' => 'Vui lòng nhập giá khuyến mãi cho biến thể.',
            'variant_sale_price.*.required' => 'Vui lòng nhập giá khuyến mãi cho từng biến thể.',
            'variant_sale_price.*.numeric' => 'Giá khuyến mãi biến thể phải là số.',
            'variant_sale_price.*.min' => 'Giá khuyến mãi biến thể phải lớn hơn hoặc bằng 0.',
        ]);

        $data = $request->all();


        if ($request->hasFile('image_product')) {
            $path_image = $request->file('image_product')->store('products', 'public');
            $data['image_product'] = $path_image;
        } else {
            $data['image_product'] = '';
        }


        $product = Product::create($data);


        if ($request->hasFile('image_details')) {
            foreach ($request->file('image_details') as $file) {
                $path = $file->store('product_images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }


        $sizes = $request->input('selected_size', []);
        $colors = $request->input('selected_color', []);
        $quantities = $request->input('variant_quantity', []);
        $variant_price = $request->input('variant_price', []);
        $variant_sale_price = $request->input('variant_sale_price', []);

        // Thêm dòng này để kiểm tra:
        if (count($sizes) != count($variant_price) || count($sizes) != count($variant_sale_price)) {
            dd($sizes, $variant_price, $variant_sale_price);
        }

        $count = count($sizes);
        for ($i = 0; $i < $count; $i++) {
            $variantData = [
                'product_id'    => $product->id,
                'size'          => $sizes[$i] ?? '',
                'color'         => $colors[$i] ?? '',
                'quantity'      => $quantities[$i] ?? 0,
                'variant_price' => $variant_price[$i] ?? 0,
                'variant_sale_price' => $variant_sale_price[$i] ?? 0,
            ];

            switch ($request->input('type')) {
                case 'shoes':
                    $variantData['variant_price'] =  $variant_price[$i] ?? null;
                    $variantData['variant_sale_price'] = $variant_sale_price[$i] ?? null;
                    break;
                case 'shirt':
                    $variantData['variant_price'] =  $variant_price[$i] ?? null;
                    $variantData['variant_sale_price'] = $variant_sale_price[$i] ?? null;
                    break;
                case 'pants':
                    $variantData['variant_price'] =  $variant_price[$i] ?? null;
                    $variantData['variant_sale_price'] = $variant_sale_price[$i] ?? null;
                    break;
            }

            ProductVariant::create($variantData);
        }

        return redirect()->route('Admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }



    public function edit(string $id)
    {
        $product = Product::with(['images', 'variants'])->findOrFail($id);
        $categories = Category::all();
        return view('Admin.products.edit', compact('product', 'categories'));
    }
    public function update(Request $request, string $id)
    {
        $product = Product::with(['images', 'variants'])->findOrFail($id);
        // dd($product);
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:clothes,shoes,shirt,pants,dress,socks',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'image_product' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_details.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            // validate biến thể như khi thêm
            'selected_size' => 'required|array',
            'selected_size.*' => 'required|string',
            'selected_color' => 'required|array',
            'selected_color.*' => 'required|string',
            'variant_quantity' => 'required|array',
            'variant_quantity.*' => 'required|integer|min:1',
            'variant_price' => 'required|array',
            'variant_price.*' => 'required|numeric|min:0',
            'variant_sale_price' => 'required|array',
            'variant_sale_price.*' => 'required|numeric|min:0',
        ], [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'type.required' => 'Loại sản phẩm không được để trống.',
            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'price.min' => 'Giá sản phẩm phải lớn hơn hoặc bằng 0.',
            'sale_price.required' => 'Vui lòng nhập giá sản phẩm giảm giá.',
            'sale_price.numeric' => 'Giá khuyến mãi phải là số.',
            'sale_price.min' => 'Giá khuyến mãi phải lớn hơn hoặc bằng 0.',
            'image_product.required' => 'Vui lòng chọn ảnh chính.',
            'image_product.image' => 'Ảnh chính phải là file hình ảnh.',
            'image_product.mimes' => 'Ảnh chính phải có định dạng jpeg, png, jpg hoặc gif.',
            'image_product.max' => 'Ảnh chính không được vượt quá 2MB.',
            'image_path.*.image' => 'Ảnh phụ phải là file hình ảnh.',
            'image_path.*.mimes' => 'Ảnh phụ phải có định dạng jpeg, png, jpg hoặc gif.',
            'image_path.*.max' => 'Ảnh phụ không được vượt quá 2MB.',
            'brand.required' => 'Vui lòng nhập thương hiệu.',
            'description.required' => 'Vui lòng nhập mô tả sản phẩm.',
            'description.min' => 'Mô tả sản phẩm phải có ít nhất 10 ký tự.',

            // Thông báo lỗi cho biến thể
            'selected_size.required' => 'Vui lòng nhập size cho biến thể.',
            'selected_size.*.required' => 'Vui lòng nhập size cho từng biến thể.',
            'selected_color.required' => 'Vui lòng nhập màu sắc cho biến thể.',
            'selected_color.*.required' => 'Vui lòng nhập màu sắc cho từng biến thể.',
            'variant_quantity.required' => 'Vui lòng nhập số lượng cho biến thể.',
            'variant_quantity.*.required' => 'Vui lòng nhập số lượng cho từng biến thể.',
            'variant_quantity.*.integer' => 'Số lượng phải là số nguyên.',
            'variant_quantity.*.min' => 'Số lượng phải lớn hơn 0.',
            'variant_price.required' => 'Vui lòng nhập giá cho biến thể.',
            'variant_price.*.required' => 'Vui lòng nhập giá cho từng biến thể.',
            'variant_price.*.numeric' => 'Giá biến thể phải là số.',
            'variant_price.*.min' => 'Giá biến thể phải lớn hơn hoặc bằng 0.',
            'variant_sale_price.required' => 'Vui lòng nhập giá khuyến mãi cho biến thể.',
            'variant_sale_price.*.required' => 'Vui lòng nhập giá khuyến mãi cho từng biến thể.',
            'variant_sale_price.*.numeric' => 'Giá khuyến mãi biến thể phải là số.',
            'variant_sale_price.*.min' => 'Giá khuyến mãi biến thể phải lớn hơn hoặc bằng 0.',
        ]);

        $data = $request->all();

        // Xử lý ảnh chính
        if ($request->hasFile('image_product')) {
            if ($product->image_product) {
                Storage::disk('public')->delete($product->image_product);
            }
            $data['image_product'] = $request->file('image_product')->store('products', 'public');
        } else {
            $data['image_product'] = $product->image_product;
        }

        $product->update($data);

        // Xử lý ảnh phụ (nếu có upload mới)
        if ($request->hasFile('image_details')) {
            // Xóa ảnh phụ cũ
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
            // Lưu ảnh phụ mới
            foreach ($request->file('image_details') as $file) {
                $path = $file->store('product_images', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                ]);
            }
        }

        // Xử lý biến thể: Xóa hết biến thể cũ, thêm mới lại
        $product->variants()->delete();
        $sizes = $request->input('selected_size', []);
        $colors = $request->input('selected_color', []);
        $quantities = $request->input('variant_quantity', []);
        $variant_price = $request->input('variant_price', []);
        // dd($variant_price);
        $variant_sale_price = $request->input('variant_sale_price', []);
        $count = count($sizes);
        for ($i = 0; $i < $count; $i++) {
            $variantData = [
                'product_id'    => $product->id,
                'size'          => $sizes[$i] ?? '',
                'color'         => $colors[$i] ?? '',
                'quantity'      => $quantities[$i] ?? 0,
                'variant_price' => $variant_price[$i] ?? 0,
                'variant_sale_price' => $variant_sale_price[$i] ?? 0,
            ];
            ProductVariant::create($variantData);
        }

        return redirect()->route('Admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
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
        $product = Product::with(['category', 'images', 'variants'])->findOrFail($id);
        return view('Admin.products.show', compact('product'));
    }
}
