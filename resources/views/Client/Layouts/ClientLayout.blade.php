@php
use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-..."
    crossorigin="anonymous"></script>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Porto - Bootstrap eCommerce Template</title>

    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="Porto - Bootstrap eCommerce Template">
    <meta name="author" content="SW-THEMES">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    @yield('css')

    {{-- chatbot --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- endchatbot --}}
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/icons/favicon.png') }}">


    <script>
        WebFontConfig = {
            google: {
                families: ['Open+Sans:300,400,600,700,800', 'Poppins:200,300,400,500,600,700,800', 'Oswald:300,600,700',
                    'Playfair+Display:700'
                ]
            }
        };
        (function(d) {
            var wf = d.createElement('script'),
                s = d.scripts[0];
            wf.src = '{{ asset('assets/js/webfont.js') }}';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Main CSS File -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo27.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/vendor/simple-line-icons/css/simple-line-icons.min.css') }}">
    <style>
        /* Menu ngang chỉ 1 dòng, tràn thì cuộn ngang, chuẩn e-commerce */
        .header .main-nav .menu {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            white-space: nowrap;
            scrollbar-width: thin;
            scrollbar-color: #ccc #f8f9fa;
        }

        .header .main-nav .menu::-webkit-scrollbar {
            height: 6px;
            background: #f8f9fa;
        }

        .header .main-nav .menu::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .header .main-nav .menu li {
            flex-shrink: 0;
            white-space: nowrap;
        }

        /* Làm đẹp các mục trong dropdown */
        .dropdown-menu .dropdown-item,
        .dropdown-menu .dropdown-item-text {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            background-color: transparent;
            border: none;
            white-space: nowrap;
            transition: background-color 0.2s, color 0.2s;
        }

        /* Hover đẹp hơn */
        .dropdown-menu .dropdown-item:hover {
            background-color: #f0f0f0;
            color: #000;
        }

        /* Tách biệt mục "Trang Quản Trị" */
        .dropdown-menu .admin-link {
            font-weight: 600;
            color: #0056b3;
        }

        /* Tên người dùng */
        .dropdown-menu .dropdown-item-text {
            font-weight: 600;
            color: #666;
            font-size: 13px;
            padding: 8px 16px;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 4px;
        }

        /* Tùy chọn thêm: đổ bóng nhẹ dropdown */
        .dropdown-menu {
            border-radius: 6px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
            min-width: 180px;
        }
        .chatbot-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1050;
        }

        .chatbot-container {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 360px;
            height: 500px;
            display: none;
            /* Ẩn ban đầu */
            z-index: 1040;
        }
        #alert-stack {
            position: fixed;
            top: 32px;
            right: 32px;
            left: auto;
            transform: none;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
            width: auto;
            min-width: 320px;
            max-width: 90vw;
            pointer-events: none;
        }

        .custom-alert {
            position: relative;
            background: #20b2aa;
            color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.13);
            font-size: 1.08rem;
            padding: 18px 28px 18px 18px;
            border: 1.5px solid #179b8a;
            font-weight: 500;
            line-height: 1.6;
            min-width: 320px;
            max-width: 420px;
            margin: 0 0 0 auto;
            animation: slideInDown 0.7s cubic-bezier(.68, -0.55, .27, 1.55);
            will-change: opacity, transform;
            backface-visibility: hidden;
            display: flex;
            align-items: center;
            pointer-events: auto;
        }
        .custom-alert .icon-warning {
            margin-right: 16px;
            font-size: 1.7em;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            min-height: 32px;
            color: #fff;
            opacity: 0.85;
            margin-top: 6px;
        }

        .custom-alert .close {
            position: absolute;
            top: 10px;
            right: 18px;
            color: #fff;
            font-size: 1.2em;
            opacity: 0.7;
            background: none;
            border: none;
        }

        @keyframes slideInDown {
            0% {
                opacity: 0;
                transform: translateY(-80px) scale(0.85);
            }

            60% {
                opacity: 1;
                transform: translateY(10px) scale(1.05);
            }

            80% {
                opacity: 1;
                transform: translateY(-2px) scale(0.98);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        #mini-cart-overlay {
            display: none;
            position: fixed; top: 0; right: 0; bottom: 0; left: 0;
            z-index: 2000;
            background: rgba(0,0,0,0.3);
        }
        #mini-cart-content {
            position: absolute; top: 0; right: 0;
            width: 340px; max-width: 100vw; height: 100%;
            background: #fff;
            box-shadow: -2px 0 16px rgba(0,0,0,0.12);
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(.77,0,.18,1);
        }
        #mini-cart-overlay.open #mini-cart-content {
            transform: translateX(0);
        }
        #mini-cart-close {
            position: absolute; top: 16px; right: 16px;
            background: #222; color: #fff; border: none;
            border-radius: 50%; width: 36px; height: 36px;
            font-size: 22px; cursor: pointer; z-index: 10;
            display: flex; align-items: center; justify-content: center;
        }
    </style>
