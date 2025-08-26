<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\DiscountUsage;
use App\Exports\DiscountsExport;
use App\Imports\DiscountsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $query = Discount::query();

        if ($request->filled('search')) {
            $query->where('code', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        if ($request->has('type') && in_array($request->type, ['order', 'product', 'shipping'])) {
            $query->where('type', $request->type);
        }

        $discounts = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    private function getValidationRules($isUpdate = false, $discountId = null)
    {
        $codeRule = 'required|max:255';
        if ($isUpdate && $discountId) {
            $codeRule .= '|unique:discounts,code,' . $discountId;
        } else {
            $codeRule .= '|unique:discounts,code';
        }

        return [
            'code' => $codeRule,
            'description' => 'nullable|max:255',
            'type' => 'required|in:order,product,shipping',
            'discount_percent' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_usage' => 'nullable|integer|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0'
        ];
    }

    private function getValidationMessages()
    {
        return [
            'code.required' => 'Mã giảm giá không được để trống.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'code.max' => 'Mã giảm giá không được vượt quá 255 ký tự.',
            'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
            'type.required' => 'Vui lòng chọn loại mã giảm giá.',
            'type.in' => 'Loại mã giảm giá không hợp lệ.',
            'discount_percent.required' => 'Phần trăm giảm giá là bắt buộc.',
            'discount_percent.numeric' => 'Phần trăm giảm giá phải là số.',
            'discount_percent.min' => 'Phần trăm giảm giá tối thiểu là 1%.',
            'discount_percent.max' => 'Phần trăm giảm giá tối đa là 100%.',
            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không đúng định dạng.',
            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không đúng định dạng.',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'max_usage.integer' => 'Số lượt sử dụng phải là số nguyên.',
            'max_usage.min' => 'Số lượt sử dụng tối thiểu là 1.',
            'min_order_amount.numeric' => 'Giá trị đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Giá trị đơn hàng tối thiểu không được âm.',
            'max_discount_amount.numeric' => 'Giá trị giảm tối đa phải là số.',
            'max_discount_amount.min' => 'Giá trị giảm tối đa không được âm.'
        ];
    }

    public function store(Request $request)
{
    $validated = $request->validate(
        $this->getValidationRules(),
        $this->getValidationMessages()
    );

    // Chỉ kiểm tra nếu cả hai giá trị đều được nhập
    if (
        !is_null($request->min_order_amount) &&
        !is_null($request->max_discount_amount) &&
        $request->min_order_amount > 0 &&
        $request->max_discount_amount > $request->min_order_amount
    ) {
        return back()
            ->withErrors(['max_discount_amount' => 'Giá trị giảm tối đa không được lớn hơn giá trị đơn hàng tối thiểu.'])
            ->withInput();
    }

    if (Discount::create($validated)) {
        return redirect()->route('admin.discounts.index')->with('success', 'Tạo mã giảm giá thành công!');
    }

    return redirect()->back()->with('error', 'Có lỗi xảy ra khi tạo mã giảm giá!');
}


    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, $id)
{
    $discount = Discount::findOrFail($id);

    $validated = $request->validate(
        $this->getValidationRules(true, $discount->id),
        $this->getValidationMessages()
    );

    // Chỉ kiểm tra nếu cả hai giá trị đều được nhập
    if (
        !is_null($request->min_order_amount) &&
        !is_null($request->max_discount_amount) &&
        $request->min_order_amount > 0 &&
        $request->max_discount_amount > $request->min_order_amount
    ) {
        return back()
            ->withErrors(['max_discount_amount' => 'Giá trị giảm tối đa không được lớn hơn giá trị đơn hàng tối thiểu.'])
            ->withInput();
    }

    if ($discount->update($validated)) {
        return redirect()->route('admin.discounts.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật mã giảm giá!');
}

    public function destroy(Discount $discount)
    {
        if ($discount->delete()) {
            return redirect()->route('admin.discounts.index')->with('success', 'Xóa mã giảm giá thành công!');
        }

        return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa mã giảm giá!');
    }

    public function report()
    {
        $usages = DiscountUsage::with(['discount', 'user'])->latest()->paginate(20);
        return view('admin.discounts.report', compact('usages'));
    }

    public function exportExcel()
    {
        return Excel::download(new DiscountsExport, 'discounts.xlsx');
    }

    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.discounts.show', compact('discount'));
    }


}
