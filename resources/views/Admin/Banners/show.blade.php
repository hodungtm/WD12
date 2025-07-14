@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-30">
            <div class="title-box flex items-center gap10">
                <i class="icon-image" style="font-size: 32px; color: #1abc9c;"></i>
                <div>
                    <h3 style="margin-bottom:2px;">Chi tiết Banner</h3>
                    <div class="body-text text-muted" style="font-size:15px;">Thông tin chi tiết banner quảng cáo</div>
                </div>
            </div>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><a href="{{ route('admin.banners.index') }}"><div class="text-tiny">Banner</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Chi tiết</div></li>
            </ul>
        </div>
        {{-- Box: Thông tin banner --}}
        <div class="wg-box mb-30">
            <div class="title-box">
                <i class="icon-image"></i>
                <div class="body-text">Thông tin banner</div>
            </div>
            <div class="flex flex-wrap gap20 mb-4">
                <div class="w-full">
                    <label class="body-title">Tiêu đề</label>
                    <div class="body-text" style="font-size: 16px; color: #333; font-weight: 500;">{{ $banner->tieu_de }}</div>
                </div>
                <div class="w-full">
                    <label class="body-title">Nội dung</label>
                    <div class="body-text" style="font-size: 15px; color: #555; line-height: 1.6; background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #1abc9c;">{!! nl2br(e($banner->noi_dung)) !!}</div>
                </div>
                <div class="w-full md:w-1/2">
                    <label class="body-title">Loại banner</label>
                    <div class="body-text" style="font-size: 16px; color: #333; font-weight: 500;">{{ $banner->loai_banner }}</div>
                </div>
                <div class="w-full md:w-1/2">
                    <label class="body-title">Trạng thái</label>
                    <div class="{{ $banner->trang_thai === 'hien' ? 'block-available' : 'block-stock' }} bg-1 fw-7" style="display: inline-block; min-width: 80px; text-align: center; border-radius: 8px; padding: 6px 18px; font-size: 15px; font-weight: 600; background: #f3f7f6; color: {{ $banner->trang_thai === 'hien' ? '#1abc9c' : '#e67e22' }}; letter-spacing: 0.5px; vertical-align: middle; margin-top: 2px;">
                        {{ $banner->trang_thai === 'hien' ? 'Hiển thị' : 'Ẩn' }}
                    </div>
                </div>
            </div>
        </div>
        {{-- Box: Ảnh banner --}}
        <div class="wg-box mb-30">
            <div class="title-box">
                <i class="icon-image"></i>
                <div class="body-text">Ảnh banner</div>
            </div>
            <div class="flex gap10 flex-wrap">
                @if ($banner->hinhAnhBanner && $banner->hinhAnhBanner->count())
                    @foreach ($banner->hinhAnhBanner as $img)
                        <img src="{{ asset('storage/' . $img->hinh_anh) }}" alt="ảnh banner" style="max-width: 120px; max-height: 120px; border-radius: 10px; border:1.5px solid #eee; margin-bottom: 8px; transition:0.2s; cursor:pointer;" onmouseover="this.style.transform='scale(1.12)'" onmouseout="this.style.transform='scale(1)'">
                    @endforeach
                @else
                    <span class="text-muted">Chưa có ảnh</span>
                @endif
            </div>
        </div>
        {{-- Box: Thông tin hệ thống --}}
        <div class="wg-box mb-30">
            <div class="title-box">
                <i class="icon-info"></i>
                <div class="body-text">Thông tin hệ thống</div>
            </div>
            <div class="flex flex-wrap gap20 mb-4">
                <div class="w-full md:w-1/2">
                    <label class="body-title">Ngày tạo:</label>
                    <span class="body-text" style="font-weight: 500;">{{ $banner->created_at ? $banner->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                </div>
                <div class="w-full md:w-1/2">
                    <label class="body-title">Cập nhật lần cuối:</label>
                    <span class="body-text" style="font-weight: 500;">{{ $banner->updated_at ? $banner->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                </div>
            </div>
        </div>
            <div class="flex justify-start gap-3 mt-4">
                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="tf-button btn-sm w-auto px-3 py-2">
                    <i class="icon-edit-3"></i> Chỉnh sửa
                </a>
                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa banner này không?');" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="tf-button style-3 btn-sm w-auto px-3 py-2" style="color: red">
                        <i class="icon-trash"></i> Xóa
                    </button>
                </form>
                <a href="{{ route('admin.banners.index') }}" class="tf-button style-1 btn-sm w-auto px-3 py-2">
                    <i class="icon-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
