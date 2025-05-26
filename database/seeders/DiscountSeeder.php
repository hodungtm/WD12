<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $discounts = [
            [
                'code' => 'SALE10K',
                'description' => 'Giảm 10.000đ cho đơn từ 100.000đ',
                'discount_amount' => 10000,
                'discount_percent' => 0,
                'start_date' => $now->copy()->subDays(2),
                'end_date' => $now->copy()->addDays(10),
                'max_usage' => 100,
                'min_order_amount' => 100000,
            ],
            [
                'code' => 'SHIPFREE',
                'description' => 'Miễn phí giao hàng',
                'discount_amount' => 30000,
                'discount_percent' => 0,
                'start_date' => $now,
                'end_date' => $now->copy()->addDays(15),
                'max_usage' => 200,
                'min_order_amount' => 50000,
            ],
            [
                'code' => 'GIAM20',
                'description' => 'Giảm 20% toàn bộ sản phẩm',
                'discount_amount' => 0,
                'discount_percent' => 20,
                'start_date' => $now->copy()->subDays(1),
                'end_date' => $now->copy()->addDays(7),
                'max_usage' => 300,
                'min_order_amount' => 0,
            ],
            [
                'code' => 'WELCOME50',
                'description' => 'Khách hàng mới giảm 50%',
                'discount_amount' => 0,
                'discount_percent' => 50,
                'start_date' => $now,
                'end_date' => $now->copy()->addDays(30),
                'max_usage' => 500,
                'min_order_amount' => 0,
            ],
            [
                'code' => 'VIP30K',
                'description' => 'Giảm ngay 30.000đ cho khách VIP',
                'discount_amount' => 30000,
                'discount_percent' => 0,
                'start_date' => $now,
                'end_date' => $now->copy()->addDays(20),
                'max_usage' => 50,
                'min_order_amount' => 150000,
            ],
        ];

        foreach ($discounts as $discount) {
            Discount::create($discount);
        }

        // Tạo thêm 5 mã ngẫu nhiên để test
        for ($i = 1; $i <= 5; $i++) {
            Discount::create([
                'code' => 'RAND' . strtoupper(Str::random(5)),
                'description' => 'Mã giảm ngẫu nhiên ' . $i,
                'discount_amount' => rand(5000, 50000),
                'discount_percent' => rand(0, 30),
                'start_date' => $now->copy()->subDays(rand(0, 5)),
                'end_date' => $now->copy()->addDays(rand(5, 30)),
                'max_usage' => rand(20, 200),
                'min_order_amount' => rand(0, 200000),
            ]);
        }
    }
}
