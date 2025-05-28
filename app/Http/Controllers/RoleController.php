<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(10);
        return view('Admin.Roles.index', compact('roles'));
    }

    public function create()
    {
        return view('Admin.Roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles',
            'description' => 'nullable'
        ]);

        Role::create($request->only('name', 'description'));

        return redirect()->route('admin.roles.index')->with('success', 'Tạo role thành công');
    }

    public function edit(Role $role)
    {
        return view('Admin.Roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'description' => 'nullable'
        ]);

        $role->update($request->only('name', 'description'));

        return redirect()->route('admin.roles.index')->with('success', 'Cập nhật role thành công');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Xóa role thành công');
    }
}
