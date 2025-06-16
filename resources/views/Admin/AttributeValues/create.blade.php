@extends('Admin.Layouts.AdminLayout')
@section('main')

<h1>Thêm giá trị thuộc tính</h1>

<form action="{{ route('admin.attributeValue.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="attribute_id" class="form-label fw-semibold">Thuộc tính</label>
        <select id="attribute_id" name="attribute_id" class="form-select @error('attribute_id') is-invalid @enderror">
            <option value="">-- Vui lòng chọn thuộc tính --</option>
            @foreach ($attributes as $attribute)
            <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
            @endforeach
        </select>
        @error('attribute_id')
        <div class="invalid-feedback mt-1">
            {{ $message }}
        </div>
        @enderror
    </div>


    <div class="mb-3">
        <label for="value" class="form-label">Giá trị</label>
        <input id="value" name="value" type="text" class="form-control @error('value') is-invalid @enderror"
            value="{{ old('value') }}" placeholder="Nhập giá trị">
        @error('value')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary">Thêm</button>
    <a href="{{ route('admin.attributeValue.index') }}" class="btn btn-secondary">Hủy</a>
</form>

@if (session('success'))
<div class="alert alert-success mt-3">
    {{ session('success') }}
</div>
@endif

@endsection