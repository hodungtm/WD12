@extends('Admin.Layouts.AdminLayout')
@section('main')

<h1>Thêm Thương Hiệu</h1>

<h1>Sửa Thương Hiệu</h1>

<form action="{{ route('admin.brand.update',$brand->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Tên Thương Hiệu</label>
        <input id="name" name="name" class="form-control" value="{{ old('name',$brand->name) }}">
        @error('name')
        <div class="text-danger mt-1">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Mô Tả</label>
        <textarea id="description" name="description" class="form-control"
            rows="5">{{ old('description',$brand->description) }}</textarea>
    </div>

    <div class="mb-3">
        <label for="logo" class="form-label">Logo Thương Hiệu</label>
        <input id="logo" name="logo" class="form-control" type="file">
        @error('logo')
        <div class="text-danger mt-1">
            {{ $message }}
        </div>
        @enderror

        @if($brand->logo)
        <img src="{{ asset('storage/' . $brand->logo) }}" alt="Logo Thương Hiệu" width="100">
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật Thương Hiệu</button>
    <a href="{{ route('admin.brand.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection