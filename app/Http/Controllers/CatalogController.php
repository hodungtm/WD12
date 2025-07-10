<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CatalogController extends Controller
{
    //
    public function index()
    {
        $sizes = Size::orderBy('id', 'desc')->get();
        $colors = Color::orderBy('id', 'desc')->get();

        return view('admin.catalog.index', compact('sizes', 'colors'));
    }

    // Size
    public function storeSize(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:sizes,name',
    ], [
        'name.required' => 'Vui lòng nhập tên Size.',
        'name.unique' => 'Tên Size đã tồn tại.',
        'name.max' => 'Tên Size không được vượt quá 255 ký tự.',
    ]);

    Size::create(['name' => $request->name]);

    return redirect()->back()->with('success', 'Đã thêm Size mới!');
}



   public function updateSize(Request $request, Size $size)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:sizes,name,' . $size->id,
    ], [
        'name.required' => 'Vui lòng nhập tên Size.',
        'name.unique' => 'Tên Size đã tồn tại.',
        'name.max' => 'Tên Size không được vượt quá 255 ký tự.',
    ]);

    $size->update(['name' => $request->name]);

    return redirect()->back()->with('success', 'Đã cập nhật Size!');
}

public function destroySize(Size $size)
{
    $size->delete();
    return redirect()->back()->with('success', 'Đã xóa Size!');
}


    // Color
public function storeColor(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:colors,name',
    ], [
        'name.required' => 'Vui lòng nhập tên màu.',
        'name.unique' => 'Tên màu đã tồn tại.',
        'name.max' => 'Tên màu không được vượt quá 255 ký tự.',
    ]);

    Color::create(['name' => $request->name]);

    return redirect()->back()->with('success', 'Đã thêm Color mới!');
}



 public function updateColor(Request $request, Color $color)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:colors,name,' . $color->id,
    ], [
        'name.required' => 'Vui lòng nhập tên màu.',
        'name.unique' => 'Tên màu đã tồn tại.',
        'name.max' => 'Tên màu không được vượt quá 255 ký tự.',
    ]);

    $color->update(['name' => $request->name]);

    return redirect()->back()->with('success', 'Đã cập nhật Color!');
}



    public function destroyColor(Color $color)
    {
        $color->delete();
        return redirect()->back()->with('success', 'Đã xóa Color!');
    }
}
