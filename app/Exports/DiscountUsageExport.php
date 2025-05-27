<?php

namespace App\Exports;

use App\Models\DiscountUsage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DiscountUsageExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return DiscountUsage::with('discount', 'user')->get()->map(function ($usage) {
            return [
                'Mã giảm giá' => $usage->discount->code,
                'Người dùng' => optional($usage->user)->name,
                'Mã đơn hàng' => $usage->order_code,
                'Ngày sử dụng' => $usage->used_at,
            ];
        });
    }

    public function headings(): array
    {
        return ['Mã giảm giá', 'Người dùng', 'Mã đơn hàng', 'Ngày sử dụng'];
    }
}

