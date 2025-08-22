@extends('admin.layouts.AdminLayout')

@section('main')
<style>
    .fs-xl {
        font-size: 1.75rem;
    }

    .btn-xl {
        padding: 0.75rem 2rem;
        font-size: 1.25rem;
    }

    .form-control-xl {
        padding: 0.75rem 1rem;
        font-size: 1.25rem;
    }
</style>

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap">
    <h3 class="mb-0 fs-xl fw-bold">Quản lý Size & Color</h3>
    <nav>
        <ol class="breadcrumb mb-0 fs-5">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Size & Color</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">

                {{-- Quản lý Size --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 fs-4 fw-bold">Danh sách Size</h5>
                    <button class="btn btn-primary btn-xl" data-bs-toggle="modal" data-bs-target="#modalSize" onclick="openSizeModal('add')">
                        <i class="fas fa-plus me-1"></i> Thêm Size
                    </button>
                </div>

                <table class="table table-bordered table-striped align-middle fs-5">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Tên Size</th>
                            <th style="width: 200px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sizes as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                <button class="btn btn-warning btn-xl me-2" data-bs-toggle="modal" data-bs-target="#modalSize" onclick="openSizeModal('edit', {{ $item->id }}, '{{ $item->name }}')">
                                    <i class="fas fa-edit me-1"></i> Sửa
                                </button>
                                <form method="POST" action="{{ route('admin.catalog.size.destroy', $item->id) }}" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xl">
                                        <i class="fas fa-trash-alt me-1"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr class="my-5">

                {{-- Quản lý Color --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 fs-4 fw-bold">Danh sách Color</h5>
                    <button class="btn btn-primary btn-xl" data-bs-toggle="modal" data-bs-target="#modalColor" onclick="openColorModal('add')">
                        <i class="fas fa-plus me-1"></i> Thêm Color
                    </button>
                </div>

                <table class="table table-bordered table-striped align-middle fs-5">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Tên Color</th>
                            <th style="width: 200px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($colors as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                <button class="btn btn-warning btn-xl me-2" data-bs-toggle="modal" data-bs-target="#modalColor" onclick="openColorModal('edit', {{ $item->id }}, '{{ $item->name }}')">
                                    <i class="fas fa-edit me-1"></i> Sửa
                                </button>
                                <form method="POST" action="{{ route('admin.catalog.color.destroy', $item->id) }}" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xl">
                                        <i class="fas fa-trash-alt me-1"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

{{-- Modal Size --}}
<div class="modal fade" id="modalSize" tabindex="-1" aria-labelledby="modalSizeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formSize">
            @csrf
            <input type="hidden" name="_method" value="POST" id="sizeMethod">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-4 fw-bold" id="modalSizeLabel">Thêm / Sửa Size</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control form-control-xl" id="sizeName" placeholder="Tên size..." required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-xl">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Color --}}
<div class="modal fade" id="modalColor" tabindex="-1" aria-labelledby="modalColorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formColor">
            @csrf
            <input type="hidden" name="_method" value="POST" id="colorMethod">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-4 fw-bold" id="modalColorLabel">Thêm / Sửa Color</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control form-control-xl" id="colorName" placeholder="Tên color..." required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-xl">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- JavaScript xử lý Modal --}}
<script>
    function openSizeModal(action, id = null, name = '') {
        const form = document.getElementById('formSize');
        const nameInput = document.getElementById('sizeName');
        const methodInput = document.getElementById('sizeMethod');

        if (action === 'add') {
            form.action = "{{ route('admin.catalog.size.store') }}";
            methodInput.value = "POST";
            nameInput.value = '';
        } else {
            form.action = "/admin/catalog/size/" + id;
            methodInput.value = "PUT";
            nameInput.value = name;
        }
    }

    function openColorModal(action, id = null, name = '') {
        const form = document.getElementById('formColor');
        const nameInput = document.getElementById('colorName');
        const methodInput = document.getElementById('colorMethod');

        if (action === 'add') {
            form.action = "{{ route('admin.catalog.color.store') }}";
            methodInput.value = "POST";
            nameInput.value = '';
        } else {
            form.action = "/admin/catalog/color/" + id;
            methodInput.value = "PUT";
            nameInput.value = name;
        }
    }
</script>
@endsection
