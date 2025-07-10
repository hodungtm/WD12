@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                    <h3>Tạo mới bài viết</h3>
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
                            <div class="text-tiny">Tạo bài viết</div>
                        </li>
                    </ul>
                </div>
                <div class="wg-box">
                    <form class="form-new-product form-style-1" action="{{ route('posts.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <fieldset class="name">
                            <div class="body-title">Tiêu đề</div>
                            <input class="flex-grow" type="text" placeholder="Nhập tiêu đề bài viết" name="title"
                                tabindex="0" value="" aria-required="true" required="">
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Trạng thái</div>
                            <select class="flex-grow" name="status" aria-required="true" required="">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="published">Đã đăng</option>
                                <option value="draft">Nháp</option>
                                <option value="hidden">Ẩn</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Nội dung</div>
                            <textarea class="flex-grow" name="content" id="editor" rows="10" aria-required="true"></textarea>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title">Ảnh đại diện</div>
                            <input class="flex-grow" type="file" name="image">
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </fieldset>

                        <div class="bot">
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
