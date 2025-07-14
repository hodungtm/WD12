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
                <div class="title-box">
                    <i class="icon-users"></i>
                    <div class="body-text">Tìm kiếm tài khoản theo tên hoặc email.</div>
                </div>
                <div class="flex flex-column gap10 mb-3">
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
                            <select name="role" class="form-select" style="width: 120px;">
                                <option value="">-- Vai trò --</option>
                                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            <select name="is_active" class="form-select" style="width: 120px;">
                                <option value="">-- Trạng thái --</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Bị khóa</option>
                            </select>
                            <select name="gender" class="form-select" style="width: 120px;">
                                <option value="">-- Giới tính --</option>
                                <option value="male" {{ request('gender') === 'male' ? 'selected' : '' }}>Nam</option>
                                <option value="female" {{ request('gender') === 'female' ? 'selected' : '' }}>Nữ</option>
                                <option value="other" {{ request('gender') === 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            <select name="sort_created" class="form-select" style="width: 120px;">
                                <option value="">-- Ngày tạo --</option>
                                <option value="desc" {{ request('sort_created') === 'desc' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="asc" {{ request('sort_created') === 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                            </select>
                            <button type="submit" class="btn d-flex align-items-center justify-content-center" style="height: 38px; width: 38px; padding: 0; border: 1.5px solid #1abc9c; background: #fff;">
                                <i class="icon-filter" style="font-size: 18px; margin: 0; color: #1abc9c;"></i>
                            </button>
                        </form>
                        @if(request()->hasAny(['role', 'search', 'is_active', 'gender', 'sort_created']))
                            <a href="{{ route('admin.users.index') }}" class="tf-button style-1 w208 btn btn-outline-secondary btn-sm me-1 mb-1">
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
                        <li style="flex-basis: 100px;"><div class="body-title">Điện thoại</div></li>
                        <li style="flex-basis: 80px;"><div class="body-title">Giới tính</div></li>
                        <li style="flex-basis: 100px;"><div class="body-title">Trạng thái</div></li>
                        <li style="flex-basis: 120px;"><div class="body-title">Ngày tạo</div></li>
                        <li style="flex-basis: 140px;"><div class="body-title">Thao tác</div></li>
                    </ul>
                    <ul class="flex flex-column">
                        @forelse($users as $user)
                            <li class="wg-product item-row flex" style="align-items: center; gap: 2px;">
                                <div class="body-text mt-4" style="flex-basis: 40px;">#{{ $user->id }}</div>
                                <div style="flex-basis: 80px;">
                                    <img src="{{ $user->avatar ?? 'https://via.placeholder.com/120' }}"
                                    class=" border border-3 border-white shadow profile-avatar"
                                    alt="Avatar">
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
                                <div class="body-text mt-4" style="flex-basis: 100px;">{{ $user->phone ?? '—' }}</div>
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
                                            <button type="submit" class="item eye" data-confirm-text="Bạn có chắc muốn {{ $user->is_active ? 'khóa' : 'mở khóa' }} tài khoản này?" title="{{ $user->is_active ? 'Khóa' : 'Mở khóa' }}" style="font-size: 20px; color: #e74c3c;">
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
                <div class="flex items-center justify-between flex-wrap gap10 mt-3">
                    <div class="text-tiny">Hiển thị từ {{ $users->firstItem() }} đến {{ $users->lastItem() }} trong tổng số {{ $users->total() }} tài khoản</div>
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection