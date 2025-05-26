<div class="mb-3">
    <label>Mã giảm giá</label>
    <input type="text" name="code" class="form-control" value="{{ old('code', $discount->code ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Mô tả</label>
    <input type="text" name="description" class="form-control" value="{{ old('description', $discount->description ?? '') }}">
</div>

<div class="mb-3">
    <label>Số tiền giảm (VNĐ)</label>
    <input type="number" name="discount_amount" class="form-control" value="{{ old('discount_amount', $discount->discount_amount ?? '') }}">
</div>

<div class="mb-3">
    <label>Phần trăm giảm (%)</label>
    <input type="number" name="discount_percent" class="form-control" value="{{ old('discount_percent', $discount->discount_percent ?? '') }}">
</div>

<div class="mb-3">
    <label>Ngày bắt đầu</label>
    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($discount->start_date ?? null)->format('Y-m-d')) }}" required>
</div>

<div class="mb-3">
    <label>Ngày kết thúc</label>
    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($discount->end_date ?? null)->format('Y-m-d')) }}" required>
</div>

<div class="mb-3">
    <label>Lượt sử dụng tối đa</label>
    <input type="number" name="max_usage" class="form-control" value="{{ old('max_usage', $discount->max_usage ?? '') }}">
</div>

<div class="mb-3">
    <label>Đơn hàng tối thiểu để áp dụng</label>
    <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $discount->min_order_amount ?? '') }}">
</div>
