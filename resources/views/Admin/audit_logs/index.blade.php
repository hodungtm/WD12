@extends('Admin.Layouts.AdminLayout')

@section('main')
    <div class="app-title">
        <h1>Lịch sử hoạt động</h1>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Người thao tác</th>
                <th>Hành động</th>
                <th>Đối tượng</th>
                <th>Mô tả</th>
                <th>IP</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->admin ? $log->admin->name : 'N/A' }}</td>
                    <td>{{ ucfirst($log->action) }}</td>
                    <td>{{ $log->target_type }} #{{ $log->target_id }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $logs->withQueryString()->links() }}
@endsection
