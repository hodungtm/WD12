<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannerRequest;
use App\Http\Requests\HinhAnhBanner\ThemHinhAnhBannerRequest;
use App\Models\Banner;
use App\Models\HinhAnhBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        // Lấy giá trị từ ô tìm kiếm nếu có
        $search = $request->input('search');

        // Query banner cùng mối quan hệ hình ảnh
        $query = Banner::with('hinhAnhBanner');

        // Nếu có tìm kiếm thì lọc theo tiêu đề
        if (!empty($search)) {
            $query->where('tieu_de', 'like', '%' . $search . '%');
        }

        // Sắp xếp mới nhất và phân trang
        $banners = $query->orderBy('id', 'desc')->paginate(5);

        // Trả về view và truyền dữ liệu

        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        // Trả về view tạo mới banner

        return view('admin.banners.create');
    }

    public function store(BannerRequest $request)
    {
        $banner = Banner::create($request->only(['tieu_de', 'noi_dung', 'loai_banner', 'trang_thai']));

        if ($request->hasFile('list_image')) {
            foreach ($request->file('list_image') as $image) {
                $path = $image->store("uploads/hinhanhbanner/id_{$banner->id}", 'public');
                $banner->hinhAnhBanner()->create(['hinh_anh' => $path]);
            }
        }
        // dd($banner);
        return redirect()->route('admin.banners.index')->with('success', 'Thêm banner và ảnh thành công');
    }


    public function show($id)
    {
        $banner = Banner::with('hinhAnhBanner')->findOrFail($id);
        // dd($banner);
        return view('admin.banners.show', compact('banner'));
    }

    public function edit($id)
    {
        $banner = Banner::with('hinhAnhBanner')->findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }


    public function update(BannerRequest $request, Banner $banner)
{
    // 1. Cập nhật thông tin cơ bản
    $banner->update($request->only(['tieu_de', 'noi_dung', 'loai_banner', 'trang_thai']));

    // 2. Danh sách ID ảnh cũ còn giữ lại (từ hidden input)
    $imageIdsFromForm = array_keys($request->input('list_image', []));

    // 3. Xóa ảnh cũ không còn trong form
    $banner->hinhAnhBanner->each(function ($image) use ($imageIdsFromForm) {
        if (!in_array($image->id, $imageIdsFromForm)) {
            Storage::disk('public')->delete($image->hinh_anh);
            $image->delete();
        }
    });

    // 4. Thêm ảnh mới
    if ($request->hasFile('list_image')) {
        foreach ($request->file('list_image') as $key => $image) {
            if (is_file($image) && $image->isValid()) {
                $path = $image->store("uploads/hinhanhbanner/id_{$banner->id}", 'public');
                $banner->hinhAnhBanner()->create([
                    'hinh_anh' => $path
                ]);
            }
        }
    }

    return redirect()->route('admin.banners.index')->with('success', 'Cập nhật Banner thành công.');
}



    public function destroy($id)
    {
        $banner = Banner::with('hinhAnhBanner')->findOrFail($id);

        foreach ($banner->hinhAnhBanner as $image) {
            Storage::disk('public')->delete($image->hinh_anh);
            $image->delete();
        }

        Storage::disk('public')->deleteDirectory("uploads/hinhanhbanner/id_{$id}");
        $banner->delete();

        return redirect()->back()->with('success', 'Xóa thành công');
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:hien,an',
        ]);

        $banner = Banner::findOrFail($id);
        $banner->update(['trang_thai' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công'
        ]);
    }

    public function getBannersByType($type)
    {
        $banners = Banner::where('loai_banner', $type)
            ->where('trang_thai', 'hien')
            ->take(3)
            ->get();

        return response()->json(['banners' => $banners]);
    }
}
