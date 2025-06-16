<?php

namespace App\Exports;

use App\Models\Discount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DiscountsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Discount::select(
            'id',
            'code',
            'description',
            'type',
            'discount_amount',
            'discount_percent',
            'max_discount_amount',
            'min_order_amount',
            'max_usage',
            'start_date',
            'end_date'
        )->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Mã giảm giá',
            'Mô tả',
            'Loại mã',
            'Giảm theo tiền (VNĐ)',
            'Giảm theo %',
            'Giá trị giảm tối đa (VNĐ)',
            'Giá trị đơn tối thiểu (VNĐ)',
            'Số lượt dùng tối đa',
            'Ngày bắt đầu',
            'Ngày kết thúc',
        ];
    }
}