</head>

<body class="@yield('body-class')">
    @include('components.alert')
    <div class="page-wrapper">
        <header class="header">
            <div class="header-top">
                <div class="container">
                    <div class="header-left">
                        <div class="header-dropdown">
                            <a href="#">VNĐ</a>
                        </div>

                        <div class="header-dropdown">
                            <a href="#"><i class="flag flag-vietnam"></i>VIE</a>
    
                        </div>
                    </div>

                    <div class="header-right d-none d-lg-flex">

                        <div class="header-dropdown dropdown-expanded">
                            <a href="#">Links</a>
                            <div class="header-menu">
                                <ul>
                                    
                                    <li><a href="{{ route('client.cart.index') }}">Giỏ hàng</a></li>
                                    <li><a href="{{ route('client.wishlist.index') }}">Yêu thích</a></li>
                                    <li><a href="{{ route('client.listblog') }}">Tin tức</a></li>

                                    <li>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-light dropdown-toggle"
                                                style="background: none; border: none; color: inherit; cursor: pointer; padding: 0; font: inherit;"
                                                data-toggle="dropdown" aria-expanded="false">
                                                My Account
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @guest
                                                <a href="{{ route('login') }}" class="dropdown-item">Đăng Nhập</a>
                                                <a href="{{ route('register') }}" class="dropdown-item">Đăng Ký</a>
                                                @else
                                                <span class="dropdown-item-text font-weight-bold">{{ Auth::user()->name
                                                    }}</span>
                                                <a class="dropdown-item" href="{{ route('user.dashboard') }}">Tài khoản của tôi</a>
                                                <a class="dropdown-item" href="{{ route('client.orders.index') }}">Đơn hàng</a>
                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    Đăng Xuất
                                                </a>
                                                
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    class="d-none">
                                                    @csrf
                                                </form>
                                                @endguest
                                                @if (Auth::check() && Auth::user()->isRoleAdmin())
                                                <a href="{{ route('products.index') }}" class="dropdown-item">Trang
                                                    Quản Trị</a>
                                                @endif
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-middle sticky-header">
                <div class="container">
                    <div class="header-left">
                        <button class="mobile-menu-toggler" type="button">
                            <i class="fas fa-bars"></i>
                        </button>
                        <a href="{{ route('client.index') }}" class="logo">
                            <img src="{{ asset('assets/images/logo1.png') }}" alt="Porto Logo" width="111"
                                height="44">
                        </a>
                        <nav class="main-nav">
                            <ul class="menu">
                                <li class="active">
                                    <a href="{{ route('client.index') }}">Trang chủ</a>
                                </li>
                                <li>
                                    <a href="{{ route('client.listproduct') }}">Sản phẩm</a>

                                </li>
                            
                                <li class="d-none d-xxl-block"><a href="{{ route('client.listblog') }}">Tin tức</a></li>
                                <li>
                                    <a href="{{ route('client.contact.show') }}">Liên hệ</a>
                                </li>

                            </ul>
                        </nav>
                    </div>

                    <div class="header-right">
                        <div
                            class="header-icon header-search header-search-inline header-search-category w-lg-max text-right d-none d-sm-block">
                            <a href="#" class="search-toggle" role="button"><i class="icon-magnifier"></i></a>
                            <form action="{{ route('client.search') }}" method="get">
                                <div class="header-search-wrapper">
                                    <input type="search" class="form-control" name="keyword" id="q"
                                        placeholder="Tôi muốn tìm..." value="{{ request('keyword') }}" required>
                                    <div class="select-custom font2">
                                        <select id="cat" name="category">
                                            <option value="">All Categories</option>
                                            @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ request('category')==$cat->id ? 'selected'
                                                : '' }}>
                                                {{ $cat->ten_danh_muc }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div><!-- End .select-custom -->
                                    <button class="btn icon-magnifier" title="search" type="submit"></button>
                                </div><!-- End .header-search-wrapper -->
                            </form>
                        </div><!-- End .header-search -->

                        <a href="{{ route('client.wishlist.index') }}" class="header-icon position-relative">
                            <i class="icon-wishlist-2 line-height-1"></i>
                            <span class="wishlist-count">{{ $wishlistCount }}</span>
                        </a>

                        <div class="dropdown cart-dropdown">
                            <a href="#" title="Cart" class="dropdown-toggle cart-toggle" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                <i class="minicart-icon"></i>
                                <span class="cart-count badge-circle">0</span>
                            </a>
                        </div><!-- End .dropdown -->
                    </div>
                </div>
            </div>
            <div class="header-bottom">
                <div class="owl-carousel info-boxes-slider" data-owl-options="{
                        'items': 1,
                        'dots': false,
                        'loop': false,
                        'responsive': {
                            '768': {
                                'items': 2
                            },
                            '992': {
                                'items': 3
                            }
                        }
                    }">
                    <div class="info-box info-box-icon-left">
                        <i class="icon-shipping text-white"></i>

                        <div class="info-box-content">
                            <h4 class="text-white">Miễn phí vận chuyển &amp; đổi trả</h4>
                        </div><!-- End .info-box-content -->
                    </div><!-- End .info-box -->

                    <div class="info-box info-box-icon-left">
                        <i class="icon-money text-white"></i>

                        <div class="info-box-content">
                            <h4 class="text-white">Hoàn tiền nếu không hài lòng</h4>
                        </div><!-- End .info-box-content -->
                    </div><!-- End .info-box -->

                    <div class="info-box info-box-icon-left">
                        <i class="icon-support text-white"></i>

                        <div class="info-box-content">
                            <h4 class="text-white">Hỗ trợ trực tuyến 24/7</h4>
                        </div><!-- End .info-box-content -->
                    </div><!-- End .info-box -->
                </div><!-- End .owl-carousel -->
            </div>
        </header>

        <main class="app-content">

            @yield('main')

        </main>

        <footer class="footer font2">
            <div class="footer-top">
                <div class="instagram-box bg-dark">
                    <div class="row m-0 align-items-center">
                        <div class="instagram-follow col-md-4 col-lg-3 d-flex align-items-center">
                            <div class="info-box">
                                <i class="fab fa-instagram text-white mr-4"></i>
                                <div class="info-box-content">
                                    <h4 class="text-white line-height-1">Theo dõi chúng tôi trên Instagram</h4>
                                    <p class="line-height-1">@sudesecommerce</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 col-lg-9 p-0">
                            <div class="instagram-carousel owl-carousel owl-theme" data-owl-options="{
                            'items': 2,
                            'dots': false,
                            'responsive': {
                                '480': {
                                    'items': 3
                                },
                                '950': {
                                    'items': 4
                                },
                                '1200': {
                                    'items' : 5
                                },
                                '1500': {
                                    'items': 6
                                }
                            }
                        }">
                                <img src="{{ asset('assets/images/demoes/demo27/instagram/instagram1.jpg') }}"
                                    alt="instagram" width="240" height="240">
                                <img src="{{ asset('assets/images/demoes/demo27/instagram/instagram2.jpg') }}"
                                    alt="instagram" width="240" height="240">
                                <img src="{{ asset('assets/images/demoes/demo27/instagram/instagram3.jpg') }}"
                                    alt="instagram" width="240" height="240">
                                <img src="{{ asset('assets/images/demoes/demo27/instagram/instagram4.jpg') }}"
                                    alt="instagram" width="240" height="240">
                                <img src="{{ asset('assets/images/demoes/demo27/instagram/instagram5.jpg') }}"
                                    alt="instagram" width="240" height="240">
                                <img src="{{ asset('assets/images/demoes/demo27/instagram/instagram6.jpg') }}"
                                    alt="instagram" width="240" height="240">
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="container">
                <div class="footer-middle">
                    <div class="row">
                        <div class="col-lg-3">
                            <a href="{{ route('client.index') }}"><img src="{{ asset('assets/images/logo2.png') }}"
                                    alt="Logo" class="logo"></a>

                            <p class="footer-desc">Cửa hàng chuyên cung cấp sản phẩm chất lượng cao.</p>

                            <div class="ls-0 footer-question mb-3">
                                <h6 class="mb-0 text-white">CẦN HỖ TRỢ?</h6>
                                <h3 class="mb-0 text-primary">1-888-123-456</h3>
                            </div>
                        </div><!-- End .col-lg-3 -->

                        <div class="col-lg-3">
                            <div class="widget">
                                <h4 class="widget-title">Tài khoản</h4>

                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="links">
                                            <li><a href="dashboard.html">Tài khoản của tôi</a></li>
                                            <li><a href="#">Theo dõi đơn hàng</a></li>
                                            <li><a href="#">Phương thức thanh toán</a></li>
                                            <li><a href="#">Hướng dẫn vận chuyển</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="links">
                                            <li><a href="#">Câu hỏi thường gặp</a></li>
                                            <li><a href="#">Hỗ trợ sản phẩm</a></li>
                                            <li><a href="#">Bảo mật</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- End .widget -->
                        </div><!-- End .col-lg-3 -->

                        <div class="col-lg-3">
                            <div class="widget">
                                <h4 class="widget-title">Về chúng tôi</h4>

                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="links">
                                            <li><a href="about.html">Về Porto</a></li>
                                            <li><a href="#">Cam kết của chúng tôi</a></li>
                                            <li><a href="#">Điều khoản &amp; điều kiện</a></li>
                                            <li><a href="#">Chính sách bảo mật</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="links">
                                            <li><a href="#">Chính sách đổi trả</a></li>
                                            <li><a href="#">Khiếu nại bản quyền</a></li>
                                            <li><a href="#">Sơ đồ trang</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- End .widget -->
                        </div><!-- End .col-lg-3 -->

                        <div class="col-lg-3">
                            <div class="widget text-lg-right">
                                <h4 class="widget-title">Tính năng</h4>

                                <ul class="links">
                                    <li><a href="#">Quản trị mạnh mẽ</a></li>
                                    <li><a href="#">Tối ưu cho di động &amp; retina</a></li>
                                    <li><a href="#">Giao diện HTML siêu nhanh</a></li>
                                </ul>
                            </div><!-- End .widget -->
                        </div><!-- End .col-lg-3 -->
                    </div><!-- End .row -->
                </div>
                <div class="footer-bottom">
                    <p class="footer-copyright text-lg-center mb-0">&copy; Porto eCommerce. 2021. Đã đăng ký bản quyền.
                    </p>
                </div><!-- End .footer-bottom -->
            </div><!-- End .container -->
        </footer><!-- End .footer -->
    </div><!-- End .page-wrapper -->

    <div class="loading-overlay">
        <div class="bounce-loader">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>

    <div class="mobile-menu-overlay"></div><!-- End .mobil-menu-overlay -->

    <div class="mobile-menu-container">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="fa fa-times"></i></span>
            <nav class="mobile-nav">
                <ul class="mobile-menu">
                    <li><a href="{{ route('client.index') }}">Trang chủ</a></li>
                    <li>
                        <a href="{{ route('client.listproduct') }}">Danh mục</a>
                        <ul>
                            <li><a href="category.html">Full Width Banner</a></li>
                            <li><a href="category-banner-boxed-slider.html">Boxed Slider Banner</a></li>
                            <li><a href="category-banner-boxed-image.html">Boxed Image Banner</a></li>
                            <li><a href="https://www.portotheme.com/html/porto_ecommerce/category-sidebar-left.html">Left
                                    Sidebar</a></li>
                            <li><a href="category-sidebar-right.html">Right Sidebar</a></li>
                            <li><a href="category-off-canvas.html">Off Canvas Filter</a></li>
                            <li><a href="category-horizontal-filter1.html">Horizontal Filter 1</a></li>
                            <li><a href="category-horizontal-filter2.html">Horizontal Filter 2</a></li>
                            <li><a href="#">List Types</a></li>
                            <li><a href="category-infinite-scroll.html">Ajax Infinite Scroll<span
                                        class="tip tip-new">New</span></a></li>
                            <li><a href="category.html">3 Columns Products</a></li>
                            <li><a href="category-4col.html">4 Columns Products</a></li>
                            <li><a href="category-5col.html">5 Columns Products</a></li>
                            <li><a href="category-6col.html">6 Columns Products</a></li>
                            <li><a href="category-7col.html">7 Columns Products</a></li>
                            <li><a href="category-8col.html">8 Columns Products</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="demo27-product.html">Sản phẩm</a>
                        <ul>
                            <li>
                                <a href="#" class="nolink">PRODUCT PAGES</a>
                                <ul>
                                    <li><a href="demo27-product.html">SIMPLE PRODUCT</a></li>
                                    <li><a href="product-variable.html">VARIABLE PRODUCT</a></li>
                                    <li><a href="demo27-product.html">SALE PRODUCT</a></li>
                                    <li><a href="demo27-product.html">FEATURED & ON SALE</a></li>
                                    <li><a href="product-sticky-info.html">WIDTH CUSTOM TAB</a></li>
                                    <li><a href="product-sidebar-left.html">WITH LEFT SIDEBAR</a></li>
                                    <li><a href="product-sidebar-right.html">WITH RIGHT SIDEBAR</a></li>
                                    <li><a href="product-addcart-sticky.html">ADD CART STICKY</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="nolink">PRODUCT LAYOUTS</a>
                                <ul>
                                    <li><a href="product-extended-layout.html">EXTENDED LAYOUT</a></li>
                                    <li><a href="product-grid-layout.html">GRID IMAGE</a></li>
                                    <li><a href="product-full-width.html">FULL WIDTH LAYOUT</a></li>
                                    <li><a href="product-sticky-info.html">STICKY INFO</a></li>
                                    <li><a href="product-sticky-both.html">LEFT & RIGHT STICKY</a></li>
                                    <li><a href="product-transparent-image.html">TRANSPARENT IMAGE</a></li>
                                    <li><a href="product-center-vertical.html">CENTER VERTICAL</a></li>
                                    <li><a href="#">BUILD YOUR OWN</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Trang<span class="tip tip-hot">Hot!</span></a>
                        <ul>
                            <li>
                                <a href="{{ route('client.wishlist.index') }}">Yêu thích</a>
                            </li>
                            <li>
                                <a href="cart.html">Giỏ hàng</a>
                            </li>
                            <li>
                                <a href="checkout.html">Thanh toán</a>
                            </li>
                            <li>
                                <a href="dashboard.html">Bảng điều khiển</a>
                            </li>
                            <li>
                                <a href="login.html">Đăng nhập</a>
                            </li>
                            <li>
                                <a href="forgot-password.html">Quên mật khẩu</a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="{{ route('client.listblog') }}">Tin tức</a></li>
                    <li>
                        <a href="#">Thành phần</a>
                        <ul class="custom-scrollbar">
                            <li><a href="element-accordions.html">Accordion</a></li>
                            <li><a href="element-alerts.html">Thông báo</a></li>
                            <li><a href="element-animations.html">Hiệu ứng</a></li>
                            <li><a href="element-banners.html">Banner</a></li>
                            <li><a href="element-buttons.html">Nút bấm</a></li>
                            <li><a href="element-call-to-action.html">Kêu gọi hành động</a></li>
                            <li><a href="element-countdown.html">Đếm ngược</a></li>
                            <li><a href="element-counters.html">Bộ đếm</a></li>
                            <li><a href="element-headings.html">Tiêu đề</a></li>
                            <li><a href="element-icons.html">Biểu tượng</a></li>
                            <li><a href="element-info-box.html">Hộp thông tin</a></li>
                            <li><a href="element-posts.html">Bài viết</a></li>
                            <li><a href="element-products.html">Sản phẩm</a></li>
                            <li><a href="element-product-categories.html">Danh mục sản phẩm</a></li>
                            <li><a href="element-tabs.html">Tab</a></li>
                            <li><a href="element-testimonial.html">Khách hàng nói</a></li>
                        </ul>
                    </li>
                </ul>

                <ul class="mobile-menu mt-2 mb-2">
                    <li class="border-0">
                        <a href="#">
                            Ưu đãi đặc biệt!
                        </a>
                    </li>
                    <li class="border-0">
                        <a href="https://1.envato.market/DdLk5" target="_blank">
                            Mua Porto!
                            <span class="tip tip-hot">Hot</span>
                        </a>
                    </li>
                </ul>

                <ul class="mobile-menu">
                    <li><a href="login.html">Tài khoản của tôi</a></li>
                    <li><a href="{{ route('client.contact.show') }}">Liên hệ</a></li>
                    <li><a href="{{ route('client.listblog') }}">Tin tức</a></li>
                    <li><a href="{{ route('client.wishlist.index') }}">Yêu thích</a></li>
                    <li><a href="cart.html">Giỏ hàng</a></li>
                    <li><a href="login.html" class="login-link">Đăng nhập</a></li>
                </ul>
            </nav><!-- End .mobile-nav -->

            <form class="search-wrapper mb-2" action="#">
                <input type="text" class="form-control mb-0" placeholder="Tìm kiếm..." required />
                <button class="btn icon-search text-white bg-transparent p-0" type="submit"></button>
            </form>

            <div class="social-icons">
                <a href="#" class="social-icon social-facebook icon-facebook" target="_blank">
                </a>
                <a href="#" class="social-icon social-twitter icon-twitter" target="_blank">
                </a>
                <a href="#" class="social-icon social-instagram icon-instagram" target="_blank">
                </a>
            </div>
        </div><!-- End .mobile-menu-wrapper -->
    </div><!-- End .mobile-menu-container -->

    <div class="sticky-navbar">
        <div class="sticky-info">
            <a href="{{ route('client.index') }}">
                <i class="icon-home"></i>Trang chủ
            </a>
        </div>
        <div class="sticky-info">
            <a href="{{ route('client.listproduct') }}" class="">
                <i class="icon-bars"></i>Danh mục
            </a>
        </div>
        <div class="sticky-info">
            <a href="{{ route('client.wishlist.index') }}" class="">
                <i class="icon-wishlist-2"></i>Yêu thích
            </a>
        </div>
        <div class="sticky-info">
            <a href="login.html" class="">
                <i class="icon-user-2"></i>Tài khoản
            </a>
        </div>
        <div class="sticky-info">
            <a href="cart.html" class="">
                <i class="icon-shopping-cart position-relative">
                    <span class="cart-count badge-circle">3</span>
                </i>Giỏ hàng
            </a>
        </div>
    </div>

    <a id="scroll-top" href="#top" title="Lên đầu trang" role="button"><i class="icon-angle-up"></i></a>

    <!-- Plugins JS File -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.min.js') }}"></script>
    <script src="{{ asset('assets/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('assets/js/optional/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.plugin.min.js') }}"></script>

