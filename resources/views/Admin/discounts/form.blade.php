<div class="row">
    <div class="form-group col-md-6">
        <label>Mã giảm giá <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
            value="{{ old('code', $discount->code ?? '') }}" placeholder="Nhập mã giảm giá">
        @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Loại mã <span class="text-danger">*</span></label>
        <select name="type" class="form-control @error('type') is-invalid @enderror">
            <option value="">-- Chọn loại mã --</option>
            <option value="order" {{ old('type', $discount->type ?? '') == 'order' ? 'selected' : '' }}>Theo đơn hàng</option>
            <option value="product" {{ old('type', $discount->type ?? '') == 'product' ? 'selected' : '' }}>Theo sản phẩm</option>
            <option value="shipping" {{ old('type', $discount->type ?? '') == 'shipping' ? 'selected' : '' }}>Giảm phí ship</option>
        </select>
        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-12">
        <label>Mô tả</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                  rows="3" placeholder="Nhập mô tả cho mã giảm giá">{{ old('description', $discount->description ?? '') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Giảm theo tiền (VNĐ)</label>
        <input type="number" name="discount_amount" step="1000" min="0"
            class="form-control @error('discount_amount') is-invalid @enderror"
            value="{{ old('discount_amount', $discount->discount_amount ?? '') }}"
            placeholder="0">
        @error('discount_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <small class="form-text text-muted">Để trống nếu sử dụng giảm theo %</small>
    </div>

    <div class="form-group col-md-6">
        <label>Giảm theo % (0-100%)</label>
        <input type="number" name="discount_percent" step="0.01" min="0" max="100"
            class="form-control @error('discount_percent') is-invalid @enderror"
            value="{{ old('discount_percent', $discount->discount_percent ?? '') }}"
            placeholder="0">
        @error('discount_percent') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <small class="form-text text-muted">Để trống nếu sử dụng giảm theo tiền</small>
    </div>

    <div class="form-group col-md-6">
        <label>Ngày bắt đầu <span class="text-danger">*</span></label>
        <input type="date" name="start_date"
            class="form-control @error('start_date') is-invalid @enderror"
            value="{{ old('start_date', isset($discount->start_date) ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : '') }}">
        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Ngày kết thúc <span class="text-danger">*</span></label>
        <input type="date" name="end_date"
            class="form-control @error('end_date') is-invalid @enderror"
            value="{{ old('end_date', isset($discount->end_date) ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') : '') }}">
        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Số lượt sử dụng tối đa</label>
        <input type="number" name="max_usage" min="0"
            class="form-control @error('max_usage') is-invalid @enderror"
            value="{{ old('max_usage', $discount->max_usage ?? '') }}"
            placeholder="Không giới hạn nếu để trống">
        @error('max_usage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group col-md-6">
        <label>Giá trị đơn tối thiểu (VNĐ)</label>
        <input type="number" step="1000" min="0" name="min_order_amount"
            class="form-control @error('min_order_amount') is-invalid @enderror"
            value="{{ old('min_order_amount', $discount->min_order_amount ?? '') }}"
            placeholder="0">
        @error('min_order_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <small class="form-text text-muted">Giá trị đơn hàng tối thiểu để áp dụng mã</small>
    </div>
</div>
