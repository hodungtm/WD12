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

        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('Admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('Admin.products.create', compact('categories'));
    }


    public function store(Request $request)
    {
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
        $foot_lengths = $request->input('foot_length', []);
        $chest_sizes = $request->input('chest_size', []);
        $waist_sizes = $request->input('waist_size', []);
        $hip_sizes = $request->input('hip_size', []);

        $count = count($sizes);
        for ($i = 0; $i < $count; $i++) {
            $variantData = [
                'product_id'    => $product->id,
                'size'          => $sizes[$i] ?? null,
                'color'         => $colors[$i] ?? null,
                'quantity'      => $quantities[$i] ?? 0,
            ];

            switch ($request->input('type')) {
                case 'shoes':
                    $variantData['foot_length'] = $foot_lengths[$i] ?? null;
                    break;
                case 'shirt':
                    $variantData['chest_size'] = $chest_sizes[$i] ?? null;
                    $variantData['waist_size'] = $waist_sizes[$i] ?? null;
                    break;
                case 'pants':
                    $variantData['waist_size'] = $waist_sizes[$i] ?? null;
                    $variantData['hip_size'] = $hip_sizes[$i] ?? null;
                    break;
            }

            ProductVariant::create($variantData);
        }

        return redirect()->route('Admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }



    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('Admin.products.edit', compact('product', 'categories'));
    }
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->all();


        if ($request->hasFile('image_product')) {

            if ($product->image_product) {
                Storage::disk('public')->delete($product->image_product);
            }
            $data['image_product'] = $request->file('image_product')->store('products', 'public');
        } else {
            $data['image_product'] = $product->image_product;
        }

        $product->update($data);

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
}
