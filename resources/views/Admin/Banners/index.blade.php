@extends('Admin.Layouts.AdminLayout')
@section('title', 'Danh sách banner | Quản trị Admin')

@section('main')
    <div class="main-content-inner" style="padding-top: 10px; margin-top: 0;">
        <div class="main-content-wrap" style="padding-top: 0; margin-top: 0;">
            <!-- Tiêu đề + breadcrumb -->
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Danh sách banner</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="#">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Banner</div>
                    </li>
                </ul>

            </div>

            @if (session('success'))
                <div class="alert alert-success" id="success-alert">
                    {{ session('success') }}
                </div>
            @endif


            <div class="wg-box">
                <!-- Dòng hướng dẫn tìm kiếm -->
                <div class="flex items-center gap10 mb-3" style="color:#1abc9c; font-size:16px;">
                    <i class="icon-coffee" style="font-size:20px;"></i>
                    <span>Tip: Bạn có thể tìm kiếm banner theo <b>tiêu đề</b> hoặc dùng <b>bộ lọc</b> để lọc nhanh banner.</span>
                </div>

                <form method="GET" action="{{ route('admin.banners.index') }}"
                    class="flex flex-wrap gap-3 mb-4 align-items-center">
                    <div class="flex items-center gap10">
                        <label for="per_page" class="text-tiny" style="color:#222;">Hiển thị</label>
                        <select name="per_page" id="per_page" class="form-select" style="width: 70px;"
                            onchange="this.form.submit()">
                            @foreach ([5, 10, 20] as $num)
                                <option value="{{ $num }}" {{ request('per_page', 5) == $num ? 'selected' : '' }}>
                                    {{ $num }}</option>
                            @endforeach
                        </select>
                        <span class="text-tiny" style="color:#222;">dòng</span>
                    </div>
                    <input type="text" name="search" class="form-control" placeholder="Tìm tiêu đề..."
                        value="{{ request('search') }}" style="min-width: 200px; max-width: 300px;">
                    <select name="loai_banner" class="form-select" style="width: 150px;">
                        <option value="">Loại banner</option>
                        <option value="slider" {{ request('loai_banner') == 'slider' ? 'selected' : '' }}>Slider</option>
                        <option value="footer" {{ request('loai_banner') == 'footer' ? 'selected' : '' }}>Footer</option>
                    </select>
                    <select name="trang_thai" class="form-select" style="width: 150px;">
                        <option value="">Trạng thái </option>
                        <option value="hien" {{ request('trang_thai') == 'hien' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="an" {{ request('trang_thai') == 'an' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                    <button class="tf-button style-1 " type="submit" style="padding: 4px 12px; font-size: 10px;">
                        <i class="icon-search me-1" style="font-size: 10px;"></i> Tìm kiếm
                    </button>
                    <a href="{{ route('admin.banners.create') }}" class="tf-button style-1"
                        style="padding: 4px 12px; font-size: 10px;">
                        <i class="icon-plus me-1" style="font-size: 10px;"></i> Thêm banner
                    </a>
                </form>

                <div class="wg-table table-all-user">
                    <!-- Tiêu đề bảng -->
                    <ul class="table-title flex gap0 mb-14 table-row-align">
                        <li class="col-id">
                            <div class="body-title">ID</div>
                        </li>
                        <li class="col-title">
                            <div class="body-title">Tiêu đề</div>
                        </li>
                        <li class="col-images">
                            <div class="body-title">Ảnh</div>
                        </li>
                        <li class="col-type">
                            <div class="body-title">Loại banner</div>
                        </li>
                        <li class="col-status">
                            <div class="body-title">Trạng thái</div>
                        </li>
                        <li class="col-action">
                            <div class="body-title">Hành động</div>
                        </li>
                    </ul>

                    @forelse ($banners as $banner)
                        <ul class="user-item flex gap0 mb-0 table-row-align" style="border-bottom:1px solid #f2f2f2;">
                            <li class="col-id">{{ $banner->id }}</li>
                            <li class="col-title">{{ $banner->tieu_de }}</li>
                            <li class="col-images">
                                @if ($banner->hinhAnhBanner && $banner->hinhAnhBanner->count())
                                    @foreach ($banner->hinhAnhBanner as $img)
                                        <img src="{{ asset('storage/' . $img->hinh_anh) }}" alt="ảnh banner"
                                            style="max-width: 60px; max-height: 60px; border-radius: 4px;">
                                    @endforeach
                                @else
                                    <span class="text-muted">Chưa có ảnh</span>
                                @endif
                            </li>
                            <li class="col-type">{{ $banner->loai_banner }}</li>
                            <li class="col-status">
                                @if ($banner->trang_thai === 'hien')
                                    <span class="badge-status badge-instock">Hiển thị</span>
                                @else
                                    <span class="badge-status badge-outstock">Ẩn</span>
                                @endif
                            </li>
                            <li class="col-action">
                                <a href="{{ route('admin.banners.show', $banner->id) }}" title="Xem"
                                    class="action-icon"><i class="icon-eye"></i></a>
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" title="Sửa"
                                    class="action-icon edit"><i class="icon-edit-3"></i></a>
                                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Xóa" class="action-icon" style="color: #e74c3c;"><i
                                            class="icon-trash-2"></i></button>
                                </form>
                            </li>
                        </ul>
                    @empty
                        <div class="text-muted px-3">Chưa có banner nào.</div>
                    @endforelse
                </div>

    <div class="wg-box">
      <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
          <form method="GET" action="{{ route('admin.banners.index') }}" class="form-search flex items-center gap10">
            <fieldset class="name">
              <input type="text" name="search" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            </fieldset>
            <div class="button-submit">
              <button type="submit"><i class="icon-search"></i></button>

                <!-- Phân trang -->
                <div class="divider"></div>
                <div class="flex justify-between align-items-center mt-3">
                    <div class="text-tiny">Tổng: {{ $banners->total() }} banner</div>
                    <div>
                        {{ $banners->links('pagination::bootstrap-4') }}
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
<script>

  document.addEventListener('DOMContentLoaded', function () {
    // XÓA ĐOẠN HIỂN THỊ LỖI/THÔNG BÁO Ở ĐÂY
  });

    document.addEventListener('DOMContentLoaded', function() {
        const alertBox = document.getElementById('success-alert');
        if (alertBox) {
            setTimeout(() => {
                alertBox.classList.add('fade');
                alertBox.style.transition = "opacity 0.5s";
                alertBox.style.opacity = 0;
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        }
    });

</script>

<style>
    /* Cấu trúc bảng */
    .table-title,
    .user-item {
        width: 100%;
    }

    .table-title li,
    .user-item li {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 14px 10px;
        font-size: 16px;
        box-sizing: border-box;
        word-break: break-word;
        background: none;
        border: none;
        min-height: 48px;
    }

    .table-row-align {
        align-items: stretch !important;
    }

    /* Các cột */
    .col-id {
        flex: 0.5 0 40px;
        min-width: 40px;
        justify-content: flex-start;
    }

    .col-title {
        flex: 1.8 0 140px;
        min-width: 140px;
        justify-content: flex-start;
    }

    .col-images {
        flex: 2.5 0 200px;
        min-width: 200px;
        justify-content: flex-start;
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .col-type {
        flex: 1.2 0 140px;
        min-width: 140px;
        justify-content: flex-start;
    }

    .col-status {
        flex: 1.2 0 140px;
        min-width: 140px;
        justify-content: flex-start;
    }

    .col-action {
        flex: 1.2 0 120px;
        min-width: 120px;
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: flex-start;
    }

    /* Badge trạng thái */
    .badge-status {
        display: inline-block;
        padding: 6px 18px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        background: #f3f7f6;
        color: #1abc9c;
        letter-spacing: 0.5px;
        vertical-align: middle;
        margin-top: 2px;
    }

    .badge-instock {
        background: #f3f7f6;
        color: #1abc9c;
    }

    .badge-outstock {
        background: #fbeee7;
        color: #e67e22;
    }

    /* Ảnh banner */
    .col-images img {
        max-width: 80px;
        max-height: 80px;
        border-radius: 6px;
        border: 1px solid #ddd;
    }

    /* Icon hành động */
    .action-icon {
        color: #2d9cdb;
        font-size: 20px;
        margin-right: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }

    .action-icon.edit {
        color: #f7b731;
        margin-right: 0;
    }

    /* Breadcrumb */
    .breadcrumbs .text-tiny {
        font-size: 13px;
        color: #999;
    }

    .breadcrumbs li i {
        color: #ccc;
    }

    /* Responsive & căn chỉnh */
    @media (max-width: 1200px) {
        .col-title {
            min-width: 120px;
        }

        .col-images {
            min-width: 150px;
        }
    }

    @media (max-width: 900px) {

        .table-title li,
        .user-item li {
            font-size: 15px;
        }

        .col-images {
            min-width: 120px;
        }
    }

    /* Giao diện layout chính */
    /* html, */
    /* body {
        height: auto !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
    } */

    /* .main-content-inner,
    .main-content-wrap {
        overflow: visible !important;
        min-height: 100vh;
    }

    .wg-box {
        overflow: visible;
    } */
    html, body {
    overflow-y: auto !important;
    overflow-x: hidden !important;
    height: auto !important;
}

* {
    overflow: visible !important;
}

</style>
