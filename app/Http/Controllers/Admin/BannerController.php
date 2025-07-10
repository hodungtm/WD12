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
    // Lấy giá trị từ ô tìm kiếm và lọc loại banner nếu có
    $search = $request->input('search');
    $loaiBanner = $request->input('loai_banner');

    // Query banner cùng mối quan hệ hình ảnh
    $query = Banner::with('hinhAnhBanner');

    // Nếu có tìm kiếm thì lọc theo tiêu đề
    if (!empty($search)) {
        $query->where('tieu_de', 'like', '%' . $search . '%');
    }

    // Nếu có lọc theo loại banner thì thêm điều kiện
    if (!empty($loaiBanner)) {
        $query->where('loai_banner', $loaiBanner);
    }

    // Sắp xếp mới nhất và phân trang, giữ lại query string khi chuyển trang
    $banners = $query->orderBy('id', 'desc')->paginate(5)->withQueryString();

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


    public function update(Request $request, string $id)
{
    $banner = Banner::findOrFail($id);

    // Cập nhật thông tin banner
    $banner->update([
        'tieu_de'    => $request->tieu_de,
        'noi_dung'   => $request->noi_dung,
        'loai_banner'=> $request->loai_banner,
        'trang_thai' => $request->trang_thai,
    ]);

    // XÓA HÌNH ẢNH ĐƯỢC ĐÁNH DẤU
    if ($request->has('delete_images')) {
        foreach ($request->delete_images as $imageId) {
            $image = $banner->hinhAnhBanner()->find($imageId);
            if ($image) {
                // Xóa file trên storage
                Storage::disk('public')->delete($image->hinh_anh);
                // Xóa bản ghi trong database
                $image->delete();
            }
        }
    }

    // THÊM ẢNH MỚI (nếu có)
    if ($request->hasFile('list_image')) {
        foreach ($request->file('list_image') as $image) {
            if ($image) {
                $path = $image->store("uploads/hinhanhbanner/id_$id", 'public');
                $banner->hinhAnhBanner()->create([
                    'hinh_anh' => $path,
                ]);
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