@yield('js')
    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.min.js') }}"></script>
    <script>
        function updateCartCount() {
            fetch("{{ route('cart.mini') }}")
              .then(res => res.json())
              .then(data => {
                document.querySelectorAll('.cart-count').forEach(el => {
                  el.textContent = data.count || 0;
                });
              });
        }
        $(document).ready(function() {
            setTimeout(function() {
                $('.custom-alert').alert('close');
            }, 3500);
            updateCartCount();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#alert-stack .custom-alert').each(function(i, el) {
                setTimeout(function() {
                    $(el).fadeOut(400, function() {
                        $(this).remove();
                    });
                }, 3000 + i * 300); // Cái sau trễ hơn cái trước 0.3s
            });
        });
    </script>
    <!-- Mini Cart Overlay -->
    <div id="mini-cart-overlay">
        <div id="mini-cart-content">
            <div id="mini-cart-body" style="padding:28px 20px 20px 20px;"></div>
        </div>
    </div>
    <script>
function showAlert(message, type = 'success') {
    const icon = type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-triangle"></i>';
    const alertDiv = document.createElement('div');
    alertDiv.className = 'custom-alert';
    alertDiv.innerHTML = `<span class="icon-warning">${icon}</span> ${message} <button type="button" class="close" onclick="this.parentElement.remove()"><span aria-hidden="true">&times;</span></button>`;
    document.getElementById('alert-stack').appendChild(alertDiv);
    setTimeout(() => { alertDiv.remove(); }, 3500);
}

