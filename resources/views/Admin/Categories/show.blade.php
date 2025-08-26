@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Chi tiết danh mục</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.categories.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.categories.index') }}">
                            <div class="text-tiny">Danh mục</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chi tiết danh mục</div>
                    </li>
                </ul>
            </div>

            {{-- Box: Ảnh danh mục --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-image"></i>
                    <div class="body-text">Ảnh danh mục</div>
                </div>
                <div class="image-container" style="background: #f8f9fa; border-radius: 12px; padding: 20px; text-align: center; border: 1px solid #e9ecef;">
                    @if($category->anh)
                        <img src="{{ asset('storage/' . $category->anh) }}" alt="Ảnh danh mục {{ $category->ten_danh_muc }}" style="object-fit: cover; width: 100%; max-width: 240px; height: 240px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    @else
                        <div class="no-image" style="display: flex; align-items: center; justify-content: center; height: 240px; color: #6c757d; font-style: italic;">
                            <div>
                                <i class="icon-image" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                                <div>Không có ảnh</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            {{-- Box: Thông tin danh mục --}}
            <div class="wg-box mb-30">
                <div class="title-box">
                    <i class="icon-folder"></i>
                    <div class="body-text">Thông tin danh mục</div>
                </div>
                <div class="flex flex-wrap gap20 mb-4">
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Tên danh mục</label>
                        <div class="body-text" style="font-size: 16px; color: #333; font-weight: 500;">{{ $category->ten_danh_muc }}</div>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Trạng thái</label>
                        <div class="status-badge" style="display: inline-block;">
                            <div class="{{ $category->tinh_trang == 1 ? 'block-available' : 'block-stock' }} bg-1 fw-7" style="display: inline-block; min-width: 80px; text-align: center; border-radius: 8px; padding: 6px 18px; font-size: 15px; font-weight: 600; background: #f3f7f6; color: {{ $category->tinh_trang == 1 ? '#1abc9c' : '#e74c3c' }}; letter-spacing: 0.5px;">
                                {{ $category->tinh_trang == 1 ? 'Hiển thị' : 'Ẩn' }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <label class="body-title">Mô tả</label>
                        <div class="body-text" style="font-size: 15px; color: #555; line-height: 1.6; background: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #1abc9c;">{!! nl2br(e($category->mo_ta ?? 'Không có mô tả')) !!}</div>
                    </div>
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
                        <span class="body-text" style="font-weight: 500;">{{ $category->created_at ? $category->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                    <div class="w-full md:w-1/2">
                        <label class="body-title">Cập nhật lần cuối:</label>
                        <span class="body-text" style="font-weight: 500;">{{ $category->updated_at ? $category->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="flex justify-start gap-3 mt-4">
                <a href="{{ route('admin.categories.edit', $category->id) }}" class="tf-button btn-sm w-auto px-3 py-2">
                    <i class="icon-edit-3"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.categories.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                    <i class="icon-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
@endsection