@extends('Admin.Layouts.AdminLayout')
@section('main')

<main class="app-content">
    <div class="app-title">
        <ul class="app-breadcrumb breadcrumb side">
            <li class="breadcrumb-item active"><a href="#"><b>Quản lý Đánh giá</b></a></li>
        </ul>
        <div id="clock"></div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="row element-button">
                        <div class="col-sm-2">
                            <a href="{{ route('Admin.reviews.trash') }}" class="btn btn-warning">
                                <i class="fas fa-trash-restore"></i> Thùng rác
                            </a>
                        </div>
                    </div>


                    <form action="{{ route('Admin.reviews.index') }}" method="GET" class="d-flex mb-3"
                        style="max-width: 700px; gap: 10px;">

                        <!-- Input tìm kiếm sản phẩm -->
                        <input type="text" name="product_name" class="form-control" placeholder="Tìm theo sản phẩm..."
                            value="{{ request('product_name') }}" style="max-width: 250px;" autocomplete="off">

                        <!-- Lọc theo số sao -->
                        <select name="so_sao" class="form-control" style="max-width: 120px;">
                            <option value="">-- Số sao --</option>
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ (request('so_sao') == $i) ? 'selected' : '' }}>{{ $i }} sao
                                </option>
                            @endfor
                        </select>

                        <!-- Lọc theo trạng thái -->
                        <select name="trang_thai" class="form-control" style="max-width: 140px;">
                            <option value="">-- Trạng thái --</option>
                            <option value="1" {{ (request('trang_thai') === '1') ? 'selected' : '' }}>Hiển thị</option>
                            <option value="0" {{ (request('trang_thai') === '0') ? 'selected' : '' }}>Ẩn</option>
                        </select>

                        <!-- Sắp xếp đánh giá -->
                        <select name="sort" class="form-control" style="max-width: 180px;">
                            <option value="">-- Sắp xếp --</option>
                            <option value="newest" {{ (request('sort') == 'newest') ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ (request('sort') == 'oldest') ? 'selected' : '' }}>Cũ nhất</option>
                        </select>

                        <button class="btn btn-outline" type="submit" style="height: calc(2.7rem + 2px);">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>


                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người dùng</th>
                                <th>Sản phẩm</th>
                                <th>Số sao</th>
                                <th>Nội dung</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $review)
                                                    <tr>
                                                        <td>{{ $review->id }}</td>
                                                        <td>{{ $review->user->name ?? 'N/A' }}</td>
                                                        <td>{{ $review->product->name ?? 'N/A' }}</td>
                                                        <td>{{ $review->so_sao }}</td>
                                                        <td>{{ Str::limit($review->noi_dung, 50) }}</td>
                                                        <td>
                                                            {!! $review->trang_thai
                                ? '<span class="badge bg-success">Hiển thị</span>'
                                : '<span class="badge bg-secondary">Ẩn</span>'
                                                                                                                                                                                                                                                                                    !!}
                                                        </td>
                                                        <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('Admin.reviews.edit', $review->id) }}"
                                                                class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                            <form action="{{ route('Admin.reviews.destroy', $review->id) }}" method="POST"
                                                                style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-danger btn-sm" type="submit"><i
                                                                        class="fas fa-trash-alt"></i></button>
                                                            </form>
                                                            <a href="{{ route('Admin.reviews.show', $review->id) }}" class="btn btn-info btn-sm"
                                                                title="Xem chi tiết">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $reviews->links('pagination::bootstrap-4') }}

                </div>
            </div>
        </div>
    </div>
</main>