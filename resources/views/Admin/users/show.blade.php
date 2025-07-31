@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Chi tiết tài khoản</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li><a href="{{ route('admin.users.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><a href="{{ route('admin.users.index') }}">
                            <div class="text-tiny">Tài khoản</div>
                        </a></li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Chi tiết tài khoản</div>
                    </li>
                </ul>
            </div>
            {{-- Box: Ảnh & thông tin cơ bản --}}
            <div class="wg-box mb-30">
                <div class="title-box mb-20">
                    <i class="icon-user"></i>
                    <div class="body-text">Ảnh & thông tin cơ bản</div>
                </div>
                <div class="flex flex-column items-center mb-4">
                    <div style="flex-basis: 80px;">
                        <img src="{{ $user->avatar ?? 'https://via.placeholder.com/120' }}"
                        class=" border border-3 border-white shadow profile-avatar"
                        alt="Avatar">
                    </div>
                    <div class="body-title mb-2" style="font-size: 1.3em;">{{ $user->name }}</div>
                    <span class="badge fw-bold {{ $user->role === 'super-admin' ? 'bg-danger' : ($user->role === 'admin' ? 'bg-warning text-dark' : 'bg-success') }} mb-2">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="{{ $user->is_active ? 'text-success' : 'text-danger' }} fw-bold mb-2">
                        {{ $user->is_active ? 'Hoạt động' : 'Bị khoá' }}
                    </span>
                </div>
            </div>
            {{-- Box: Thông tin liên hệ & hệ thống --}}
            <div class="wg-box mb-30">
                <div class="title-box mb-20">
                    <i class="icon-info"></i>
                    <div class="body-text">Thông tin liên hệ & hệ thống</div>
                </div>
                <div class="info-section mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="body-title mb-1">Email</div>
                            <div class="body-text">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="body-title mb-1">Số điện thoại</div>
                            <div class="body-text">{{ $user->phone ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="body-title mb-1">Giới tính</div>
                            <div class="body-text">
                                @php
                                    $genderLabels = ['male' => 'Nam ♂', 'female' => 'Nữ ♀', 'other' => 'Khác ⚧'];
                                @endphp
                                {{ $genderLabels[$user->gender] ?? '—' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="body-title mb-1">Ngày tạo</div>
                            <div class="body-text">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="flex justify-start gap-3 mt-4">
                    <a href="{{ route('admin.users.index') }}" class="tf-button style-3 btn-sm w-auto px-2 py-1">
                        <i class="icon-arrow-left"></i>
                    </a>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="tf-button style-2 btn-sm w-auto px-2 py-1">
                        <i class="icon-edit-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
