<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('Admin.Brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('logo', 'public'); // Lưu vào storage/app/public/logos
        }

        Brand::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'logo' => $logoPath,
        ]);

        return redirect()->route('admin.brand.index')->with('success', 'Thương hiệu được thêm.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }


    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Xóa logo cũ nếu có
            if ($brand->logo) {
                Storage::delete('public/' . $brand->logo);
            }

            $logo = $request->file('logo');
            $logoPath = $logo->store('logos', 'public'); // Lưu vào storage/app/public/logos

            $brand->logo = $logoPath;
        }

        $brand->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('admin.brand.index')->with('success', 'Thương hiệu được cập nhật.');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->logo) {
            Storage::delete('public/' . $brand->logo);
        }

        $brand->delete();

        return redirect()->route('admin.brand.index')->with('success', 'Thương hiệu được xóa.');
    }
}
