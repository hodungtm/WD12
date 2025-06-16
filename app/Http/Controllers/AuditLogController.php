<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    // Hiển thị danh sách lịch sử hoạt động
    public function index(Request $request)
    {
        $query = AuditLog::with('admin');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('description', 'like', "%$search%");
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.audit_logs.index', compact('logs'));
    }
}
