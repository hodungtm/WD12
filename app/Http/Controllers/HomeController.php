<?php

namespace App\Http\Controllers;

// note: Import các model cần thiết cho trang chủ
use App\Models\Post;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;

// note: Controller xử lý trang chủ và các trang liên quan đến hiển thị sản phẩm
class HomeController extends Controller
{
    // note: Hiển thị trang chủ với banner, danh mục, sản phẩm nổi bật và tin tức
    public function index()
    {
        // note: Lấy banner slider (loại slider, trạng thái hiển thị, sắp xếp theo thời gian mới nhất)
        $banners = Banner::with('hinhAnhBanner')
            ->where('loai_banner', 'slider')
            ->where('trang_thai', 'hien')
            ->latest()
            ->get();

        // note: Lấy danh mục sản phẩm (chỉ những danh mục đang hoạt động)
        $categories = Category::where('tinh_trang', 1)
            ->orderBy('ten_danh_muc')
            ->get();

        // note: Lấy sản phẩm nổi bật (5 sản phẩm có đánh giá trung bình cao nhất)
        $products = Products::with(['images', 'variants', 'reviews'])
            ->where('status', 1)
            ->withAvg('reviews', 'so_sao')
            ->orderByDesc('reviews_avg_so_sao')
            ->take(5)
            ->get();

        // note: Lấy sản phẩm trending (5 sản phẩm có nhiều lượt mua nhất)
        $trendingProducts = Products::with(['images', 'variants', 'reviews'])
            ->where('status', 1)
            ->orderByDesc('sold')
            ->take(5)
            ->get();

       
        

        // note: Lấy tin tức/blog (6 bài viết mới nhất, trạng thái đã xuất bản)
        $posts = Post::where('status', 'published')->latest()->take(6)->get();

        // note: Lấy banner footer (loại footer, trạng thái hiển thị)
        // $footerBanners = Banner::with('hinhAnhBanner')
        //     ->where('loai_banner', 'footer')
        //     ->where('trang_thai', 'hien')
        //     ->latest()
        //     ->get();

        // note: Discount (nếu muốn dùng cho banner khuyến mãi)
 

        // note: Trả về view trang chủ với tất cả dữ liệu đã chuẩn bị
        return view('Client.index', compact(
            'banners',
            'categories',
            'products',
            'trendingProducts',
            'posts',
            // 'footerBanners'
            
        ));

    }
    
    // note: Hiển thị trang sản phẩm bán chạy (sắp xếp theo số lượng đơn hàng)
    public function bestSellers()
{
    // note: Lấy sản phẩm với số lượng đơn hàng, sắp xếp theo số lượng bán chạy
    $products = Products::withCount('orderItems')
        ->with(['category', 'images', 'variants'])
        ->orderByDesc('order_items_count')
        ->paginate(20);
    // note: Lấy dữ liệu cho bộ lọc (danh mục, màu sắc, kích thước, khoảng giá)
    $categories = \App\Models\Category::all();
    $colors = \App\Models\Color::all();
    $sizes = \App\Models\Size::all();
    $minPrice = \App\Models\ProductVariant::min('price');
    $maxPrice = \App\Models\ProductVariant::max('price');
    $pageTitle = 'Sản phẩm bán chạy';
    return view('Client.Product.ListProductClient', compact('products', 'categories', 'colors', 'sizes', 'minPrice', 'maxPrice', 'pageTitle'));
}

// note: Hiển thị trang sản phẩm nổi bật (sắp xếp theo đánh giá trung bình)
public function featured()
{
    // note: Lấy sản phẩm với đánh giá trung bình, sắp xếp theo điểm đánh giá cao nhất
    $products = Products::withAvg('reviews', 'so_sao')
        ->with(['category', 'images', 'variants'])
        ->orderByDesc('reviews_avg_so_sao')
        ->paginate(20);
    // note: Lấy dữ liệu cho bộ lọc
    $categories = \App\Models\Category::all();
    $colors = \App\Models\Color::all();
    $sizes = \App\Models\Size::all();
    $minPrice = \App\Models\ProductVariant::min('price');
    $maxPrice = \App\Models\ProductVariant::max('price');
    $pageTitle = 'Sản phẩm nổi bật';
    return view('Client.Product.ListProductClient', compact('products', 'categories', 'colors', 'sizes', 'minPrice', 'maxPrice', 'pageTitle'));
}
    
    // note: Xử lý chatbot trả lời tự động dựa trên từ khóa
    public function respond(Request $request)
    {
        // note: Chuyển tin nhắn về chữ thường để dễ so sánh
        $message = strtolower($request->input('message'));
        $response = $this->getResponse($message);
        return response()->json(['reply' => $response]);
    }

