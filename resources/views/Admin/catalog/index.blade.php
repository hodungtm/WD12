@extends('admin.layouts.Adminlayout')

@section('main')

<!-- Thông báo thành công -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
    </div>
@endif

<!-- Tiêu đề -->
<div class="app-title d-flex justify-content-between align-items-center mb-3">
    <ul class="app-breadcrumb breadcrumb side mb-0">
        <li class="breadcrumb-item active"><a href="#"><b>Quản lý Size & Color</b></a></li>
    </ul>
    <div id="clock"></div>
</div>


<!-- Nội dung -->
<div class="row">
    <div class="col-md-12">
        <div class="tile card shadow-sm rounded-3 border-0">
            <div class="tile-body p-4">

                <div class="row g-4">
                    <!-- Size -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Danh sách Size</h4>

                        <!-- Form Thêm Size -->
                        <form method="POST" action="{{ route('catalog.size.store') }}" class="mb-4">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="Tên Size..." >
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-plus me-2"></i> Thêm Size
                                </button>
                              
                            </div>   
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                        </form>

                        <!-- Danh sách Size -->
                        <table class="table table-hover table-bordered align-middle text-center">
                            <thead style="background-color: #f8f9fa; font-weight: bold;">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Tên Size</th>
                                    <th style="width: 150px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sizes as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('catalog.size.update', $item->id) }}" class="d-flex">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" value="{{ $item->name }}" class="form-control me-2" >
                                           
                                            <button type="submit" class="btn btn-outline-success btn-sm">Lưu</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('catalog.size.destroy', $item->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Color -->
                    <div class="col-md-6">
                        <h4 class="mb-3">Danh sách Color</h4>

                        <!-- Form Thêm Color -->
                        <form method="POST" action="{{ route('catalog.color.store') }}" class="mb-4">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="Tên Color..." >
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-plus me-2"></i> Thêm Color
                                </button>
                            </div>
                             @error('name')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                        </form>

                        <!-- Danh sách Color -->
                        <table class="table table-hover table-bordered align-middle text-center">
                            <thead style="background-color: #f8f9fa; font-weight: bold;">
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Tên Color</th>
                                    <th style="width: 150px;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($colors as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('catalog.color.update', $item->id) }}" class="d-flex">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" value="{{ $item->name }}" class="form-control me-2" required>
                                            <button type="submit" class="btn btn-outline-success btn-sm">Lưu</button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('catalog.color.destroy', $item->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i> Xóa
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div> <!-- end row -->
            </div>
        </div>
    </div>
</div>

@endsection
