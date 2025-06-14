<?
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Comment;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function show($id)
    {
        $product = Product::with([
            'reviews' => function ($q) {
                $q->where('trang_thai', true)->latest();
            },
            'comments' => function ($q) {
                $q->where('trang_thai', true)->latest();
            }
        ])->findOrFail($id);

        return view('client.product_detail', compact('product'));
    }

    // public function submitReview(Request $request, $id)
    // {
    //     $request->validate([
    //         'so_sao' => 'required|integer|min:1|max:5',
    //         'noi_dung' => 'required|string|max:1000',
    //     ]);

    //     Review::create([
    //         'product_id' => $id,
    //         'user_id' => auth()->id() ?? null,
    //         'so_sao' => $request->so_sao,
    //         'noi_dung' => $request->noi_dung,
    //         'trang_thai' => false,
    //     ]);

    //     return back()->with('success', 'Gửi đánh giá thành công, đang chờ duyệt.');
    // }

    // public function submitComment(Request $request, $id)
    // {
    //     $request->validate([
    //         'tac_gia' => 'required|string|max:255',
    //         'noi_dung' => 'required|string|max:1000',
    //     ]);

    //     Comment::create([
    //         'product_id' => $id,
    //         'tac_gia' => $request->tac_gia,
    //         'noi_dung' => $request->noi_dung,
    //         'trang_thai' => false,
    //     ]);

    //     return back()->with('success', 'Bình luận đã gửi và đang chờ duyệt.');
    // }
}