// Thêm hàm renderMiniCart
function renderMiniCart(data) {
    const overlay = document.getElementById('mini-cart-overlay');
    const content = document.getElementById('mini-cart-body');
    let html = `<h3 style='font-size:1.4rem;font-weight:700;margin-bottom:18px;'>Giỏ hàng</h3>`;
    if(data.items.length === 0) {
        html += `<div style='padding:24px 0;text-align:center;'>Chưa có sản phẩm nào.</div>`;
    } else {
        data.items.forEach(item => {
          html += `<div style='display:flex;align-items:center;margin-bottom:18px;gap:12px;position:relative;'>
            <img src='${item.image}' width='56' height='56' style='object-fit:cover;border:1px solid #eee;border-radius:6px;'>
            <div style='flex:1;'>
              <a href='${item.link}' style='font-weight:600;color:#222;font-size:1.1rem;'>${item.name}</a><br>
              <span style='font-size:0.97rem;color:#666;'>${item.variant.color ? 'Màu: '+item.variant.color : ''} ${item.variant.size ? 'Size: '+item.variant.size : ''}</span><br>
              <span style='font-size:1rem;'>${item.qty} × <b>${item.price.toLocaleString('vi-VN')}</b></span>
            </div>
            <form method='POST' action='/client/cart/remove/${item.id}' style='display:inline;'>@csrf <input type='hidden' name='_method' value='DELETE'><button type='submit' style='background:none;border:none;color:#c00;font-size:1.3rem;position:absolute;top:0;right:0;cursor:pointer;' title='Xóa'>&times;</button></form>
          </div>`;
        });
        html += `<div style='border-top:1px solid #eee;margin:18px 0 10px;'></div>`;
        html += `<div style='font-size:1.1rem;font-weight:600;margin-bottom:18px;'>Tạm tính: <span style='float:right;'>${data.subtotal.toLocaleString('vi-VN')}</span></div>`;
        html += `<a href='{{ route('client.cart.index') }}' class='btn btn-gray btn-block view-cart' style='width:100%;margin-bottom:10px;'>XEM GIỎ HÀNG</a>`;
        html += `<a href='{{ route('client.checkout.show') }}' class='btn btn-dark btn-block' style='width:100%;'>THANH TOÁN</a>`;
    }
    content.innerHTML = html;
    overlay.style.display = 'block';
    setTimeout(() => overlay.classList.add('open'), 10);
}

document.addEventListener('DOMContentLoaded', function() {
    var cartIcon = document.querySelector('.dropdown.cart-dropdown > a');
    if(cartIcon) cartIcon.id = 'header-cart-icon';
    const overlay = document.getElementById('mini-cart-overlay');
    const content = document.getElementById('mini-cart-body');
    if(cartIcon) {
        cartIcon.addEventListener('click', function(e) {
            e.preventDefault();
            fetch("{{ route('cart.mini') }}")
              .then(res => res.json())
              .then(data => {
                renderMiniCart(data);
              });
        });
    }
    // Đóng mini cart khi click ra ngoài vùng content
    overlay.addEventListener('mousedown', function(e) {
        if (e.target === overlay) {
            overlay.classList.remove('open');
            setTimeout(() => { overlay.style.display = 'none'; }, 350);
        }
    });
});
</script>
</body>


<!-- Mirrored from portotheme.com/html/porto_ecommerce/demo27.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 05 Jul 2025 12:58:29 GMT -->

</html>