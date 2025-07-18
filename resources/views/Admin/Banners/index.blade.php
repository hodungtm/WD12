@extends('Admin.Layouts.AdminLayout')
@section('title', 'Danh sách banner | Quản trị Admin')

@section('main')
<div class="main-content-inner">
  <div class="main-content-wrap">
    
    <div class="flex items-center flex-wrap justify-between gap20 mb-30">
      <div class="title-box flex items-center gap10">
        <i class="icon-image" style="font-size: 32px; color: #1abc9c;"></i>
        <div>
          <h3 style="margin-bottom:2px;">Quản lý Banner</h3>
          <div class="body-text text-muted" style="font-size:15px;">Quản lý các banner quảng cáo trên hệ thống</div>
        </div>
      </div>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="{{ route('admin.dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Banner</div></li>
      </ul>
    </div>
    @if (session('success'))
                <div class="alert"
                    style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">
                    <i class="icon-check-circle" style="margin-right: 6px;"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert"
                    style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">
                    <i class="icon-alert-triangle" style="margin-right: 6px;"></i> {{ session('error') }}
                </div>
            @endif
    <div class="wg-box">
      <div class="title-box mb-2">
        <i class="icon-book-open"></i>
        <div class="body-text">Tìm kiếm banner theo tiêu đề, loại hoặc trạng thái.</div>
      </div>
      <div class="flex flex-column gap10 mb-3">
        <form method="GET" action="{{ route('admin.banners.index') }}" class="form-search w-100" style="margin-bottom: 10px;">
          <div class="search-input" style="width: 100%; position: relative;">
            <input type="text" placeholder="Tìm kiếm banner..." name="search" value="{{ request('search') }}" style="width: 100%; min-width: 200px;">
            <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff; position: absolute; right: 5px; top: 50%; transform: translateY(-50%);">
              <i class="icon-search" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
            </button>
          </div>
        </form>
        <div class="flex items-center justify-between gap10 flex-wrap">
          <div class="flex gap10 flex-wrap align-items-center">
            <form method="GET" action="{{ route('admin.banners.index') }}" class="flex gap10 flex-wrap align-items-center" style="margin-bottom: 0;">
              <input type="hidden" name="search" value="{{ request('search') }}">
              <select name="loai_banner" class="form-select" style="width: 150px;">
                <option value="">-- Loại banner --</option>
                @foreach($loaiBanners as $loai)
                  <option value="{{ $loai }}" {{ request('loai_banner') == $loai ? 'selected' : '' }}>{{ $loai }}</option>
                @endforeach
              </select>
              <select name="trang_thai" class="form-select" style="width: 120px;">
                <option value="">-- Trạng thái --</option>
                <option value="hien" {{ request('trang_thai') == 'hien' ? 'selected' : '' }}>Hiện</option>
                <option value="an" {{ request('trang_thai') == 'an' ? 'selected' : '' }}>Ẩn</option>
              </select>
              <select name="per_page" class="form-select" style="width: 110px;">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 dòng</option>
                <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 dòng</option>
                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 dòng</option>
                <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100 dòng</option>
              </select>
              <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff;">
                <i class="icon-filter" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
              </button>
            </form>
          </div>
          <div class="flex gap10">
            <a class="tf-button style-1 w200" href="{{ route('admin.banners.create') }}">
              <i class="icon-plus"></i> Thêm banner
            </a>
          </div>
        </div>
      </div>
      <div class="wg-table table-product-list mt-3">
        <ul class="table-title flex mb-14" style="gap:2px;">
          <li style="flex-basis: 60px; text-align:center;"><div class="body-title">ID</div></li>
          <li style="flex-basis: 220px; text-align:center;"><div class="body-title">Tiêu đề</div></li>
          <li style="flex-basis: 180px; text-align:center;"><div class="body-title">Ảnh</div></li>
          <li style="flex-basis: 140px; text-align:center;"><div class="body-title">Loại banner</div></li>
          <li style="flex-basis: 120px; text-align:center;"><div class="body-title">Trạng thái</div></li>
          <li style="flex-basis: 140px; text-align:center;"><div class="body-title">Hành động</div></li>
        </ul>
        <ul class="flex flex-column">
          @forelse ($banners as $banner)
          <li class="wg-product item-row" style="gap:2px; align-items:center; border-bottom:1px solid #f0f0f0;">
            <div class="body-text mt-4" style="flex-basis: 60px; text-align:center;">#{{ $banner->id }}</div>
            <div class="body-text mt-4" style="flex-basis: 220px; text-align:center;">{{ $banner->tieu_de }}</div>
            <div style="flex-basis: 180px; text-align:center;">
              @if ($banner->hinhAnhBanner && $banner->hinhAnhBanner->count())
                <div class="flex gap5 justify-center flex-wrap">
                  @foreach ($banner->hinhAnhBanner as $img)
                    <img src="{{ asset('storage/' . $img->hinh_anh) }}" alt="ảnh banner" style="max-width: 60px; max-height: 60px; border-radius: 8px; margin-right: 3px; margin-bottom: 2px; border:1px solid #eee; transition:0.2s; cursor:pointer;" onmouseover="this.style.transform='scale(1.12)'" onmouseout="this.style.transform='scale(1)'">
                  @endforeach
                </div>
              @else
                <span class="text-muted">Chưa có ảnh</span>
              @endif
            </div>
            <div class="body-text mt-4" style="flex-basis: 140px; text-align:center;">{{ $banner->loai_banner }}</div>
            <div style="flex-basis: 120px; text-align:center;">
              <div class="{{ $banner->trang_thai === 'hien' ? 'block-available' : 'block-stock' }} bg-1 fw-7" style="display: inline-block; min-width: 80px; text-align: center; border-radius: 8px; padding: 6px 18px; font-size: 15px; font-weight: 600; background: #f3f7f6; color: {{ $banner->trang_thai === 'hien' ? '#1abc9c' : '#e67e22' }}; letter-spacing: 0.5px; vertical-align: middle; margin-top: 2px;">
                {{ $banner->trang_thai === 'hien' ? 'Hiện' : 'Ẩn' }}
              </div>
            </div>
            <div class="list-icon-function flex justify-center gap10" style="flex-basis: 140px;">
              <a href="{{ route('admin.banners.show', $banner->id) }}" class="item eye" title="Xem"><i class="icon-eye"></i></a>
              <a href="{{ route('admin.banners.edit', $banner->id) }}" class="item edit" title="Sửa"><i class="icon-edit-3"></i></a>
              <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa banner này không?');" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" style="color: red" title="Xóa banner">
                  <i class="icon-trash" style="color: red; font-size: 20px;"></i>
                </button>
              </form>
            </div>
          </li>
          @empty
          <li class="text-center text-muted py-3">Chưa có banner nào.</li>
          @endforelse
        </ul>
      </div>
      <div class="divider"></div>
      <div class="flex items-center justify-between flex-wrap gap10">
        <div class="text-tiny">Hiển thị từ {{ $banners->firstItem() }} đến {{ $banners->lastItem() }} trong tổng số {{ $banners->total() }} banner</div>
        {{ $banners->appends(request()->query())->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection
