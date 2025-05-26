<?php

namespace App\Http\Controllers;

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
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('ten_danh_muc', 'like', "%$search%")
                ->orWhere('ma_danh_muc', 'like', "%$search%");
        })->get();
        $trashed = Category::onlyTrashed()->get(); // Lấy danh mục đã xóa mềm
        return view('Admin.categories.index', compact('categories', 'trashed'));
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
            'ma_danh_muc' => 'required|unique:categories,ma_danh_muc',
            'ten_danh_muc' => 'required',
            'anh' => 'nullable|image',
            'tinh_trang' => 'required|in:0,1',
        ]);

        $category = new Category();
        $category->ma_danh_muc = $request->ma_danh_muc;
        $category->ten_danh_muc = $request->ten_danh_muc;
        $category->tinh_trang = $request->tinh_trang;

        if ($request->hasFile('anh')) {
            $path = $request->file('anh')->store('categories', 'public');
            $category->anh = $path;
        }

        $category->save();

        return redirect()->route('Admin.categories.index')->with('success', 'Thêm danh mục thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
            'ma_danh_muc' => 'required|unique:categories,ma_danh_muc,' . $category->id,
            'ten_danh_muc' => 'required',
            'anh' => 'nullable|image',
            'tinh_trang' => 'required|in:0,1',
        ]);

        $category->ma_danh_muc = $request->ma_danh_muc;
        $category->ten_danh_muc = $request->ten_danh_muc;
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete(); // Xóa mềm
        return redirect()->route('Admin.categories.index')->with('success', 'Đã xóa tạm thời danh mục');
    }
    public function trashed(Request $request)
    {
        $search = $request->input('keyword');
        $trashed = Category::onlyTrashed()
            ->when($search, function ($query, $search) {
                return $query->where('ten_danh_muc', 'like', "%$search%")
                    ->orWhere('ma_danh_muc', 'like', "%$search%");
            })
            ->get();
        return view('Admin.categories.trashed', compact('trashed'));
    }

    // Khôi phục danh mục
    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('Admin.categories.trashed')->with('success', 'Khôi phục thành công');
    }

    // Xóa vĩnh viễn danh mục
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);

        if ($category->anh) {
            Storage::disk('public')->delete($category->anh);
        }

        $category->forceDelete();
        return redirect()->route('Admin.categories.trashed')->with('success', 'Đã xóa vĩnh viễn');
    }
}
