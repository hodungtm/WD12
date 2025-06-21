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
            'name' => 'required|max:255'
        ]);

        Size::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Đã thêm Size mới!');
    }

    public function updateSize(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $size->update([
            'name' => $request->name
        ]);

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
            'name' => 'required|max:255'
        ]);

        Color::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Đã thêm Color mới!');
    }

    public function updateColor(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|max:255'
        ]);

        $color->update([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật Color!');
    }

    public function destroyColor(Color $color)
    {
        $color->delete();
        return redirect()->back()->with('success', 'Đã xóa Color!');
    }
}
