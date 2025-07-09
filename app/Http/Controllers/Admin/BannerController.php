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
    $search = $request->input('search');
    $loaiBanner = $request->input('loai_banner');
    $trangThai = $request->input('trang_thai');
    $sort = strtolower($request->input('sort', 'desc'));
    $perPage = $request->input('per_page', 5);

    // Chỉ chấp nhận 'asc' hoặc 'desc'
    if (!in_array($sort, ['asc', 'desc'])) {
        $sort = 'desc';
    }

    $query = Banner::with('hinhAnhBanner');

    if (!empty($search)) {
        $query->where('tieu_de', 'like', '%' . $search . '%');
    }

    if (!empty($loaiBanner)) {
        $query->where('loai_banner', $loaiBanner);
    }

    if (!empty($trangThai)) {
        $query->where('trang_thai', $trangThai);
    }

    $banners = $query->orderBy('id', $sort)
                     ->paginate($perPage)
                     ->appends($request->query());

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
        return redirect()->route('admin.banners.index')->with('success', 'Thêm banner thành công');
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


    public function update(Request $request, string $id)
{
    $banner = Banner::findOrFail($id);

    // Cập nhật thông tin banner
    $banner->update([
        'tieu_de'     => $request->tieu_de,
        'noi_dung'    => $request->noi_dung,
        'loai_banner' => $request->loai_banner,
        'trang_thai'  => $request->trang_thai,
    ]);

    // XÓA CÁC ẢNH ĐƯỢC ĐÁNH DẤU
    if ($request->has('delete_images') && is_array($request->delete_images)) {
        foreach ($request->delete_images as $imageId) {
            $image = $banner->hinhAnhBanner()->find($imageId);
            if ($image) {
                if (Storage::disk('public')->exists($image->hinh_anh)) {
                    Storage::disk('public')->delete($image->hinh_anh);
                }
                $image->delete();
            }
        }
    }

    // XỬ LÝ THÊM HOẶC THAY ẢNH
    if ($request->hasFile('list_image')) {
        foreach ($request->file('list_image') as $key => $imageFile) {
            if ($imageFile && $imageFile->isValid()) {
                // Nếu key là ID của ảnh cũ => cập nhật ảnh
                if (is_numeric($key)) {
                    $image = $banner->hinhAnhBanner()->find($key);
                    if ($image) {
                        if (Storage::disk('public')->exists($image->hinh_anh)) {
                            Storage::disk('public')->delete($image->hinh_anh);
                        }

                        $path = $imageFile->store("uploads/hinhanhbanner/id_$id", 'public');
                        $image->update(['hinh_anh' => $path]);
                        continue;
                    }
                }

                // Nếu không có ID hoặc không tìm thấy ảnh cũ => thêm mới
                $path = $imageFile->store("uploads/hinhanhbanner/id_$id", 'public');
                $banner->hinhAnhBanner()->create(['hinh_anh' => $path]);
            }
        }
    }

    return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công!');
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

//     public function destroyImage($id)
// {
//     $image = HinhAnhBanner::findOrFail($id);

//     // Xóa file trên storage
//     if ($image->hinh_anh) {
//         Storage::disk('public')->delete($image->hinh_anh);
//     }

//     $image->delete();

//     return redirect()->back()->with('success', 'Xóa ảnh thành công!');
// }


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