    // note: Hàm xử lý logic trả lời tự động dựa trên từ khóa
    private function getResponse($msg)
    {
       // note: Mảng chứa các câu hỏi thường gặp và câu trả lời tương ứng
       $faq = [
            'chào' => '🤖 Chào bạn! Mình là trợ lý của **shop phụ kiện thể thao**. Shop có giày đá bóng, vợt cầu lông, quần áo thể thao, bóng đá - bóng chuyền và nhiều phụ kiện khác. Bạn cần tìm gì để mình hỗ trợ nha?',

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

            'mua nhiều' => '🤖 Nếu bạn mua sỉ hoặc mua nhiều, shop sẽ có giá ưu đãi riêng. Bạn vui lòng nhắn số lượng cụ thể để mình báo giá nhé.',
            'tìm' => '🤖 Bạn cần tìm sản phẩm nào ạ? Bạn có thể gõ tên sản phẩm hoặc từ khóa như "giày Nike", "áo thể thao nam", "vợt cầu lông nhẹ"...',
            'tìm giày đá bóng' => '🤖 Đây là link các mẫu giày đá bóng: /san-pham?loai=giay-da-bong. Bạn cần sân cỏ nhân tạo hay sân futsal ạ?',
            'tìm theo giá' => '🤖 Bạn muốn tìm sản phẩm trong khoảng giá nào ạ? Ví dụ: dưới 500k, từ 500k-1 triệu,... Mình sẽ lọc giúp bạn!',
            'đơn hàng' => '🤖 Bạn đã đặt đơn hàng trên website? Vui lòng cung cấp mã đơn hoặc số điện thoại để mình kiểm tra giúp nhé.',
            'kiểm tra đơn' => '🤖 Bạn có thể kiểm tra tình trạng đơn hàng tại mục "Tra cứu đơn hàng" hoặc nhắn mã đơn cho mình để kiểm tra nhanh hơn.',
            'bao lâu nhận' => '🤖 Thời gian giao hàng từ 2–5 ngày tùy khu vực. Ở Hà Nội và TP.HCM có thể nhận trong 1–2 ngày đó ạ.',
            'phí ship' => '🤖 Phí giao hàng thường từ 20k đến 40k tùy khu vực và số lượng. Đơn từ 500k trở lên có thể được miễn phí ship nha!',
            'có cod không' => '🤖 Có ạ! Shop hỗ trợ thanh toán khi nhận hàng (COD). Bạn nhận hàng, kiểm tra rồi mới thanh toán nhé.',
            'giảm giá' => '🤖 Shop có giảm giá theo tuần và dịp lễ. Bạn muốn xem ưu đãi giày, áo hay combo sản phẩm ạ?',
            'mã giảm giá' => '🤖 Bạn có thể nhập mã "THETHAO10" khi thanh toán để giảm 10k cho đơn từ 300k nhé!',
            'chọn size' => '🤖 Bạn cần chọn size giày hay áo ạ? Bạn cho mình chiều cao, cân nặng hoặc size đang dùng để mình tư vấn chuẩn nha.',
            'có sẵn không' => '🤖 Bạn cần hỏi sản phẩm nào ạ? Nếu hết hàng thì mình có thể báo khi hàng về lại nhé.',
            'có shop không' => '🤖 Shop có cửa hàng tại Hà Nội & TP.HCM. Bạn muốn đến xem trực tiếp hay mua online giao tận nhà ạ?',
'mua sỉ' => '🤖 Shop có chính sách ưu đãi đặc biệt cho khách sỉ. Bạn có thể để lại thông tin số lượng và khu vực để mình gửi báo giá nha.',
            'đại lý' => '🤖 Bạn muốn làm cộng tác viên hoặc đại lý? Shop có chương trình chiết khấu tốt. Nhắn giúp mình khu vực và số điện thoại nhé!',
            'shop bán gì' => '🤖 Shop chuyên bán các sản phẩm thể thao chính hãng như **giày đá bóng, vợt cầu lông, quần áo thể thao, bóng đá – bóng chuyền – bóng rổ**, và các phụ kiện khác. Mẫu mã đa dạng, hàng mới liên tục, giá hợp lý và có nhiều ưu đãi nhé!',

            'sản phẩm' => '🤖 Shop có rất nhiều mặt hàng thể thao bao gồm: 
            - 👟 Giày thể thao (Nike, Adidas, Mizuno…)
            - 🏸 Vợt cầu lông (Yonex, Lining, Victor…)
            - 👕 Quần áo thể thao đủ size nam nữ
            - ⚽ Bóng đá, bóng chuyền, bóng rổ
            - 🎒 Phụ kiện: bao vợt, tất, băng tay, balo, bình nước...

                Bạn cần tìm loại nào để mình hỗ trợ chi tiết hơn ạ?',

            'danh mục' => '🤖 Bạn có thể xem nhanh các danh mục sản phẩm như:
            - Giày đá bóng: /san-pham?loai=giay
            - Vợt cầu lông: /san-pham?loai=vot
            - Quần áo thể thao: /san-pham?loai=quanao
            - Bóng các loại: /san-pham?loai=bong
            Hoặc vào trang sản phẩm: /san-pham để xem toàn bộ nhé.',

        ];

        // note: Duyệt qua mảng FAQ để tìm từ khóa phù hợp
        foreach ($faq as $key => $reply) {
            if (str_contains($msg, $key)) {
                return $reply;
            }
        }

        // note: Trả về câu trả lời mặc định nếu không tìm thấy từ khóa phù hợp
        return 'Xin lỗi, tôi chưa hiểu. Bạn vui lòng hỏi lại hoặc gọi 0969152065 hoặc zalo:0969152065 để được hỗ trợ.';
    }
}
