@extends('Admin.Layouts.AdminLayout')
@section('main')
    <h1>Thêm Bình Luận</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('Admin.comments.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="tac_gia">Tác Giả</label>
            <input type="text" name="tac_gia" id="tac_gia" class="form-control" value="{{ old('tac_gia') }}" required>
        </div>

        <div class="form-group">
            <label for="noi_dung">Nội Dung</label>
            <textarea name="noi_dung" id="noi_dung" class="form-control" rows="4" required>{{ old('noi_dung') }}</textarea>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" name="trang_thai" id="trang_thai" class="form-check-input" {{ old('trang_thai') ? 'checked' : '' }}>
            <label for="trang_thai" class="form-check-label">Hiển Thị</label>
        </div>

        <button type="submit" class="btn btn-primary">Thêm Bình Luận</button>
        <a href="{{ route('Admin.comments.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
    
@endsection