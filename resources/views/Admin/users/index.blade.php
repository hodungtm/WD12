@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="row">
        <div class="col-md-12">
            <div class="tile card shadow-sm rounded-3 border-0">
                <div class="tile-body p-4">

                    <div class="wg-box">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="flex items-center justify-content-between gap10 flex-wrap">
                            <div class="wg-filter flex-grow">
                                <div class="show">
                                    <div class="text-tiny">Showing</div>
                                    <div class="select">
                                        <select class="" onchange="window.location.href = this.value">
                                            <option
                                                value="{{ route('admin.users.index', ['per_page' => 10, 'search' => request()->get('search'), 'role' => request()->get('role')]) }}"
                                                @if (request()->get('per_page') == 10) selected @endif>10</option>
                                            <option
                                                value="{{ route('admin.users.index', ['per_page' => 25, 'search' => request()->get('search'), 'role' => request()->get('role')]) }}"
                                                @if (request()->get('per_page') == 25) selected @endif>25</option>
                                            <option
                                                value="{{ route('admin.users.index', ['per_page' => 50, 'search' => request()->get('search'), 'role' => request()->get('role')]) }}"
                                                @if (request()->get('per_page') == 50) selected @endif>50</option>
                                            <option
                                                value="{{ route('admin.users.index', ['per_page' => 100, 'search' => request()->get('search'), 'role' => request()->get('role')]) }}"
                                                @if (request()->get('per_page') == 100) selected @endif>100</option>
                                        </select>
                                    </div>
                                    <div class="text-tiny">entries</div>
                                </div>
                                <form action="{{ route('admin.users.index') }}" method="GET" class="form-search">
                                    <fieldset class="name">
                                        <input type="text" placeholder="Search here..." name="search" tabindex="2"
                                            value="{{ request()->get('search') }}" aria-required="true" required="">
                                    </fieldset>
                                    <div class="button-submit">
                                        <button type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="dropdown">
                                <button
                                    class="tf-button style-1 w208 btn btn-outline-primary btn-sm me-1 mb-1 dropdown-toggle"
                                    type="button" id="filterRoleBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-2"></i> Vai trò
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterRoleBtn">
                                    <li><a class="dropdown-item {{ !request('role') ? 'active' : '' }}"
                                            href="{{ route('admin.users.index', request()->except('role')) }}"><i
                                                class="fas fa-users me-1"></i> Tất cả</a></li>
                                    <li><a class="dropdown-item {{ request('role') === 'admin' ? 'active' : '' }}"
                                            href="{{ route('admin.users.index', array_merge(request()->all(), ['role' => 'admin'])) }}"><i
                                                class="fas fa-user-shield me-1"></i> Admin</a></li>
                                    <li><a class="dropdown-item {{ request('role') === 'user' ? 'active' : '' }}"
                                            href="{{ route('admin.users.index', array_merge(request()->all(), ['role' => 'user'])) }}"><i
                                                class="fas fa-user me-1"></i> User</a></li>
                                </ul>
                            </div>
                            @if(request()->hasAny(['role', 'search', 'per_page']))
                                <a href="{{ route('admin.users.index') }}"
                                    class="tf-button style-1 w208 btn btn-outline-secondary btn-sm me-1 mb-1">
                                    <i class="fas fa-times me-2"></i> Xóa bộ lọc
                                </a>
                            @endif
                        </div>

                        <div class="wg-table table-product-list">
                            <ul class="table-title flex mb-14" style="gap: 20px;">
                                <li style="width: 60px; flex-shrink: 0;">
                                    <div class="body-title">STT</div>
                                </li>
                                <li style="width: 80px; flex-shrink: 0;">
                                    <div class="body-title">Ảnh</div>
                                </li>
                                <li style="flex-grow: 2;">
                                    <div class="body-title">Tên</div>
                                </li>
                                <li style="flex-grow: 3;">
                                    <div class="body-title">Email</div>
                                </li>
                                <li style="width: 100px; flex-shrink: 0;">
                                    <div class="body-title">Vai trò</div>
                                </li>
                                <li style="width: 100px; flex-shrink: 0;">
                                    <div class="body-title">Giới tính</div>
                                </li>
                                <li style="width: 120px; flex-shrink: 0;">
                                    <div class="body-title">Trạng thái</div>
                                </li>
                                <li style="width: 150px; flex-shrink: 0;">
                                    <div class="body-title">Ngày tạo</div>
                                </li>
                                <li style="width: 140px; flex-shrink: 0;">
                                    <div class="body-title">Thao tác</div>
                                </li>
                            </ul>
                            <ul class="flex flex-column">
                                @forelse($users as $user)
                                    <li class="wg-product item-row flex" style="align-items: center; gap: 20px;">
                                        <div class="body-text text-main-dark mt-4" style="width: 60px; flex-shrink: 0;">
                                            {{ $users->firstItem() + $loop->index }}
                                        </div>
                                        <div style="width: 80px; flex-shrink: 0;">
                                            <div class="image" style="width: 40px; height: 40px;">
                                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}"
                                                    alt="Avatar"
                                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                            </div>
                                        </div>
                                        <div class="title line-clamp-2 mb-0" style="flex-grow: 2;">
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                class="body-text">{{ $user->name }}</a>
                                        </div>
                                        <div class="body-text text-main-dark mt-4" style="flex-grow: 3;">{{ $user->email }}
                                        </div>
                                        <div class="body-text text-main-dark mt-4" style="width: 100px;">
                                            <span
                                                class="badge fw-bold {{ $user->role === 'super-admin' ? 'bg-danger' : ($user->role === 'admin' ? 'bg-warning text-dark' : 'bg-success') }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                        <div class="body-text text-main-dark mt-4" style="width: 100px;">
                                            {{ ucfirst($user->gender) }}
                                        </div>
                                        <div style="width: 120px;">
                                            <span class="{{ $user->is_active ? 'text-success' : 'text-danger' }} fw-bold">
                                                {{ $user->is_active ? 'Hoạt động' : 'Bị khóa' }}
                                            </span>
                                        </div>
                                        <div class="body-text text-main-dark mt-4" style="width: 150px;">
                                            {{ optional($user->created_at)->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="d-flex align-items-center justify-content-start">
                                            {{-- Nút xem --}}
                                            <div
                                                style="flex: 0 0 32px; display: flex; justify-content: center; align-items: center; height: 100%; margin-right: 8px;">
                                                <a href="{{ route('admin.users.show', $user->id) }}" class="item eye"
                                                    title="Xem"
                                                    style="font-size: 1.4em; display: flex; align-items: center; height: 100%;">
                                                    <i class="icon-eye"></i>
                                                </a>
                                            </div>

                                            {{-- Nút sửa: đã comment lại theo yêu cầu --}}
                                            {{-- <div
                                                style="flex: 0 0 32px; display: flex; justify-content: center; align-items: center; height: 100%; margin-right: 8px;">
                                                @if(Auth::user()->role === 'admin' && $user->id === Auth::id())
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="item edit"
                                                    title="Sửa"
                                                    style="font-size: 1.4em; display: flex; align-items: center; height: 100%;">
                                                    <i class="icon-edit-3"></i>
                                                </a>
                                                @else
                                                <div style="width: 100%; height: 100%; visibility: hidden;"></div>
                                                @endif
                                            </div> --}}

                                            {{-- Nút khóa/mở: admin chỉ được khóa user --}}
                                            <div
                                                style="flex: 0 0 32px; display: flex; justify-content: center; align-items: center; height: 100%;">
                                                @if(Auth::user()->role === 'admin' && $user->role === 'user')
                                                    <form method="POST" action="{{ route('admin.users.toggle-active', $user->id) }}"
                                                        class="form-toggle-active"
                                                        style="display: flex; height: 100%; width: 100%; justify-content: center; align-items: center;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="item toggle"
                                                            data-confirm-text="Bạn có chắc muốn {{ $user->is_active ? 'khóa' : 'mở khóa' }} tài khoản này?"
                                                            title="{{ $user->is_active ? 'Khóa' : 'Mở khóa' }}"
                                                            style="border: none; background: none; padding: 0; cursor: pointer; font-size: 1.4em; height: 100%; display: flex; align-items: center;">
                                                            <i class="fas {{ $user->is_active ? 'fa-lock' : 'fa-unlock' }}"
                                                                style="color: {{ $user->is_active ? '#dc3545' : '#28a745' }};"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <div style="width: 100%; height: 100%; visibility: hidden;"></div>
                                                @endif
                                            </div>
                                        </div>





                                    </li>
                                @empty
                                    <li class="text-center text-muted py-3">Chưa có tài khoản nào.</li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="divider"></div>
                        <div class="flex items-center justify-content-between flex-wrap gap10 mt-3">
                            <div class="text-tiny">Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of
                                {{ $users->total() }} entries
                            </div>
                            {{ $users->links('pagination::bootstrap-5') }}
                        </div>

                    </div>
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
                    <i class="icon-book-open"></i>
                    <div class="body-text">Tìm kiếm tài khoản theo tên hoặc email.</div>
                </div>
                <div class="flex items-center justify-between gap10 flex-wrap mb-3">
                    <div class="wg-filter flex-grow">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="form-search mt-2">
                            <fieldset class="name">
                                <input type="text" placeholder="Tìm kiếm tên hoặc email..." name="search" value="{{ request('search') }}">
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-search"></i></button>
                            </div>
                        </form>
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
                        <li style="flex-basis: 120px;"><div class="body-title">Hành động</div></li>
                    </ul>
                    <ul class="flex flex-column">
                        @foreach($users as $user)
                            <li class="wg-product item-row" style="gap: 2px;">
                                <div class="body-text mt-4" style="flex-basis: 40px;">#{{ $user->id }}</div>
                                <div class="image" style="flex-basis: 80px;">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default-avatar.png') }}" width="50" height="50" class="rounded-circle" alt="Avatar">
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 180px;">{{ $user->name }}</div>
                                <div class="body-text mt-4" style="flex-basis: 180px;">{{ $user->email }}</div>
                                <div class="body-text mt-4" style="flex-basis: 100px;">
                                    @if($user->role === 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">User</span>
                                    @endif
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 100px;">{{ $user->phone }}</div>
                                <div class="body-text mt-4" style="flex-basis: 80px;">{{ ucfirst($user->gender) }}</div>
                                <div class="body-text mt-4" style="flex-basis: 100px;">
                                    @if($user->is_active)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-danger">Bị khóa</span>
                                    @endif
                                </div>
                                <div class="body-text mt-4" style="flex-basis: 120px;">{{ optional($user->created_at)->format('d/m/Y H:i') }}</div>
                                <div class="list-icon-function" style="flex-basis: 120px;">
                                    <a href="{{ route('admin.users.show', $user) }}" class="item eye"><i class="icon-eye"></i></a>
                                    @if($user->role === 'admin')
                                        <a href="{{ route('admin.users.edit', $user) }}" class="item edit"><i class="icon-edit-3"></i></a>
                                    @endif
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="color: red" title="Xóa tài khoản">
                                            <i class="icon-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10">
                    <div class="text-tiny">Tổng: {{ $users->total() }} tài khoản</div>
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.form-toggle-active').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const confirmText = this.querySelector('button').getAttribute('data-confirm-text');
                if (confirm(confirmText)) {
                    this.submit();
                }
            });
        });
    </script>
@endsection