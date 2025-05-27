<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $i) {
            Wishlist::create([
                'product_id' => Product::inRandomOrder()->first()->id,
                'product_name' => $faker->word,
                'price' => $faker->randomFloat(2, 10, 100),
                'image' => $faker->imageUrl(300, 300),
                'status' => 'active',
            ]);
        }
    }
}
