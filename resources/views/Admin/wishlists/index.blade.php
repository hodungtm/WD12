@extends('Admin.Layouts.AdminLayout')
@section('main')

  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item">Danh sách sản phẩm yêu thích</li>
    </ul>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
    <form action="{{ route('admin.wishlists.index') }}" method="GET" class="d-flex mb-3" role="search">
      <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm sản phẩm hoặc user..."
      aria-label="Search" value="{{ request('search') }}">
      <button class="btn btn-outline-primary" type="submit">Tìm kiếm</button>
    </form>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
    <div class="tile">
      <h3 class="tile-title">Danh sách sản phẩm yêu thích</h3>

      @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

      <table class="table table-hover table-bordered" id="sampleTable">
      <thead>
        <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Ảnh</th>
        <th>Giá</th>
        <th>Trạng thái</th>
        <th>Ngày thêm</th>
        <th>Hành động</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($wishlists as $wishlist)
      <tr>
      <td>{{ $wishlist->id }}</td>
      <td>{{ $wishlist->product_name ?? ($wishlist->product->name ?? 'N/A') }}</td>
      <td>
        @php
      $image = $wishlist->image ?? ($wishlist->product->image ?? null);
      @endphp
        @if($image)
      <img src="{{ Str::startsWith($image, ['http://', 'https://']) ? $image : asset('storage/' . $wishlist->image) }}"
      alt="{{ $wishlist->product_name ?? ($wishlist->product->name ?? '') }}"
      style="width: 60px; height: auto;">
      @else
      N/A
      @endif
      </td>
      <td>
        @php
      $price = $wishlist->price ?? ($wishlist->product->price ?? null);
      @endphp
        {{ $price !== null ? number_format($price, 0, ',', '.') . '₫' : 'N/A' }}
      </td>
      <td>
        @php
      $status = $wishlist->status ?? ($wishlist->product->status ?? null);
      @endphp
        @if($status !== null)
      @if($status == 1 || $status === 'active')
      <span class="badge bg-success">Còn hàng</span>
      @else
      <span class="badge bg-danger">Hết hàng</span>
      @endif
      @else
      N/A
      @endif
      </td>
      <td>{{ $wishlist->created_at ? $wishlist->created_at->format('d/m/Y') : 'N/A' }}</td>
      <td>
        <a class="btn btn-info btn-sm" href="{{ route('admin.wishlists.show', $wishlist) }}">Xem</a>
        <form action="{{ route('admin.wishlists.destroy', $wishlist) }}" method="POST" style="display:inline-block;"
        onsubmit="return confirm('Bạn có chắc muốn xóa?');">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger btn-sm" type="submit">Xóa</button>
        </form>
      </td>
      </tr>
      @empty
      <tr>
      <td colspan="7" class="text-center">Chưa có sản phẩm yêu thích nào.</td>
      </tr>
      @endforelse
      </tbody>
      </table>

      <div class="mt-3">
      {{ $wishlists->withQueryString()->links() }}
      </div>
    </div>
    </div>
  </div>

@endsection