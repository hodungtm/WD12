@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Danh sách tài khoản</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><div class="text-tiny">Tài khoản</div></li>
                </ul>
            </div>
            <div class="wg-box">
                {{-- Hiển thị thông báo thành công/lỗi (từ giao diện 2) --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="title-box">
                    <i class="icon-users"></i>
                    <div class="body-text">Tìm kiếm và lọc tài khoản.</div>
                </div>
                <div class="flex flex-column gap10 mb-3">
                    {{-- Form tìm kiếm theo tên hoặc email (từ giao diện 1) --}}
                    <form method="GET" action="{{ route('admin.users.index') }}" class="form-search w-100" style="margin-bottom: 10px;">
                        <div class="search-input" style="width: 100%; position: relative;">
                            <input type="text" placeholder="Tìm kiếm tên hoặc email..." name="search" value="{{ request('search') }}" style="width: 100%; min-width: 200px;">
                            <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff; position: absolute; right: 5px; top: 50%; transform: translateY(-50%);">
                                <i class="icon-search" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                            </button>
                        </div>
                    </form>
                    <div class="flex items-center justify-between gap10 flex-wrap">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap10 flex-wrap align-items-center" style="margin-bottom: 0;">
<input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="per_page" value="{{ request('per_page') }}"> {{-- Giữ lại per_page nếu có --}}

                            {{-- Bộ lọc Vai trò (dropdown từ giao diện 2, nhưng đã điều chỉnh để không tự động gửi form) --}}
                            <div class="dropdown">
                                <ul class="dropdown-menu" aria-labelledby="filterRoleBtn">
                                    <li><a class="dropdown-item {{ !request('role') ? 'active' : '' }}" href="{{ route('admin.users.index', array_merge(request()->except('role'), ['search' => request('search'), 'is_active' => request('is_active'), 'gender' => request('gender'), 'sort_created' => request('sort_created'), 'per_page' => request('per_page')])) }}">Tất cả</a></li>
                                    <li><a class="dropdown-item {{ request('role') === 'super-admin' ? 'active' : '' }}" href="{{ route('admin.users.index', array_merge(request()->all(), ['role' => 'super-admin'])) }}">Super Admin</a></li>
                                    <li><a class="dropdown-item {{ request('role') === 'admin' ? 'active' : '' }}" href="{{ route('admin.users.index', array_merge(request()->all(), ['role' => 'admin'])) }}">Admin</a></li>
                                    <li><a class="dropdown-item {{ request('role') === 'user' ? 'active' : '' }}" href="{{ route('admin.users.index', array_merge(request()->all(), ['role' => 'user'])) }}">User</a></li>
                                </ul>
                            </div>

                            {{-- Các bộ lọc Trạng thái, Giới tính, Ngày tạo (từ giao diện 1) --}}
                            <select name="is_active" class="form-select" style="width: 120px; height: 38px;">
                                <option value="">-- Trạng thái --</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Bị khóa</option>
                            </select>
                            <select name="gender" class="form-select" style="width: 120px; height: 38px;">
                                <option value="">-- Giới tính --</option>
                                <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            <select name="sort_created" class="form-select" style="width: 120px; height: 38px;">
                                <option value="">-- Ngày tạo --</option>
<option value="desc" {{ request('sort_created') === 'desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="asc" {{ request('sort_created') === 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                            <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff;">
                                <i class="icon-filter" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                            </button>
                        </form>

                        {{-- Nút "Xóa bộ lọc" (từ giao diện 1) --}}
                        @if(request()->hasAny(['role', 'search', 'is_active', 'gender', 'sort_created', 'per_page']))
                            <a href="{{ route('admin.users.index') }}" class="tf-button style-1 w208 btn btn-outline-secondary btn-sm me-1 mb-1" style="height: 38px; display: flex; align-items: center;">
                                <i class="icon-x me-2"></i> Xóa bộ lọc
                            </a>
                        @endif
                    </div>
                </div>
                <div class="wg-table table-product-list mt-3">
                    <ul class="table-title flex mb-14" style="gap: 2px;">
                        <li style="flex-basis: 40px;"><div class="body-title">ID</div></li>
                        <li style="flex-basis: 80px;"><div class="body-title">Ảnh đại diện</div></li>
                        <li style="flex-basis: 180px;"><div class="body-title">Họ tên</div></li>
                        <li style="flex-basis: 180px;"><div class="body-title">Email</div></li>
                        <li style="flex-basis: 100px;"><div class="body-title">Vai trò</div></li>
                        {{-- <li style="flex-basis: 100px;"><div class="body-title">Điện thoại</div></li> --}}
                        <li style="flex-basis: 80px;"><div class="body-title">Giới tính</div></li>
                        <li style="flex-basis: 100px;"><div class="body-title">Trạng thái</div></li>
                        <li style="flex-basis: 120px;"><div class="body-title">Ngày tạo</div></li>
                        <li style="flex-basis: 140px;"><div class="body-title">Thao tác</div></li>
                    </ul>
                    <ul class="flex flex-column">
                        @forelse($users as $user)
                            <li class="wg-product item-row flex" style="align-items: center; gap: 2px;">
                                <div class="body-text mt-4" style="flex-basis: 40px;">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</div>
                                <div style="flex-basis: 80px;">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
class="border border-3 border-white shadow profile-avatar"
                                    alt="Avatar" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                </div>
                                <div class="title line-clamp-2 mb-0" style="flex-basis: 180px;">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="body-text">{{ $user->name }}</a>
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 180px;">{{ $user->email }}</div>
                                <div class="body-text mt-4" style="flex-basis: 100px;">
                                    <span class="badge fw-bold {{ $user->role === 'super-admin' ? 'bg-danger' : ($user->role === 'admin' ? 'bg-warning text-dark' : 'bg-success') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                                {{-- <div class="body-text mt-4" style="flex-basis: 100px;">{{ $user->phone ?? '—' }}</div> --}}
                                <div class="body-text mt-4" style="flex-basis: 80px;">{{ ucfirst($user->gender) }}</div>
                                <div style="flex-basis: 100px;">
                                    <div class="{{ $user->is_active ? 'block-available' : 'block-stock' }} bg-1 fw-7">
                                        {{ $user->is_active ? 'Hoạt động' : 'Bị khóa' }}
                                    </div>
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 120px;">{{ optional($user->created_at)->format('d/m/Y H:i') }}</div>
                                <div class="d-flex align-items-center justify-content-start" style="flex-basis: 140px; gap: 8px;">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="item eye" title="Xem" style="font-size: 20px; color: #1abc9c;"><i class="icon-eye"></i></a>

                                    @if(Auth::user()->role === 'admin' && $user->role === 'user')
                                        <form method="POST" action="{{ route('admin.users.toggle-active', $user->id) }}" class="form-toggle-active" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="item eye" data-confirm-text="Bạn có chắc muốn {{ $user->is_active ? 'khóa' : 'mở khóa' }} tài khoản này?" title="{{ $user->is_active ? 'Khóa' : 'Mở khóa' }}" style="font-size: 20px; color: {{ $user->is_active ? '#e74c3c' : '#28a745' }}; background: none; border: none; cursor: pointer;">
                                                <i class="fas {{ $user->is_active ? 'fa-lock' : 'fa-unlock' }}"></i>
</button>
                                        </form>
                                    @endif
                                  
                                    
                                </div>
                            </li>
                        @empty
                            <li class="text-center text-muted py-3">Chưa có tài khoản nào.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="divider"></div>
                {{-- Phân trang (từ giao diện 1, nhưng đã thêm withQueryString để giữ lại các tham số lọc) --}}
                <div class="flex items-center justify-between flex-wrap gap10 mt-3">
                    <div class="text-tiny">Hiển thị từ {{ $users->firstItem() }} đến {{ $users->lastItem() }} trong tổng số {{ $users->total() }} tài khoản</div>
                    {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.form-toggle-active').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const confirmText = this.querySelector('button').getAttribute('data-confirm-text');
                if (confirm(confirmText)) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush