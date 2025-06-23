<?php

namespace App\Http\Controllers;
use App\Models\Size;
use App\Models\Color;
use App\Models\Category;
use App\Models\Products;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Lấy từ khóa tìm kiếm từ input
    $search = $request->input('search');
    
    // Query base
    $query = Products::with(['category', 'images', 'variants.size', 'variants.color'])
                ->orderBy('created_at', 'desc');
    
    // Nếu có từ khóa tìm kiếm, thêm điều kiện vào query
    if ($search) {
        $query->where('name', 'like', '%' . $search . '%')
              ->orWhereHas('category', function ($q) use ($search) {
                  $q->where('name', 'like', '%' . $search . '%');
              });
    }

    // Paginate, mặc định 10/sp 1 trang
    $products = $query->paginate($request->input('per_page', 10))
                      ->appends($request->query()); // giữ lại query search khi chuyển trang

    // Truyền dữ liệu vào view
    return view('admin.products.index', compact('products'));
}


    

    public function admin()
    {
        return view('admin.home');

    }

    /**
     * Show the form for creating a new resource.
     */
  public function create()
{
    $categories = Category::all();
    $sizes = Size::all();
    $colors = Color::all();

    return view('admin.products.create', compact('categories', 'sizes', 'colors'));
}


    /**
     * Store a newly created resource in storage.
     */
        public function store(Request $request)
{
    // Validate
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required',
        'category_id' => 'required|exists:categories,id',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'variant_sizes' => 'required|array',
        'variant_colors' => 'required|array',
        'variant_prices' => 'required|array',
        'variant_quantities' => 'required|array',
    ]);

    // 1️⃣ Lưu sản phẩm chính
    $product = Products::create([
        'name' => $request->name,
        'description' => $request->description,
        'category_id' => $request->category_id,
    ]);

    // 2️⃣ Lưu ảnh
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $imagePath = $imageFile->store('product_images', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $imagePath,
            ]);
        }
    }

    // 3️⃣ Lưu biến thể (variant)
    $sizes = $request->input('variant_sizes');
    $colors = $request->input('variant_colors');
    $prices = $request->input('variant_prices');
    $quantities = $request->input('variant_quantities');

    for ($i = 0; $i < count($sizes); $i++) {
        ProductVariant::create([
            'product_id' => $product->id,
            'size_id' => $sizes[$i],
            'color_id' => $colors[$i],
            'price' => $prices[$i],
            'quantity' => $quantities[$i],
        ]);
    }

    return redirect()->route('products.index')->with('success', 'Sản phẩm và các biến thể đã được thêm thành công!');
}

   public function show($id)
{
    $product = Products::with([
        'category',
        'images',
        'variants.size',
        'variants.color'
    ])->findOrFail($id);

    // Nếu sản phẩm có variants, lấy giá nhỏ nhất
    if ($product->variants->isNotEmpty()) {
        $minPrice = $product->variants->min('price');
    } else {
        $minPrice = null; // hoặc 0
    }

    $relatedProducts = Products::where('category_id', $product->category_id)
        ->where('id', '!=', $id)
        ->latest()
        ->take(10)
        ->get();

    if ($relatedProducts->isEmpty()) {
        $relatedProducts = null;
    }

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

    // Validate dữ liệu
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'variant_sizes' => 'required|array',
        'variant_colors' => 'required|array',
        'variant_prices' => 'required|array',
        'variant_quantities' => 'required|array',
    ]);

    // Cập nhật sản phẩm
    $product->update([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'category_id' => $validated['category_id'],
    ]);

    // Upload hình ảnh mới (nếu có)
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('products', 'public');
            $product->images()->create(['image' => $path]);
        }
    }

    // Xoá tất cả biến thể cũ
    $product->variants()->delete();

    // Lưu biến thể mới
    $variants = [];
    for ($i = 0; $i < count($validated['variant_sizes']); $i++) {
        $variants[] = [
            'size_id' => $validated['variant_sizes'][$i],
            'color_id' => $validated['variant_colors'][$i],
            'price' => $validated['variant_prices'][$i],
            'quantity' => $validated['variant_quantities'][$i],
        ];
    }

    $product->variants()->createMany($variants);

    return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công!');
}




    
    public function destroy($id)
    {
        // Tìm sản phẩm theo ID
        $product = Products::findOrFail($id);
    
        // Xóa sản phẩm
        $product->delete();
    
        // Chuyển hướng sau khi xóa thành công
        return redirect()->route('products.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    
    public function destroyImage($id)
    {
        $productImage = ProductImage::findOrFail($id); // Kiểm tra xem sản phẩm có tồn tại không
        
        // Xóa ảnh khỏi storage
        if (Storage::exists('public/' . $productImage->image)) {
            Storage::delete('public/' . $productImage->image);
        }
        
        // Xóa bản ghi khỏi database
        $productImage->delete();
        
        return redirect()->back()->with('success', 'Ảnh đã được xóa');
    }
    
}
