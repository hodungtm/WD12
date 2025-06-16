<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    // Hiển thị tất cả thuộc tính
    public function index()
    {
        $attributes = Attribute::all();
        return view('admin.attributes.index', compact('attributes'));
    }

    // Hiển thị form thêm
    public function create()
    {
        return view('admin.attributes.create');
    }

    // Lưu thuộc tính vào db
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:attributes,name',
        ]);

        $attribute = new Attribute([
            'name' => $request->input('name'),
        ]);
        $attribute->save();

        return redirect()->route('admin.attribute.create')->with('success', 'Thêm thuộc tính thành công!');
    }

    public function show($id)
    {
        $attribute = Attribute::with('values')->findOrFail($id);
        return view('admin.attributes.show', compact('attribute'));
    }


    // Hiển thị form sửa
    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    // Cập nhật thuộc tính
    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'required|unique:attributes,name,' . $attribute->id,
        ]);

        $attribute->name = $request->input('name');
        $attribute->save();

        return redirect()->route('admin.attribute.edit')->with('success', 'Cập nhật thuộc tính thành công!');
    }

    // Xóa thuộc tính
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return redirect()->route('admin.attribute.index')->with('success', 'Xóa thuộc tính thành công!');
    }
}
