<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->value('id'), // hoặc tạo nếu chưa có
            'tac_gia' => $this->faker->name,
            'noi_dung' => $this->faker->sentence(10),
            'trang_thai' => $this->faker->boolean(70), // 70% hiển thị
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
