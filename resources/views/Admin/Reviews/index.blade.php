@extends('Admin.Layouts.AdminLayout')

@section('main')
<div class="main-content-inner" style="padding-top: 10px; margin-top: 0;">
  <div class="main-content-wrap" style="padding-top: 0; margin-top: 0;">
    <div class="flex items-center flex-wrap justify-between gap20 mb-30">
      <h3>Danh sách đánh giá</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Bảng điều khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Đánh giá</div></li>
      </ul>
    </div>
    <div class="wg-box">
      <div class="flex items-center gap10 mb-3" style="color:#1abc9c; font-size:16px;">
        <i class="icon-coffee" style="font-size:20px;"></i>
        <span>Tip: Bạn có thể tìm kiếm theo <b>ID</b>, <b>người dùng</b> hoặc <b>sản phẩm</b> để lọc nhanh.</span>
      </div>
      <div class="flex items-center justify-between gap10 flex-wrap">
        <div class="wg-filter flex-grow">
          <form action="{{ route('Admin.reviews.index') }}" method="GET" class="form-search flex items-center gap10">
            <div class="flex items-center gap10">
              <label for="per_page" class="text-tiny" style="color:#222;">Hiển thị</label>
              <select name="per_page" id="per_page" class="form-select" style="width: 70px;" onchange="this.form.submit()">
                @foreach([10, 20, 50, 100] as $num)
                  <option value="{{ $num }}" {{ request('per_page', 10) == $num ? 'selected' : '' }}>{{ $num }}</option>
                @endforeach
              </select>
              <span class="text-tiny" style="color:#222;">dòng</span>
            </div>
            <input type="text" name="product_name" class="form-control" placeholder="Tìm theo sản phẩm..." value="{{ request('product_name') }}" style="height: 40px; min-width: 180px;">
            <select name="so_sao" class="form-select" style="height: 40px; min-width: 90px;">
              <option value="">Số sao</option>
              @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ request('so_sao') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
              @endfor
            </select>
            <select name="trang_thai" class="form-select" style="height: 40px; min-width: 110px;">
              <option value="">Trạng thái</option>
              <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Hiển thị</option>
              <option value="0" {{ request('trang_thai') == '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
            <select name="sort" class="form-select" style="height: 40px; min-width: 100px;">
              <option value="">Sắp xếp</option>
              <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
              <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
            </select>
            <button class="tf-button style-1" type="submit" style="height: 40px; min-width: 40px; display: flex; align-items: center; justify-content: center;">
              <i class="icon-search"></i>
            </button>
          </form>
        </div>
      </div>
      <div class="wg-table table-all-category mt-3" style="display: inline-block; min-width: unset; width: auto; max-width: 100%;">
        <ul class="table-title flex mb-14" style="gap:0;">
          <li style="width:60px; text-align:center;"><div class="body-title">ID</div></li>
          <li style="width:140px; text-align:center;"><div class="body-title">Người dùng</div></li>
          <li style="width:140px; text-align:center;"><div class="body-title">Sản phẩm</div></li>
          <li style="width:80px; text-align:center;"><div class="body-title">Số sao</div></li>
          <li style="width:220px; text-align:center;"><div class="body-title">Nội dung</div></li>
          <li style="width:100px; text-align:center;"><div class="body-title">Trạng thái</div></li>
          <li style="width:120px; text-align:center;"><div class="body-title">Ngày tạo</div></li>
          <li style="width:120px; text-align:center;"><div class="body-title">Hành động</div></li>
        </ul>
        <ul class="flex flex-column">
          @forelse ($reviews as $review)
            <li class="wg-product item-row flex align-items-center mb-10" style="gap:0;">
              <div style="width:60px; text-align:center;">{{ $review->id }}</div>
              <div style="width:140px; text-align:center;">{{ $review->user->name ?? 'N/A' }}</div>
              <div style="width:140px; text-align:center;">{{ $review->product->name ?? 'N/A' }}</div>
              <div style="width:80px; text-align:center;">{{ $review->so_sao }} ⭐</div>
              <div style="width:220px; text-align:center;">{{ Str::limit($review->noi_dung, 60) }}</div>
              <div style="width:100px; text-align:center;">
                @if($review->trang_thai)
                  <span class="badge-status badge-instock">Hiện</span>
                @else
                  <span class="badge-status badge-outstock">Ẩn</span>
                @endif
              </div>
              <div style="width:120px; text-align:center;">{{ $review->created_at->format('d/m/Y') }}</div>
              <div class="list-icon-function" style="width:120px; text-align:center;">
                <a href="{{ route('Admin.reviews.show', $review->id) }}" class="item eye" title="Xem"><i class="icon-eye"></i></a>
                <a href="{{ route('Admin.reviews.edit', $review->id) }}" class="item edit" title="Sửa"><i class="icon-edit-3"></i></a>
                <form action="{{ route('Admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?');" style="display:inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" title="Xóa" style="background: none; border: none;">
                    <i class="icon-trash-2" style="color: red; font-size: 22px;"></i>
                  </button>
                </form>
              </div>
            </li>
          @empty
            <div class="text-muted px-3">Không có đánh giá nào.</div>
          @endforelse
        </ul>
      </div>
      <div class="divider mt-3"></div>
      <div class="flex items-center justify-between flex-wrap gap10">
        <div class="text-tiny">Tổng: {{ $reviews->total() }} đánh giá</div>
        {{ $reviews->appends(request()->query())->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>

<style>
  .badge-status {
    display: inline-block;
    padding: 6px 18px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    background: #f3f7f6;
    color: #1abc9c;
    letter-spacing: 0.5px;
    vertical-align: middle;
    margin-top: 2px;
  }
  .badge-instock {
    background: #f3f7f6;
    color: #1abc9c;
  }
  .badge-outstock {
    background: #fbeee7;
    color: #e67e22;
  }
</style>
@endsection
