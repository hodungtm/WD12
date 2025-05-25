    @extends('Admin.Layouts.AdminLayout')
@section('main')



  <div class="app-title">
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item">Danh sách bài viết</li>
      <li class="breadcrumb-item"><a href="{{ route('posts.create') }}">Thêm bài viết</a></li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <h3 class="tile-title">Danh sách bài viết</h3>

        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-hover table-bordered" id="sampleTable">
          <thead>
            <tr>
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
              <tr>
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>
                  @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="" width="100">
                  @else
                    Không có ảnh
                  @endif
                </td>
                <td>
                  @if($post->status === 'published')
                    <span class="badge bg-success">Đã đăng</span>
                  @elseif($post->status === 'draft')
                    <span class="badge bg-secondary">Nháp</span>
                  @else
                    <span class="badge bg-warning">Ẩn</span>
                  @endif
                </td>
                <td>{{ $post->created_at->format('d/m/Y') }}</td>
               <td>
                <a class="btn btn-info btn-sm" href="{{ route('posts.show', $post) }}">Xem</a>
                <a class="btn btn-primary btn-sm" href="{{ route('posts.edit', $post) }}">Sửa</a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" type="submit">Xóa</button>
                </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">Chưa có bài viết nào.</td>
              </tr>
            @endforelse
          </tbody>
        </table>

        <div class="mt-3">
          {{ $posts->links() }}
        </div>
      </div>
    </div>
  </div>


@endsection
