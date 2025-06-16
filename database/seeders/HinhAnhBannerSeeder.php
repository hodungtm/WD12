<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use App\Models\HinhAnhBanner;

class HinhAnhBannerSeeder extends Seeder
{
    public function run(): void
    {
        $baseImagePath = 'uploads/hinhanhbanner/';

        $imageSlides = [
            'banner_slider_1.png',
            'banner_slider_2.png',
            'banner_slider_3.jpg',
            'banner_slider_4.png',
            'banner_slider_5.png',
        ];

        $imageFooters = [
            'banner_footer_1.png',
            'banner_footer_2.png',
            'banner_footer_3.png',
            'banner_footer_4.png',
            'banner_footer_5.png',
        ];

        $banners = Banner::select('id', 'loai_banner')->get();

        foreach ($banners as $banner) {
            if ($banner->loai_banner === 'slider') {
                $imageFiles = $imageSlides;
                $imageFolder = 'id_1';
            } elseif ($banner->loai_banner === 'footer') {
                $imageFiles = $imageFooters;
                $imageFolder = 'id_2';
            } else {
                continue;
            }

            foreach ($imageFiles as $fileName) {
                $imagePath = "{$baseImagePath}{$imageFolder}/{$fileName}";

                HinhAnhBanner::create([
                    'banner_id' => $banner->id,
                    'hinh_anh'  => $imagePath,
                ]);
            }
        }
    }
}
