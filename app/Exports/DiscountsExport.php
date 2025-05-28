<?php

namespace App\Exports;

use App\Models\Discount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DiscountsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Discount::select('id', 'code', 'description', 'discount_amount', 'discount_percent', 'start_date', 'end_date', 'max_usage', 'min_order_amount')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Code',
            'Description',
            'Discount Amount',
            'Discount Percent',
            'Start Date',
            'End Date',
            'Max Usage',
            'Min Order Amount',
        ];
    }
}
