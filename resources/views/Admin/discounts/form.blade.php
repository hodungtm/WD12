<div class="wg-box mb-30">
    <fieldset class="code">
        <div class="body-title mb-10">Mã giảm giá <span class="tf-color-1">*</span></div>
        <input type="text" name="code" class="form-control mb-10" value="{{ old('code', $discount->code ?? '') }}">
        @error('code')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </fieldset>
    <fieldset class="type">
        <div class="body-title mb-10">Loại mã <span class="tf-color-1">*</span></div>
        <select name="type" class="form-control mb-10">
            <option value="">-- Chọn loại mã --</option>
            @foreach (\App\Models\Discount::getTypes() as $value => $label)
                <option value="{{ $value }}" {{ old('type', $discount->type ?? '') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('type')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </fieldset>
    <fieldset class="description">
        <div class="body-title mb-10">Mô tả</div>
        <textarea name="description" class="form-control mb-10"
            rows="2">{{ old('description', $discount->description ?? '') }}</textarea>
        @error('description')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </fieldset>
    <fieldset class="start-date">
        <div class="body-title mb-10">Ngày bắt đầu <span class="tf-color-1">*</span></div>
        <input type="date" name="start_date" class="form-control mb-10"
            value="{{ old('start_date', isset($discount->start_date) ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : '') }}">
        @error('start_date')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </fieldset>
    <fieldset class="end-date">
        <div class="body-title mb-10">Ngày kết thúc <span class="tf-color-1">*</span></div>
        <input type="date" name="end_date" class="form-control mb-10"
            value="{{ old('end_date', isset($discount->end_date) ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') : '') }}">
        @error('end_date')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </fieldset>
    <div class="flex gap20">
        <fieldset class="discount-percent" style="flex:1;">
            <div class="body-title mb-10">Phần trăm giảm giá (%)</div>
            <input type="number" name="discount_percent" step="0.01" min="0" max="100" class="form-control mb-10"
                value="{{ old('discount_percent', $discount->discount_percent ?? '') }}"
                placeholder="Nhập phần trăm giảm">
            @error('discount_percent')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </fieldset>

        <fieldset class="discount-amount" style="flex:1;">
            <div class="body-title mb-10">Số tiền giảm cố định</div>
            <input type="number" name="discount_amount" step="1000" min="0" class="form-control mb-10"
                value="{{ old('discount_amount', $discount->discount_amount ?? '') }}" placeholder="Nhập số tiền giảm">
            @error('discount_amount')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </fieldset>
    </div>
    <fieldset class="max-usage" style="flex:1;">
        <div class="body-title mb-10">Số lượt sử dụng</div>
        <input type="number" name="max_usage" min="0" class="form-control mb-10"
            value="{{ old('max_usage', $discount->max_usage ?? '') }}" placeholder="Không giới hạn">
        @error('max_usage')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </fieldset>
    <fieldset class="min-order-amount" style="flex:1;">
        <div class="body-title mb-10">Đơn hàng tối thiểu</div>
        <input type="number" step="1000" min="0" name="min_order_amount" class="form-control mb-10"
            value="{{ old('min_order_amount', $discount->min_order_amount ?? '') }}" placeholder="0">
        @error('min_order_amount')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </fieldset>
    <fieldset class="max-discount-amount" style="flex:1;">
        <div class="body-title mb-10">Giảm tối đa</div>
        <input type="number" step="1000" min="0" name="max_discount_amount" class="form-control mb-10"
            value="{{ old('max_discount_amount', $discount->max_discount_amount ?? '') }}" placeholder="0">
        @error('max_discount_amount')
            <div class="text-danger mt-1 small">{{ $message }}</div>
        @enderror
    </fieldset>
    <div class="mt-4">
        <button class="tf-button btn-sm w-auto px-3 py-2" type="submit">
            <i class="icon-save"></i> Lưu mã giảm giá
        </button>
        <a href="{{ route('admin.discounts.index') }}" class="tf-button style-3 btn-sm w-auto px-3 py-2">
            <i class="icon-x"></i> Hủy bỏ
        </a>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const percentInput = document.querySelector('input[name="discount_percent"]');
    const amountInput = document.querySelector('input[name="discount_amount"]');
    const maxDiscountInput = document.querySelector('input[name="max_discount_amount"]');
    const maxDiscountField = maxDiscountInput.closest('fieldset');

    function toggleFields() {
        if (percentInput.value) {
            amountInput.value = '';
            // amountInput.disabled = true; ❌ bỏ disable
            maxDiscountField.style.display = 'block'; // hiển thị khi giảm %
        } else if (amountInput.value) {
            percentInput.value = '';
            // percentInput.disabled = true; ❌ bỏ disable
            maxDiscountField.style.display = 'none'; // ẩn khi giảm cố định
            maxDiscountInput.value = ''; // clear luôn để không lưu nhầm
        } else {
            // Khi cả 2 trống thì để user nhập tự do
            amountInput.disabled = false;
            percentInput.disabled = false;
            maxDiscountField.style.display = 'block'; // default
        }
    }

    percentInput.addEventListener('input', toggleFields);
    amountInput.addEventListener('input', toggleFields);

    toggleFields(); // chạy 1 lần khi load trang
});


</script>