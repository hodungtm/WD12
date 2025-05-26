<div class="row">
    <div class="form-group col-md-6">
        <label>Mã giảm giá</label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $discount->code ?? '') }}">
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Mô tả</label>
        <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
            value="{{ old('description', $discount->description ?? '') }}">
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Giảm theo tiền (VNĐ)</label>
        <input type="number" name="discount_amount" step="0.01"
            class="form-control @error('discount_amount') is-invalid @enderror"
            value="{{ old('discount_amount', $discount->discount_amount ?? '') }}">
        @error('discount_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Giảm theo %</label>
        <input type="number" name="discount_percent" step="0.01"
            class="form-control @error('discount_percent') is-invalid @enderror"
            value="{{ old('discount_percent', $discount->discount_percent ?? '') }}">
        @error('discount_percent') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Ngày bắt đầu</label>
        <input type="date" name="start_date"
            class="form-control @error('start_date') is-invalid @enderror"
            value="{{ old('start_date', isset($discount->start_date) ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : '') }}">
        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Ngày kết thúc</label>
        <input type="date" name="end_date"
            class="form-control @error('end_date') is-invalid @enderror"
            value="{{ old('end_date', isset($discount->end_date) ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') : '') }}">
        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Số lượt sử dụng tối đa</label>
        <input type="number" name="max_usage" class="form-control @error('max_usage') is-invalid @enderror"
            value="{{ old('max_usage', $discount->max_usage ?? '') }}">
        @error('max_usage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Giá trị đơn tối thiểu</label>
        <input type="number" step="0.01" name="min_order_amount"
            class="form-control @error('min_order_amount') is-invalid @enderror"
            value="{{ old('min_order_amount', $discount->min_order_amount ?? '') }}">
        @error('min_order_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
