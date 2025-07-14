<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Products;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Banner slider
        $banners = Banner::with('hinhAnhBanner')
            ->where('loai_banner', 'slider')
            ->where('trang_thai', 'hien')
            ->latest()
            ->get();

        // Danh mục
        $categories = Category::where('tinh_trang', 1)
            ->orderBy('ten_danh_muc')
            ->get();

        // Sản phẩm nổi bật (lấy 10 sản phẩm có đánh giá trung bình cao nhất)
        $products = Products::with(['images', 'variants', 'reviews'])
            ->where('status', 1)
            ->withAvg('reviews', 'so_sao')
            ->orderByDesc('reviews_avg_so_sao')
            ->take(10)
            ->get();

        // Sản phẩm trending (lấy 10 sản phẩm có nhiều lượt mua nhất)
        $trendingProducts = Products::with(['images', 'variants', 'reviews'])
            ->where('status', 1)
            ->orderByDesc('sold')
            ->take(10)
            ->get();
        // Blog/tin tức
        $posts = Post::where('status', 'published')->latest()->take(6)->get();

        // Banner nhỏ (footer, khuyến mãi...)
        $footerBanners = Banner::with('hinhAnhBanner')
            ->where('loai_banner', 'footer')
            ->where('trang_thai', 'hien')
            ->latest()
            ->get();

        // Discount (nếu muốn dùng cho banner khuyến mãi)


        return view('Client.index', compact(
            'banners',
            'categories',
            'products',
            'trendingProducts',

            'posts',
            'footerBanners'

        ));
    }
    public function respond(Request $request)
    {
        $message = strtolower($request->input('message'));
        $response = $this->getResponse($message);
        return response()->json(['reply' => $response]);
    }

    private function getResponse($msg)
    {
        $faq = [
    'chào' => '🤖 Chào bạn! Mình là trợ lý của shop thể thao. Bạn cần tìm giày, vợt, quần áo hay thông tin gì ạ?',
    
    'giá' => '🤖 Mỗi sản phẩm có mức giá khác nhau tùy loại và thương hiệu. Bạn có thể nói rõ tên sản phẩm hoặc loại bạn cần để mình báo giá chi tiết hơn nha.',

    'giày' => '🤖 Shop hiện có các loại giày đá bóng phù hợp cho sân cỏ nhân tạo, futsal và sân đất. Có các thương hiệu như Adidas, Nike, Mizuno, Banta... Bạn cần loại giày sân nào ạ?',

    'giầy' => '🤖 Shop có nhiều loại giầy: sân cỏ nhân tạo, sân trong nhà, sân đất. Hàng có size từ 37 đến 45, mẫu mã đa dạng phù hợp cả trẻ em và người lớn.',

    'vợt' => '🤖 Shop có vợt cầu lông cho người mới chơi, bán chuyên và chuyên nghiệp. Các thương hiệu như Yonex, Lining, Victor. Bạn muốn chọn loại nhẹ, cân bằng hay nặng đầu?',

    'bóng' => '🤖 Có bóng đá, bóng chuyền, bóng rổ các loại dùng cho thi đấu và tập luyện. Đa số làm từ da PU, chuẩn size và độ nảy tiêu chuẩn.',

    'quần áo' => '🤖 Quần áo thể thao có đủ size từ S đến XXL. Shop có đồ bóng đá, áo chạy bộ, quần cầu lông, đồ gym,... phù hợp cho cả nam và nữ.',

    'mở cửa' => '🤖 Shop hoạt động từ 8:00 đến 22:00 hàng ngày. Đặt hàng online 24/7 nhé!',

    'ship' => '🤖 Shop giao hàng toàn quốc. Thời gian nhận hàng từ 2–5 ngày tùy khu vực. Có hỗ trợ kiểm tra hàng trước khi thanh toán.',

    'thanh toán' => '🤖 Bạn có thể thanh toán khi nhận hàng (COD) hoặc chuyển khoản trước. Shop hỗ trợ cả ví MoMo và ZaloPay.',

    'đổi trả' => '🤖 Đổi trả trong 7 ngày nếu sản phẩm lỗi hoặc không đúng mô tả. Sản phẩm cần còn nguyên tem, chưa qua sử dụng nha.',

    'khuyến mãi' => '🤖 Hiện đang có chương trình khuyến mãi theo tuần và flash sale. Bạn muốn xem ưu đãi giày, vợt hay trang phục ạ?',

    'địa chỉ' => '🤖 Shop có nhiều chi nhánh tại Hà Nội và TP.HCM. Ngoài ra, bạn có thể đặt hàng ngay trên web và giao tận nhà nhé.',

    'hết hàng' => '🤖 Nếu sản phẩm bạn cần đang hết, bạn có thể để lại tên và số điện thoại, shop sẽ báo ngay khi hàng về.',

    'size' => '🤖 Sản phẩm có đủ size từ trẻ em đến người lớn. Nếu bạn cần tư vấn chọn size giày hoặc áo, hãy cho mình biết chiều cao và cân nặng nha.',

    'tư vấn' => '🤖 Bạn cần tư vấn sản phẩm nào cụ thể ạ? Giày, vợt, bóng, hay quần áo để mình hỗ trợ tốt hơn nhé.',

    'hotline' => '🤖 Nếu cần hỗ trợ gấp, bạn có thể gọi hoặc nhắn Zalo qua số 0909.xxx.xxx. Shop luôn sẵn sàng giúp bạn!',

    'zalo' => '🤖 Bạn có thể chat qua Zalo nếu cần hỗ trợ nhanh, hoặc gửi ảnh sản phẩm bạn muốn hỏi thêm cũng được nha 😊',

    'mua nhiều' => '🤖 Nếu bạn mua sỉ hoặc mua nhiều, shop sẽ có giá ưu đãi riêng. Bạn vui lòng nhắn số lượng cụ thể để mình báo giá nhé.'
        ];

        foreach ($faq as $key => $reply) {
            if (str_contains($msg, $key)) {
                return $reply;
            }
        }

        return 'Xin lỗi, tôi chưa hiểu. Bạn vui lòng hỏi lại hoặc gọi 0969152065 hoặc zalo:0969152065 để được hỗ trợ.';
    }
}
