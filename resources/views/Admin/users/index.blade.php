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
@endsection