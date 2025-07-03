@extends('admin.layouts.Adminlayout')

@section('main')
<div class="main-content-inner">
  <div class="main-content-wrap">

    <!-- Tiêu đề và breadcrumb -->
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
      <h3>Danh sách bài viết</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Bài viết</div></li>
      </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">

                    {{-- Các nút chức năng --}}
                    <div class="d-flex align-items-center justify-content-start mb-3" style="gap: 10px;">
                        <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm d-flex align-items-center"
                            style="gap: 5px;">
                            <i class="fas fa-plus"></i> Tạo mới bài viết
                        </a>
                    </div>


                    <form method="GET" action="{{ route('posts.index') }}" class="mb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                            {{-- Lọc theo trạng thái --}}
                            <div class="d-flex align-items-center">
                                <label class="me-2 mb-0">Trạng thái:</label>
                                <select name="status" class="form-control-sm" onchange="this.form.submit()">
                                    <option value=""> Tất cả </option>
                                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã đăng
                                    </option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                                    <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                                </select>
                            </div>

                            {{-- Số lượng hiển thị --}}
                            <div class="d-flex align-items-center">
                                <label class="me-2 mb-0"> Hiện: </label>
                                <select name="per_page" class="form-control-sm" onchange="this.form.submit()"
                                    style="width: auto;">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                                <span class="ms-2">bài viết</span>
                            </div>
                            <div class="d-flex align-items-center">
                                {{-- Form tìm kiếm --}}
                                <form method="GET" action="{{ route('posts.index') }}">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="keyword" class="form-control"
                                                placeholder="Nhập thông tin..." value="{{ request('keyword') }}"
                                                style="min-width: 200px; height: 40px;">
                                            <button style="min-width: 50px; min-height: 40px;" class="btn btn-primary "
                                                type="submit">
                                                <i class="fas fa-search me-1">Tìm Kiếm</i>
                                            </button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                    </form>
                </div>

                {{-- Thông báo --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                {{-- Bảng danh sách bài viết --}}
                <form action="{{ route('posts.delete.selected') }}" method="POST"
                    onsubmit="return confirm('Bạn có chắc muốn xóa các bài viết đã chọn?');">
                    @csrf
                    @method('DELETE')

                    <table class="table table-hover table-bordered js-copytextarea" id="sampleTable">
                        <thead>
                            <tr class="text-center">
                                <th><input type="checkbox" id="check_all"></th>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <th>Ảnh</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($posts as $post)
                                <tr class="text-center align-middle">
                                    <td><input type="checkbox" name="selected_posts[]" value="{{ $post->id }}"
                                            class="check_item"></td>
                                    <td>{{ $post->id }}</td>
                                    <td class="text-start">{{ $post->title }}</td>
                                    <td>
                                        @if ($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}" alt="Ảnh" width="80"
                                                class="img-thumbnail">
                                        @else
                                            <span class="text-muted">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($post->status === 'published')
                                            <span class="badge bg-success">Đã đăng</span>
                                        @elseif($post->status === 'draft')
                                            <span class="badge bg-secondary">Nháp</span>
                                        @else
                                            <span class="badge bg-warning">Ẩn</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('posts.show', $post) }}" class="btn btn-info btn-sm" title="Xem"><i
                                                class="fas fa-eye"></i></a>
                                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm" title="Sửa"><i
                                                class="fas fa-edit"></i></a>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Chưa có bài viết nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-danger btn-sm mt-2" style="gap: 5px;">
                        <i class="fas fa-trash-alt"></i> Xóa các bài viết đã chọn
                    </button>
                </form>
              </div>
            </div>
          </li>
          @empty
          <li class="text-muted p-4">Không có bài viết nào</li>
          @endforelse
        </ul>
      </div>

      <!-- Phân trang -->
      <div class="divider my-4"></div>
      <div class="flex items-center justify-between flex-wrap gap10">
        <div class="text-tiny">Tổng cộng {{ $posts->total() }} bài viết</div>
        <div>
          {{ $posts->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
