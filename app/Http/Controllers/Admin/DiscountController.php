<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'code' => 'required|unique:discounts,code',
        'description' => 'nullable|string|max:255',
        'type' => 'required|in:order,shipping,product',
        'discount_amount' => 'nullable|numeric|min:0',
        'discount_percent' => 'nullable|numeric|min:0|max:100',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'max_usage' => 'nullable|integer|min:1',
        'min_order_amount' => 'nullable|numeric|min:0',
    ]);

    Discount::create($request->all());

    return redirect()->route('discounts.index')->with('success', 'Đã tạo mã giảm giá thành công!');
}


    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
{
    $request->validate([
        'code' => 'required|unique:discounts,code,' . $discount->id,
        'description' => 'nullable|string|max:255',
        'type' => 'required|in:order,shipping,product',
        'discount_amount' => 'nullable|numeric|min:0',
        'discount_percent' => 'nullable|numeric|min:0|max:100',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'max_usage' => 'nullable|integer|min:1',
        'min_order_amount' => 'nullable|numeric|min:0',
    ]);

    $discount->update($request->all());

    return redirect()->route('discounts.index')->with('success', 'Cập nhật mã giảm giá thành công!');
}


    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()->route('discounts.index')->with('success', 'Đã xóa');
    }
    public function report()
{
    $discounts = Discount::withCount('usages')->get();
    return view('admin.discounts.report', compact('discounts'));
}
}
