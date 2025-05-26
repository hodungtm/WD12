<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
   public function index(Request $request)
{
    $query = Discount::query();

    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where('code', 'like', '%' . $search . '%')
              ->orWhere('description', 'like', '%' . $search . '%');
    }

    $discounts = $query->orderBy('created_at', 'desc')->paginate(10);

    return view('admin.discounts.index', compact('discounts'));
}

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'code' => 'required|unique:discounts,code|max:255',
        'description' => 'nullable|max:255',
        'type' => 'required|in:order,product,shipping',
        'discount_amount' => 'nullable|numeric|min:0',
        'discount_percent' => 'nullable|numeric|min:0|max:100',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'max_usage' => 'nullable|integer|min:0',
        'min_order_amount' => 'nullable|numeric|min:0',
    ]);

    Discount::create($validated);

    return redirect()->route('discounts.index')->with('success', 'Tạo mã giảm giá thành công!');
}


    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, $id)
{
    $discount = Discount::findOrFail($id);

    $validated = $request->validate([
        'code' => 'required|max:255|unique:discounts,code,' . $discount->id,
        'description' => 'nullable|max:255',
        'type' => 'required|in:order,product,shipping',
        'discount_amount' => 'nullable|numeric|min:0',
        'discount_percent' => 'nullable|numeric|min:0|max:100',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'max_usage' => 'nullable|integer|min:0',
        'min_order_amount' => 'nullable|numeric|min:0',
    ]);

    $discount->update($validated);

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
