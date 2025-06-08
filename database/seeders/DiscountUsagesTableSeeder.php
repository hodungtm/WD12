<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiscountUsage;
use App\Models\Discount;
use App\Models\User;
use Carbon\Carbon;

class DiscountUsagesTableSeeder extends Seeder
{
    public function run()
{
    DiscountUsage::truncate();

    $discount1 = Discount::where('code', 'SUMMER21')->first();
    $discount2 = Discount::where('code', 'NEWYEAR22')->first();

    if (!$discount1 || !$discount2) {
        $this->command->error('Discounts not found. Please run DiscountsTableSeeder first.');
        return; 
    }

    $user1 = User::first();
    $userId = $user1 ? $user1->id : null;

    DiscountUsage::create([
        'discount_id' => $discount1->id,
        'user_id' => $userId,
        'order_code' => 'ORD001',
        'used_at' => Carbon::now()->subDays(2),
    ]);

    DiscountUsage::create([
        'discount_id' => $discount2->id,
        'user_id' => $userId,
        'order_code' => 'ORD002',
        'used_at' => Carbon::now()->subDays(1),
    ]);
}

}
