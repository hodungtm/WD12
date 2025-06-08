@extends('Admin.Layouts.AdminLayout')

@section('main')

                <div class="app-title">
                    <ul class="app-breadcrumb breadcrumb">
                       <li class="breadcrumb-item"><a href="{{ route('admin.discounts.index') }}">Mã giảm giá</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thùng Rác</li>
                    </ul>
                    <div id="clock"></div>
                </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="tile">
        <div class="tile-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="20%">Mã giảm giá</th>
                            <th width="50%">Mô tả</th>
                            <th width="30%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($discounts as $discount)
                            <tr>
                                <td>{{ $discount->code }}</td>
                                <td>{{ $discount->description }}</td>
                                <td>
                                    <form action="{{ route('admin.discounts.restore', $discount->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" title="Khôi phục">
                                            <i class="fas fa-undo"></i> Khôi phục
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.discounts.forceDelete', $discount->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn chắc chắn muốn xóa vĩnh viễn mã giảm giá này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Xóa vĩnh viễn">
                                            <i class="fas fa-trash"></i> Xóa vĩnh viễn
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($discounts->isEmpty())
                            <tr>
                                <td colspan="3" class="text-muted">Không có mã giảm giá nào đã xóa.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary mt-3">
                <i class="fa fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
@endsection
