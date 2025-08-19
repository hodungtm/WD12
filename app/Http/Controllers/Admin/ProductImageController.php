<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    // Xóa ảnh theo ID
    public function destroyImage($id)
    {
        $productImage = ProductImage::findOrFail($id);

        // Xóa file từ storage
        if (Storage::exists('public/' . $productImage->image)) {
            Storage::delete('public/' . $productImage->image);
        }

        // Xóa bản ghi khỏi DB
        $productImage->delete();

        return redirect()->back()->with('success', 'Ảnh đã được xóa thành công!');
    }
}
