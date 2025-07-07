<div class="wg-box">
    <form class="form-style-1 row">
        <fieldset class="col-md-6">
            <div class="body-title">Mã giảm giá <span class="tf-color-1">*</span></div>
            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                value="{{ old('code', $discount->code ?? '') }}" placeholder="Nhập mã giảm giá">
            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </fieldset>
        <fieldset class="col-md-6">
            <div class="body-title">Loại mã <span class="tf-color-1">*</span></div>
            <select name="type" class="form-control @error('type') is-invalid @enderror">
                <option value="">-- Chọn loại mã --</option>
                <option value="order" {{ old('type', $discount->type ?? '') == 'order' ? 'selected' : '' }}>Theo đơn hàng</option>
                <option value="shipping" {{ old('type', $discount->type ?? '') == 'shipping' ? 'selected' : '' }}>Giảm phí vận chuyển</option>
            </select>
            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </fieldset>
        <fieldset class="col-md-12">
            <div class="body-title">Mô tả</div>
            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                      rows="3" placeholder="Nhập mô tả cho mã giảm giá">{{ old('description', $discount->description ?? '') }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </fieldset>
        <fieldset class="col-md-6">
            <div class="body-title">Ngày bắt đầu <span class="tf-color-1">*</span></div>
            <input type="date" name="start_date"
                   class="form-control @error('start_date') is-invalid @enderror"
                   value="{{ old('start_date', isset($discount->start_date) ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d') : '') }}">
            @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </fieldset>
        <fieldset class="col-md-6">
            <div class="body-title">Ngày kết thúc <span class="tf-color-1">*</span></div>
            <input type="date" name="end_date"
                   class="form-control @error('end_date') is-invalid @enderror"
                   value="{{ old('end_date', isset($discount->end_date) ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d') : '') }}">
            @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </fieldset>
        <fieldset class="col-md-12">
            <div class="body-title"><strong>Loại giảm giá</strong> <span class="tf-color-1">*</span></div>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="discount_type" id="type_percent" value="percent"
                        {{ old('discount_type', $discount->discount_type ?? 'percent') == 'percent' ? 'checked' : '' }}>
                    <label class="form-check-label" for="type_percent">Giảm theo phần trăm</label>
                </div>
            </div>
        </fieldset>
        <fieldset class="col-md-6" id="percent_input" style="display: none;">
            <div class="body-title">Giảm theo phần trăm (%)</div>
            <input type="number" name="discount_percent" step="0.01" min="0" max="100"
                class="form-control @error('discount_percent') is-invalid @enderror"
                value="{{ old('discount_percent', $discount->discount_percent ?? '') }}"
                placeholder="Nhập phần trăm giảm">
            @error('discount_percent') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </fieldset>
        <fieldset class="col-md-6">
            <div class="body-title">Phần trăm giảm giá (%) <span class="tf-color-1">*</span></div>
            <input type="number" name="discount_percent" step="0.01" min="1" max="100"
                   class="form-control @error('discount_percent') is-invalid @enderror"
                   value="{{ old('discount_percent', $discount->discount_percent ?? '') }}"
                   placeholder="Nhập phần trăm giảm (ví dụ: 10)">
            @error('discount_percent')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">Bắt buộc nhập giá trị từ 1 đến 100 (%). Không được để trống.</small>
        </fieldset>
        <fieldset class="col-md-6">
            <div class="body-title">Số lượt sử dụng tối đa</div>
            <input type="number" name="max_usage" min="0"
                   class="form-control @error('max_usage') is-invalid @enderror"
                   value="{{ old('max_usage', $discount->max_usage ?? '') }}"
                   placeholder="Không giới hạn nếu để trống">
            @error('max_usage') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </fieldset>
        <fieldset class="col-md-6">
            <div class="body-title">Giá trị đơn hàng tối thiểu (VNĐ)</div>
            <input type="number" step="1000" min="0" name="min_order_amount"
                   class="form-control @error('min_order_amount') is-invalid @enderror"
                   value="{{ old('min_order_amount', $discount->min_order_amount ?? '') }}"
                   placeholder="0">
            @error('min_order_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-muted">Đơn hàng phải đạt giá trị này để sử dụng mã.</small>
        </fieldset>
        <fieldset class="col-md-6">
            <div class="body-title">Giá trị giảm tối đa (VNĐ)</div>
            <input type="number" step="1000" min="0" name="max_discount_amount"
                   class="form-control @error('max_discount_amount') is-invalid @enderror"
                   value="{{ old('max_discount_amount', $discount->max_discount_amount ?? '') }}"
                   placeholder="0">
            @error('max_discount_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <small class="form-text text-muted">Số tiền tối đa mã giảm giá có thể trừ.</small>
        </fieldset>
        <div class="col-12 mt-4">
            <button class="tf-button w208" type="submit">Lưu</button>
        </div>
    </form>
</div>
