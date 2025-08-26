@extends('admin.layouts.AdminLayout')

@section('main')

<div class="flex items-center flex-wrap justify-between gap20 mb-30">
    <h3>Quản lý Size & Color</h3>
    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li>
            <a href="index-2.html"><div class="text-tiny">Dashboard</div></a>
        </li>
        <li>
            <i class="icon-chevron-right"></i>
        </li>
        <li>
            <div class="text-tiny">Size & Color</div>
        </li>
    </ul>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="wg-box">
            <div class="p-4">

                <div class="row g-4">
                    <div class="col-12">

                        <h3 class="mb-3">Danh sách Size</h3>

                        <form method="POST" action="{{ route('admin.catalog.size.store') }}" class="form-style-1 mb-4">
                            @csrf
                            <fieldset class="name">
                                <div class="body-title visually-hidden">Thêm Size</div>
                                <div class="input-group d-flex"> {{-- THAY ĐỔI: Thêm d-flex --}}
                                    <input type="text" name="name" class="form-control me-2" placeholder="Tên Size..." aria-required="true" required> {{-- THAY ĐỔI: Bỏ flex-grow, thêm form-control me-2 --}}

                                    <button type="submit" class="tf-button" style="min-width: 120px;">
                                        <i class="fas fa-plus me-2"></i> Thêm Size
                                    </button>
                                </div>
                            </fieldset>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </form>


                        <div class="wg-table table-product-list mt-3">
                            <ul class="table-title flex mb-14" style="gap: 10px;">
                                <li style="flex-basis: 60px;"><div class="body-title">ID</div></li>
                                <li style="flex-basis: calc(100% - 210px);"><div class="body-title">Tên Size</div></li>
                                <li style="flex-basis: 150px;"><div class="body-title">Hành động</div></li>
                            </ul>

                            <ul class="flex flex-column">
                                @foreach ($sizes as $item)
                                    <li class="wg-product item-row" style="gap: 10px;">
                                        <div class="body-text mt-4" style="flex-basis: 60px;">{{ $item->id }}</div>
                                        <div style="flex-basis: calc(100% - 210px);">
                                            <form method="POST" action="{{ route('admin.catalog.size.update', $item->id) }}" class="d-flex">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="name" value="{{ $item->name }}" class="flex-grow me-2" required>
                                                <button type="submit" class="tf-button sm">Lưu</button>
                                            </form>
                                        </div>
                                        <div class="list-icon-function" style="flex-basis: 150px;">
                                            <form method="POST" action="{{ route('admin.catalog.size.destroy', $item->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="tf-button danger sm">
                                                    <i class="fas fa-trash-alt"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="col-12">

                        <h3 class="mb-3">Danh sách Color</h3>

                        <form method="POST" action="{{ route('admin.catalog.color.store') }}" class="form-style-1 mb-4">
                            @csrf
                            <fieldset class="name">
                                <div class="body-title visually-hidden">Thêm Color</div>
                                <div class="input-group d-flex"> {{-- THAY ĐỔI: Thêm d-flex --}}
                                    <input type="text" name="name" class="form-control me-2" placeholder="Tên Color..." aria-required="true" required> {{-- THAY ĐỔI: Bỏ flex-grow, thêm form-control me-2 --}}

                                    <button type="submit" class="tf-button" style="min-width: 120px;">
                                        <i class="fas fa-plus me-2"></i> Thêm Color
                                    </button>
                                </div>
                            </fieldset>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </form>

                        <div class="wg-table table-product-list mt-3">
                            <ul class="table-title flex mb-14" style="gap: 10px;">
                                <li style="flex-basis: 60px;"><div class="body-title">ID</div></li>
                                <li style="flex-basis: calc(100% - 210px);"><div class="body-title">Tên Color</div></li>
                                <li style="flex-basis: 150px;"><div class="body-title">Hành động</div></li>
                            </ul>

                            <ul class="flex flex-column">
                                @foreach ($colors as $item)
                                    <li class="wg-product item-row" style="gap: 10px;">
                                        <div class="body-text mt-4" style="flex-basis: 60px;">{{ $item->id }}</div>
                                        <div style="flex-basis: calc(100% - 210px);">
                                            <form method="POST" action="{{ route('admin.catalog.color.update', $item->id) }}" class="d-flex">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="name" value="{{ $item->name }}" class="flex-grow me-2" required>
                                                <button type="submit" class="tf-button sm">Lưu</button>
                                            </form>
                                        </div>
                                        <div class="list-icon-function" style="flex-basis: 150px;">
                                            <form method="POST" action="{{ route('admin.catalog.color.destroy', $item->id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="tf-button danger sm">
                                                    <i class="fas fa-trash-alt"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div> </div>
        </div>
    </div>
</div>

@endsection