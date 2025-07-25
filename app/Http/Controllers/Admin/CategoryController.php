<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('keyword');
    $status = $request->input('status'); // Đổi từ 'tinh_trang' thành 'status'
    $sort = $request->input('sort_created'); // Đổi từ 'sort' thành 'sort_created'
    $perPage = $request->input('per_page', 10);

    

    $query = Category::query()
        ->when($search, function ($query, $search) {
            return $query->where('ten_danh_muc', 'like', "%$search%")
                         ->orWhere('mo_ta', 'like', "%$search%");
        })
        ->when($status !== null && $status !== '', function ($query) use ($status) {
            return $query->where('tinh_trang', $status);
        })
        ->when($sort, function ($query, $sort) {
            return $query->orderBy('created_at', $sort);
        }, function ($query) {
            return $query->orderBy('created_at', 'desc'); // Mặc định: mới nhất
        });

    $categories = $query->paginate($perPage)->appends($request->all());
    return view('Admin.categories.index', compact('categories'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ten_danh_muc' => 'required|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
            'anh' => 'required|image|mimes:jpeg,png,bmp,gif,svg|max:2048',
            'tinh_trang' => 'required|in:0,1',
        ], [
            'ten_danh_muc.required' => 'Vui lòng nhập tên danh mục.',
            'ten_danh_muc.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'ten_danh_muc.max' => 'Tên danh mục không được vượt quá 255 ký tự.',

            'mo_ta.string' => 'Mô tả phải là chuỗi ký tự.',
            'mo_ta.max' => 'Mô tả không được vượt quá 1000 ký tự.',

            'anh.required' => 'Vui lòng thêm ảnh cho danh mục.',
            'anh.image' => 'Ảnh phải là file hình ảnh hợp lệ.',
            'anh.mimes' => 'Ảnh chỉ được phép định dạng: jpeg, png, bmp, gif, svg.',
            'anh.max' => 'Ảnh dung lượng tối đa là 2MB.',

            'tinh_trang.required' => 'Vui lòng chọn tình trạng.',
            'tinh_trang.in' => 'Tình trạng không hợp lệ.',
        ]);

        $category = new Category();
        $category->ten_danh_muc = $request->ten_danh_muc;
        $category->mo_ta = $request->mo_ta;
        $category->tinh_trang = $request->tinh_trang;

        if ($request->hasFile('anh')) {
            $path = $request->file('anh')->store('categories', 'public');
            $category->anh = $path;
        }

        $category->save();

        return redirect()->route('Admin.categories.index')->with('success', 'Thêm danh mục thành công');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('Admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'ten_danh_muc' => 'required|string|max:255',
            'mo_ta' => 'nullable|string|max:1000',
            'anh' => 'nullable|image|mimes:jpeg,png,bmp,gif,svg|max:2048',
            'tinh_trang' => 'required|in:0,1',
        ], [
            'ten_danh_muc.required' => 'Vui lòng nhập tên danh mục.',
            'ten_danh_muc.string' => 'Tên danh mục phải là chuỗi ký tự.',
            'ten_danh_muc.max' => 'Tên danh mục không được vượt quá 255 ký tự.',

            'mo_ta.string' => 'Mô tả phải là chuỗi ký tự.',
            'mo_ta.max' => 'Mô tả không được vượt quá 1000 ký tự.',

            'anh.image' => 'Ảnh phải là file hình ảnh hợp lệ.',
            'anh.mimes' => 'Ảnh chỉ được phép định dạng: jpeg, png, bmp, gif, svg.',
            'anh.max' => 'Ảnh dung lượng tối đa là 2MB.',

            'tinh_trang.required' => 'Vui lòng chọn tình trạng.',
            'tinh_trang.in' => 'Tình trạng không hợp lệ.',
        ]);

        $category->ten_danh_muc = $request->ten_danh_muc;
        $category->mo_ta = $request->mo_ta;
        $category->tinh_trang = $request->tinh_trang;

        if ($request->hasFile('anh')) {
            if ($category->anh) {
                Storage::disk('public')->delete($category->anh);
            }
            $path = $request->file('anh')->store('categories', 'public');
            $category->anh = $path;
        }

        $category->save();

        return redirect()->route('Admin.categories.index')->with('success', 'Cập nhật danh mục thành công');
    }
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('Admin.categories.show', compact('category'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('Admin.categories.index')->with('success', 'Đã chuyển danh mục vào thùng rác.');
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(10);
        return view('Admin.categories.trash', compact('categories'));
    }

    public function restore($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('Admin.categories.trash')->with('success', 'Khôi phục danh mục thành công.');
    }

    public function forceDelete($id)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $category->forceDelete();

        return redirect()->route('Admin.categories.trash')->with('success', 'Đã xóa vĩnh viễn danh mục.');
    }
}
