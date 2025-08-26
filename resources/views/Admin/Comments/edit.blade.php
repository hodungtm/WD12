@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Chỉnh sửa bình luận</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.comments.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.comments.index') }}">
                            <div class="text-tiny">Bình luận</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chỉnh sửa bình luận</div>
                    </li>
                </ul>
            </div>

            <form action="{{ route('admin.comments.update', $comment->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="wg-box mb-30">
                    <div class="title-box">
                        <i class="icon-message-circle"></i>
                        <div class="body-text">Chỉnh sửa trạng thái bình luận</div>
                    </div>
                    
                    <div class="form-group mb-20">
                        <div class="body-title mb-10">Nội dung bình luận</div>
                        <textarea class="form-control" rows="4" readonly disabled style="background: #f8f9fa; color: #666;">{{ $comment->noi_dung }}</textarea>
                    </div>

                    <div class="form-group mb-20">
                        <div class="body-title mb-10">Trạng thái hiển thị</div>
                        <select name="trang_thai" class="form-control" required>
                            <option value="1" {{ old('trang_thai', $comment->trang_thai) == 1 ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ old('trang_thai', $comment->trang_thai) == 0 ? 'selected' : '' }}>Ẩn</option>
                        </select>
                        @error('trang_thai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-start gap-3 mt-4">
                    <button type="submit" class="tf-button btn-sm w-auto px-3 py-2">
                        <i class="icon-save"></i> Cập nhật
                    </button>
                    <a href="{{ route('admin.comments.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                        <i class="icon-x"></i> Hủy bỏ
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
