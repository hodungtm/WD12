@extends('admin.layouts.Adminlayout')

@section('main')

    <!-- Thông báo thành công -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}

        </div>
    @endif


        <div class="app-title d-flex justify-content-between align-items-center mb-3">
            <ul class="app-breadcrumb breadcrumb side mb-0">
                <li class="breadcrumb-item active"><a href="#"><b>Danh sách sản phẩm</b></a></li>
            </ul>
            <div id="clock"></div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tile card shadow-sm rounded-3 border-0">
                    <div class="tile-body p-4">

                        <!-- Nút thêm sản phẩm -->
                        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap" style="gap: 10px;">
                            <a href="{{ route('products.create') }}" class="btn btn-outline-success btn-sm me-1 mb-1">
                                <i class="fas fa-plus me-2"></i> Thêm sản phẩm
                            </a>

                            <!-- Form tìm kiếm -->
                            <form action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center" style="gap: 10px;">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên sản phẩm..." value="{{ request()->get('search') }}" style="min-width: 250px; height: 40px;">
                                <button type="submit" class="btn btn-outline-warning btn-sm me-1 mb-1 " style="min-width: 100px; height: 40px;">
                                    <i class="fas fa-search me-1 "></i> Tìm kiếm
                                </button>
                            </form>
                        </div>

                        <!-- Bảng sản phẩm -->
                        <table class="table table-hover table-bordered align-middle text-center">
                            <thead style="background-color: #f8f9fa; font-weight: bold;">
                                <tr>
                                    <th style="width: 50px;">STT</th>
                                     <th>Mã Sản Phẩm</th>
                                      <th>Tên sản phẩm</th>
                                    <th style="width: 120px;">Hình ảnh</th>
                                    <th>Giá</th>
                                    <th>Danh mục</th>
                                    <th>Trang Thái</th>
                                    <th>Ngày tạo</th>
                                    <th style="width: 180px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    @php
                                        $variant = $product->variants->first();
                                    @endphp
                
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>Thêm Mã SP ai cho mày fix cứng </td>
                                         <td class="text-start">{{ $product->name }}</td>
                                        <td>
                                            @if ($product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $product->images->first()->image) }}" width="50" class="img-thumbnail">
                                            @else
                                                <span class="text-muted">No Image</span>
                                            @endif
                                        </td>
                                       
                                        <td>
                                            @if ($variant)
                                                {{ number_format($variant->price, 0, ',', '.') }} VND
                                            @else
                                                <span class="text-muted">Chưa có giá</span>
                                            @endif
                                        </td>
                                        
                                        </td>
                                        <td>{{ $product->category->ten_danh_muc ?? 'Không có danh mục' }}</td>
                                           <td>Thêm Trạng Thái Vào Cho Tao  </td>    
                                        <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>    
                                     

                                       <td>
                                        <a href="{{ route('products.show', $product->id) }}" 
                                        class="btn btn-outline-info btn-sm me-1 mb-1" title="Xem">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}" 
                                        class="btn btn-outline-warning btn-sm me-1 mb-1" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" 
                                            class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm mb-1" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Phân trang -->
                        @if ($products->hasPages())
                            <div class="mt-3">
                                {{ $products->links('pagination::bootstrap-5') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
@endsection
