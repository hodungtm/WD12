@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="card">
        <div class="card-header">
            <h5>Cập nhật quyền: {{ $role->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Tên quyền</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name) }}">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Mô tả</label>
                    <textarea name="description" id="description" class="form-control">{{ old('description', $role->description) }}</textarea>
                </div>

                <button class="btn btn-primary mt-2">Cập nhật</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary mt-2">Quay lại</a>
            </form>
        </div>
    </div>
@endsection
