@extends('Admin.Layouts.AdminLayout')
@section('main')
<h1>Chi tiết thuộc tính</h1>

    <div class="card p-4 shadow-md rounded">
        <h5 class="mb-3"><strong>Tên thuộc tính:</strong> {{ $attribute->name }}</h5>
        <h5 class="mb-3"><strong>Slug:</strong> {{ $attribute->slug }}</h5>

        <h5>Các giá trị thuộc tính</h5>
        @if($attribute->values->isEmpty()) 
            <p>Không có giá trị nào.</p>
        @else
            <ul class="list-group mt-3">
                @foreach ($attribute->values as $value)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $value->value }}
                        <span class="badge bg-info text-dark">{{ $value->slug }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('admin.attribute.index') }}" class="btn btn-secondary mt-4">Trở về</a>
    </div>
@endsection