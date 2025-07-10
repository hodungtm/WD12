@extends('Admin.Layouts.AdminLayout')

@section('main')

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                    <h3>Chỉnh sửa bài viết</h3>
                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                        <li>
                            <a href="index-2.html">
                                <div class="text-tiny">Dashboard</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <a href="#">
                                <div class="text-tiny">Bài viết</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <div class="text-tiny">Chỉnh sửa bài viết</div>
                        </li>
                    </ul>
                </div>

                {{-- XÓA ĐOẠN HIỂN THỊ LỖI/THÔNG BÁO Ở ĐÂY --}}

                <div class="wg-box">
                    <form class="form-new-product form-style-1" action="{{ route('posts.update', $post) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <fieldset class="name col-md-6">
                            <div class="body-title">Tiêu đề</div>
                            <input class="flex-grow" type="text" name="title" value="{{ old('title', $post->title) }}"
                                aria-required="true" required>
                        </fieldset>

                        <fieldset class="name col-md-6">
                            <div class="body-title">Trạng thái</div>
                            <select class="flex-grow" name="status" aria-required="true" required>
                                <option value="">-- Chọn trạng thái --</option>
                                @php
                                    $statuses = [
                                        'published' => 'Đã đăng',
                                        'draft' => 'Nháp',
                                        'hidden' => 'Ẩn',
                                    ];
                                @endphp
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ old('status', $post->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>

                        <fieldset class="name col-md-12">
                            <div class="body-title">Nội dung</div>
                            <textarea class="flex-grow" name="content" id="editor" rows="10" aria-required="true" required>{{ old('content', $post->content ?? '') }}</textarea>
                        </fieldset>

                        <fieldset class="name col-md-6">
                            <div class="body-title">Ảnh đại diện</div>
                            <input class="flex-grow" type="file" name="image">
                            @if ($post->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $post->image) }}" width="150" alt="Ảnh hiện tại"
                                        onerror="this.style.display='none'">
                                </div>
                            @endif
                        </fieldset>

                        <div class="bot col-md-12 mt-3">

                            <button type="submit" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                                <i class="icon-save"></i> Lưu Bài Viết
                            </button>
                            <a href="{{ route('posts.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
                                <i class="icon-x"></i> Hủy bỏ
                            </a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            ClassicEditor
                .create(document.querySelector('#editor'))
                .catch(error => {
                    console.error('CKEditor lỗi:', error);
                });
        });
    </script>
@endsection
