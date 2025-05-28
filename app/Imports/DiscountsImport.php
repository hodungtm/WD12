<?php

namespace App\Imports;

use App\Models\Discount;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DiscountsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Discount([
            'code' => $row['code'],
            'description' => $row['description'],
            'type' => $row['type'],
            'discount_amount' => $row['discount_amount'],
            'discount_percent' => $row['discount_percent'],
            'start_date' => $row['start_date'],
            'end_date' => $row['end_date'],
            'max_usage' => $row['max_usage'],
            'min_order_amount' => $row['min_order_amount'],
        ]);
    }
}

