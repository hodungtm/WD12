@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Sửa banner: {{ $banner->tieu_de }}</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Sửa banner</div></li>
            </ul>
        </div>

        <div class="wg-box">
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if (session('success'))
                    <div class="alert alert-success mt-3">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group mt-3">
                    <label>Tiêu đề:</label>
                    <input type="text" name="tieu_de" class="form-control" value="{{ old('tieu_de', $banner->tieu_de) }}">
                </div>

                <div class="form-group mt-3">
                    <label>Nội dung:</label>
                    <textarea name="noi_dung" class="form-control">{{ old('noi_dung', $banner->noi_dung) }}</textarea>
                </div>

                <div class="form-group mt-3">
                    <label>Loại banner:</label>
                    <select name="loai_banner" class="form-control">
                        <option value="slider" {{ old('loai_banner', $banner->loai_banner) == 'slider' ? 'selected' : '' }}>Slideshow</option>
                        <option value="footer" {{ old('loai_banner', $banner->loai_banner) == 'footer' ? 'selected' : '' }}>Footer</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <label>Trạng thái:</label>
                    <select name="trang_thai" class="form-control">
                        <option value="hien" {{ old('trang_thai', $banner->trang_thai) == 'hien' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="an" {{ old('trang_thai', $banner->trang_thai) == 'an' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>

                <div class="form-group mt-3">
                    <div class="d-flex justify-between items-end mb-2">
                        <label>Ảnh slide</label>
                        <div id="add-row" class="btn btn-secondary btn-sm">+</div>
                    </div>
                    <table class="table align-middle mb-0">
                        <tbody id="image-table-body">
                            @foreach ($banner->hinhAnhBanner as $index => $item)
                            <tr>
                                <td class="d-flex align-items-center">
                                    <img id="preview_{{ $index }}" src="{{ Storage::url($item->hinh_anh) }}" width="50px">
                                    <input type="file" name="list_image[{{ $item->id }}]" class="form-control mx-2" onchange="previewImage(this, {{ $index }})">
                                    <button type="button" class="btn btn-light text-danger" onclick="markImageForDelete(this, {{ $item->id }})">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-end gap10">
                    <button type="submit" class="btn btn-warning"><i class="icon-edit-3"></i> Cập nhật</button>
                    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var rowCount = {{ count($banner->hinhAnhBanner) }};

        document.getElementById('add-row').addEventListener('click', function() {
            var tableBody = document.getElementById('image-table-body');
            var newRow = document.createElement('tr');

            newRow.innerHTML = `
                <td class="d-flex align-items-center">
                    <img id="preview_${rowCount}" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQrVLGzO55RQXipmjnUPh09YUtP-BW3ZTUeAA&s" width="50px">
                    <input type="file" name="list_image[id_${rowCount}]" class="form-control mx-2" onchange="previewImage(this, ${rowCount})">
                    <button type="button" class="btn btn-light text-danger" onclick="removeRow(this)"><i class="bx bx-trash"></i></button>
                </td>
            `;
            tableBody.appendChild(newRow);
            rowCount++;
        });
    });

    function previewImage(input, rowIndex) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(`preview_${rowIndex}`).setAttribute('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeRow(item) {
        var row = item.closest('tr');
        row.remove();
    }

    function markImageForDelete(button, imageId) {
        let row = button.closest('tr');
        row.remove();

        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_images[]';
        input.value = imageId;

        document.querySelector('form').appendChild(input);
    }
</script>
@endsection
