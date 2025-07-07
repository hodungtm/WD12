@extends('admin.layouts.AdminLayout')

@section('main')

<div class="main-content-inner">
    <div class="main-content-wrap">

        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Quản lý Size & Color</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li><a href="#"><div class="text-tiny">Dashboard</div></a></li>
                <li><i class="icon-chevron-right"></i></li>
                <li><div class="text-tiny">Danh sách Size & Color</div></li>
            </ul>
        </div>

        <!-- Thông báo thành công -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="wg-box">

            <div class="flex items-center justify-between gap10 flex-wrap">
                <div class="title-box">
                    <i class="icon-coffee"></i>
                    <div class="body-text">Thêm mới Size & Color</div>
                </div>
            </div>

            <div class="flex gap20 mt-3 flex-wrap">

                <!-- Size -->
                <div class="flex-1">
                    <div class="wg-table mt-3">
                        <h4>Danh sách Size</h4>

                        <!-- Form Thêm -->
                        <form method="POST" action="{{ route('catalog.size.store') }}" class="form-search mt-2">
                            @csrf
                            <fieldset class="name">
                                <input type="text" name="name" placeholder="Tên Size..." required>
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-plus"></i> Thêm Size</button>
                            </div>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </form>

                        <ul class="table-title flex gap20 mt-3 mb-14">
                            <li><div class="body-title">ID</div></li>
                            <li><div class="body-title">Tên Size</div></li>
                            <li><div class="body-title">Hành động</div></li>
                        </ul>

                        <ul class="flex flex-column">
                            @foreach($sizes as $item)
                            <li class="flex items-center justify-between gap20">
                                <div>{{ $item->id }}</div>
                                <div class="flex-grow">
                                    <form method="POST" action="{{ route('catalog.size.update', $item->id) }}" class="flex gap10">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $item->name }}" class="form-control w-full" required>
                                        <button type="submit" class="tf-button style-1">Lưu</button>
                                    </form>
                                </div>
                                <div>
                                    <form method="POST" action="{{ route('catalog.size.destroy', $item->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tf-button style-1 danger"><i class="icon-trash"></i> Xóa</button>
                                    </form>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Color -->
                <div class="flex-1">
                    <div class="wg-table mt-3">
                        <h4>Danh sách Color</h4>

                        <!-- Form Thêm -->
                        <form method="POST" action="{{ route('catalog.color.store') }}" class="form-search mt-2">
                            @csrf
                            <fieldset class="name">
                                <input type="text" name="name" placeholder="Tên Color..." required>
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit"><i class="icon-plus"></i> Thêm Color</button>
                            </div>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </form>

                        <ul class="table-title flex gap20 mt-3 mb-14">
                            <li><div class="body-title">ID</div></li>
                            <li><div class="body-title">Tên Color</div></li>
                            <li><div class="body-title">Hành động</div></li>
                        </ul>

                        <ul class="flex flex-column">
                            @foreach($colors as $item)
                            <li class="flex items-center justify-between gap20">
                                <div>{{ $item->id }}</div>
                                <div class="flex-grow">
                                    <form method="POST" action="{{ route('catalog.color.update', $item->id) }}" class="flex gap10">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $item->name }}" class="form-control w-full" required>
                                        <button type="submit" class="tf-button style-1">Lưu</button>
                                    </form>
                                </div>
                                <div>
                                    <form method="POST" action="{{ route('catalog.color.destroy', $item->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="tf-button style-1 danger"><i class="icon-trash"></i> Xóa</button>
                                    </form>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
