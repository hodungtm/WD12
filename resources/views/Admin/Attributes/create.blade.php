@extends('Admin.Layouts.AdminLayout')
@section('main')

<h1>Thêm thuộc tính</h1>

    <form action="{{ route('admin.attribute.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Tên thuộc tính</label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="form-control @error('name') is-invalid @enderror" 
                value="{{ old('name') }}"
                placeholder="Nhập tên thuộc tính">
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input 
                id="slug" 
                name="slug" 
                type="text" 
                class="form-control @error('slug') is-invalid @enderror" 
                value="{{ old('slug') }}"
                placeholder="Nhập slug nếu bạn muốn">
            @error('slug')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admin.attribute.index') }}" class="btn btn-secondary">Hủy</a>
    </form>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

<script>
        document.getElementById('name').addEventListener('input', function(e) {
            let text = e.target.value.trim().toLowerCase();

            // Chuyển khoảng trắng sang gạch nối
            text = text.split(/\s+/).join('-');

            // Loại bỏ kí tự đặc biệt
            text = text.replace(/[^a-z0-9-]/g, '');

            document.getElementById('slug').value = text;
        });
    </script>

@endsection