<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<!--<![endif]-->


<!-- Mirrored from themesflat.co/html/ecomus/admin-ecomus/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 05 Jul 2025 17:40:43 GMT -->

<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <title>Admin Sudes Sports</title>

    <meta name="author" content="themesflat.com">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Theme Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animation.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom-improvements.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">




    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('font/fonts.css') }}">

    <!-- Icon -->
    <link rel="stylesheet" href="{{ asset('icon/style.css') }}">

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo2.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/images/logo2.png') }}">

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
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
            padding: 18px 28px 18px 56px;
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
            position: absolute;
            left: 22px;
            top: 22px;
            font-size: 1.5em;
            color: #fff;
            opacity: 0.85;
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
/* CSS focus sidebar */
.sidebar .nav-link {
    color: #f1f1f1 !important;
    transition: all 0.3s ease !important;
    position: relative !important;
}

/* Style cho menu cha */
.sidebar .has-children {
    margin-bottom: 5px !important;
}

.sidebar .has-children.open > a {
    background-color: rgba(32, 178, 170, 0.1) !important;
    color: #20B2AA !important;
    border-radius: 6px !important;
}

.sidebar .has-children.open .sub-menu {
    display: block !important;
    animation: slideDown 0.3s ease-in-out !important;
}

/* Style cho submenu */
.sidebar .sub-menu {
    margin-left: 20px !important;
    display: none !important;
}

.sidebar .sub-menu .nav-link {
    padding: 8px 15px !important;
    border-radius: 6px !important;
}

.sidebar .sub-menu .nav-link.active {
    background-color: #20B2AA !important;
    color: #ffffff !important;
    box-shadow: 0 2px 8px rgba(32, 178, 170, 0.2) !important;
}

.sidebar .sub-menu .nav-link:hover {
    background-color: rgba(32, 178, 170, 0.1) !important;
    color: #20B2AA !important;
    transform: translateX(4px) !important;
}

/* Hiệu ứng cho active và hover */
.sidebar .nav-link.active {
    background-color: #20B2AA !important;
    color: #ffffff !important;
    border-radius: 6px !important;
    box-shadow: 0 2px 8px rgba(32, 178, 170, 0.2) !important;
}

.sidebar .nav-link.active .text {
    color: #ffffff !important;
    font-weight: 500 !important;
}

.sidebar .nav-link.active i {
    color: #ffffff !important;
}

.sidebar .nav-link:hover {
    background-color: rgba(32, 178, 170, 0.1) !important;
    color: #20B2AA !important;
    border-radius: 6px !important;
    transform: translateX(4px) !important;
}

.sidebar .nav-link:hover i {
    color: #20B2AA !important;
}

.sidebar .nav-link:hover .text {
    color: #20B2AA !important;
}

/* Animation cho submenu */
@keyframes slideDown {
    from {
        opacity: 0 !important;
        transform: translateY(-10px) !important;
    }
    to {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
}

/* Style cho icon mũi tên */
.menu-item-button::after {
    content: '\f107' !important; /* Unicode của icon mũi tên xuống */
    font-family: 'BoxIcons' !important;
    position: absolute !important;
    right: 15px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    transition: transform 0.3s ease !important;
}

.has-children.open > .menu-item-button::after {
    transform: translateY(-50%) rotate(180deg) !important; /* Xoay mũi tên khi menu mở */
}


    </style>
</head>

<body>

    <!-- #wrapper -->
    <div id="wrapper">
        <!-- #page -->
        <div id="page" class="">
            <!-- layout-wrap -->
            <div class="layout-wrap">
                <!-- preload -->
                <div id="preload" class="preload-container">
                    <div class="preloading">
                        <span></span>
                    </div>
                </div>
                <!-- /preload -->
                <!-- section-menu-left -->
                <div class="section-menu-left">
                    <div class="box-logo">
                        <a href="{{ route('admin.dashboard') }}" id="site-logo-inner">
                            <img class="" id="logo_header" alt=""
                                src="{{ asset('https://themesflat.co/html/ecomus/images/logo/logo.svg') }}"
                                data-light="{{ asset('https://themesflat.co/html/ecomus/images/logo/logo.svg') }}"
                                data-dark="{{ asset('https://themesflat.co/html/ecomus/images/logo/logo-white.svg') }}">
                        </span></a>
                        <div class="button-show-hide">
                            <i class="icon-chevron-left"></i>
                        </div>
                    </div>
                    <div class="section-menu-left-wrap">
                        <div class="center">
                            <div class="center-item">
                                <ul class="">
    <li class="menu-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <div class="icon"><i class="icon-grid"></i></div>
            <div class="text">Dashboard</div>
        </a>
    </li>

    <li class="menu-item has-children {{ request()->routeIs('products.*') || request()->is('admin/products*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link menu-item-button {{ request()->routeIs('products.*') || request()->is('admin/products*') ? 'active' : '' }}">
            <div class="icon"><i class="icon-package"></i></div>
            <div class="text">Sản phẩm</div>
        </a>
        <ul class="sub-menu" style="{{ request()->routeIs('products.*') || request()->is('admin/products*') ? 'display: block !important;' : '' }}">
            <li class="sub-menu-item">
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}">
                    <div class="text">Danh sách sản phẩm</div>
                </a>
            </li>
            <li class="sub-menu-item">
                <a href="{{ route('products.create') }}" class="nav-link {{ request()->routeIs('products.create') ? 'active' : '' }}">
                    <div class="text">Thêm sản phẩm</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item has-children {{ request()->routeIs('admin.orders.*') || request()->is('admin/orders*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link menu-item-button {{ request()->routeIs('admin.orders.*') || request()->is('admin/orders*') ? 'active' : '' }}">
            <div class="icon"><i class="icon-shopping-cart"></i></div>
            <div class="text">Đơn hàng</div>
        </a>
        <ul class="sub-menu" style="{{ request()->routeIs('admin.orders.*') || request()->is('admin/orders*') ? 'display: block !important;' : '' }}">
            <li class="sub-menu-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                    <div class="text">Danh sách đơn hàng</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item has-children {{ request()->routeIs('Admin.categories.*') || request()->is('admin/categories*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link menu-item-button {{ request()->routeIs('Admin.categories.*') || request()->is('admin/categories*') ? 'active' : '' }}">
            <div class="icon"><i class="icon-folder"></i></div>
            <div class="text">Danh mục</div>
        </a>
        <ul class="sub-menu" style="{{ request()->routeIs('Admin.categories.*') || request()->is('admin/categories*') ? 'display: block !important;' : '' }}">
            <li class="sub-menu-item">
                <a href="{{ route('Admin.categories.index') }}" class="nav-link {{ request()->routeIs('Admin.categories.index') ? 'active' : '' }}">
                    <div class="text">Danh sách danh mục</div>
                </a>
            </li>
            <li class="sub-menu-item">
                <a href="{{ route('Admin.categories.create') }}" class="nav-link {{ request()->routeIs('Admin.categories.create') ? 'active' : '' }}">
                    <div class="text">Thêm danh mục</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item has-children {{ request()->routeIs('Admin.comments.*') || request()->is('admin/comments*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link menu-item-button {{ request()->routeIs('Admin.comments.*') || request()->is('admin/comments*') ? 'active' : '' }}">
            <div class="icon"><i class="icon-message-square"></i></div>
            <div class="text">Bình luận</div>
        </a>
        <ul class="sub-menu" style="{{ request()->routeIs('Admin.comments.*') || request()->is('admin/comments*') ? 'display: block !important;' : '' }}">
            <li class="sub-menu-item">
                <a href="{{ route('Admin.comments.index') }}" class="nav-link {{ request()->routeIs('Admin.comments.index') ? 'active' : '' }}">
                    <div class="text">Danh sách bình luận</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item has-children {{ request()->routeIs('Admin.reviews.*') || request()->is('admin/reviews*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link menu-item-button {{ request()->routeIs('Admin.reviews.*') || request()->is('admin/reviews*') ? 'active' : '' }}">
            <div class="icon"><i class="icon-star"></i></div>
            <div class="text">Đánh giá</div>
        </a>
        <ul class="sub-menu" style="{{ request()->routeIs('Admin.reviews.*') || request()->is('admin/reviews*') ? 'display: block !important;' : '' }}">
            <li class="sub-menu-item">
                <a href="{{ route('Admin.reviews.index') }}" class="nav-link {{ request()->routeIs('Admin.reviews.index') ? 'active' : '' }}">
                    <div class="text">Danh sách đánh giá</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item has-children {{ request()->routeIs('admin.users.*') || request()->is('admin/users*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link menu-item-button {{ request()->routeIs('admin.users.*') || request()->is('admin/users*') ? 'active' : '' }}">
            <div class="icon"><i class="icon-users"></i></div>
            <div class="text">Người dùng</div>
        </a>
        <ul class="sub-menu" style="{{ request()->routeIs('admin.users.*') || request()->is('admin/users*') ? 'display: block !important;' : '' }}">
            <li class="sub-menu-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                    <div class="text">Danh sách người dùng</div>
                </a>
            </li>
            <li class="sub-menu-item">
                <a href="{{ route('admin.users.create') }}" class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                    <div class="text">Thêm người dùng</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item has-children {{ request()->routeIs('posts.*') || request()->is('admin/posts*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link menu-item-button {{ request()->routeIs('posts.*') || request()->is('admin/posts*') ? 'active' : '' }}">
            <div class="icon"><i class="icon-file-text"></i></div>
            <div class="text">Bài viết</div>
        </a>
        <ul class="sub-menu" style="{{ request()->routeIs('posts.*') || request()->is('admin/posts*') ? 'display: block !important;' : '' }}">
            <li class="sub-menu-item">
                <a href="{{ route('posts.index') }}" class="nav-link {{ request()->routeIs('posts.index') ? 'active' : '' }}">
                    <div class="text">Danh sách bài viết</div>
                </a>
            </li>
            <li class="sub-menu-item">
                <a href="{{ route('posts.create') }}" class="nav-link {{ request()->routeIs('posts.create') ? 'active' : '' }}">
                    <div class="text">Thêm bài viết</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item has-children {{ request()->routeIs('admin.discounts.*') || request()->is('admin/discounts*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link menu-item-button {{ request()->routeIs('admin.discounts.*') || request()->is('admin/discounts*') ? 'active' : '' }}">
            <div class="icon"><i class="icon-tag"></i></div>
            <div class="text">Mã Giảm Giá</div>
        </a>
        <ul class="sub-menu" style="{{ request()->routeIs('admin.discounts.*') || request()->is('admin/discounts*') ? 'display: block !important;' : '' }}">
            <li class="sub-menu-item">
                <a href="{{ route('admin.discounts.index') }}" class="nav-link {{ request()->routeIs('admin.discounts.index') ? 'active' : '' }}">
                    <div class="text">Danh sách mã giảm giá</div>
                </a>
            </li>
            <li class="sub-menu-item">
                <a href="{{ route('admin.discounts.create') }}" class="nav-link {{ request()->routeIs('admin.discounts.create') ? 'active' : '' }}">
                    <div class="text">Thêm mã giảm giá</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item has-children {{ request()->routeIs('admin.banners.*') || request()->is('admin/banners*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link menu-item-button {{ request()->routeIs('admin.banners.*') || request()->is('admin/banners*') ? 'active' : '' }}">
            <div class="icon"><i class="icon-image"></i></div>
            <div class="text">Banner</div>
        </a>
        <ul class="sub-menu" style="{{ request()->routeIs('admin.banners.*') || request()->is('admin/banners*') ? 'display: block !important;' : '' }}">
            <li class="sub-menu-item">
                <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.index') ? 'active' : '' }}">
                    <div class="text">Danh sách banner</div>
                </a>
            </li>
            <li class="sub-menu-item">
                <a href="{{ route('admin.banners.create') }}" class="nav-link {{ request()->routeIs('admin.banners.create') ? 'active' : '' }}">
                    <div class="text">Thêm banner</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item has-children {{ request()->routeIs('catalog.*') || request()->is('admin/catalog*') ? 'open' : '' }}">
        <a href="javascript:void(0);" class="nav-link menu-item-button {{ request()->routeIs('catalog.*') || request()->is('admin/catalog*') ? 'active' : '' }}">
            <div class="icon"><i class="icon-list"></i></div>
            <div class="text">Thuộc tính sản phẩm</div>
        </a>
        <ul class="sub-menu" style="{{ request()->routeIs('catalog.*') || request()->is('admin/catalog*') ? 'display: block !important;' : '' }}">
            <li class="sub-menu-item">
                <a href="{{ route('catalog.index') }}" class="nav-link {{ request()->routeIs('catalog.index') ? 'active' : '' }}">
                    <div class="text">Danh sách thuộc tính</div>
                </a>
            </li>
        </ul>
    </li>

    <li class="menu-item">
        <a href="{{ route('Admin.contacts.index') }}" class="nav-link {{ request()->routeIs('Admin.contacts.index') ? 'active' : '' }}">
            <div class="icon"><i class="icon-mail"></i></div>
            <div class="text">Quản lý liên hệ</div>
        </a>
    </li>

    <li class="menu-item">
        <a href="logout" class="nav-link">
            <div class="icon"><i class="icon-log-out"></i></div>
            <div class="text">Đăng Xuất</div>
        </a>
    </li>
</ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /section-menu-left -->
                <!-- section-content-right -->
                <div class="section-content-right">
                    <!-- header-dashboard -->
                    <div class="header-dashboard">
                        <div class="wrap">
                            <div class="header-left">
                                <a href="index-2.html">
                                    <img class="" id="logo_header_mobile" alt=""
                                        src="{{ asset('https://themesflat.co/html/ecomus/images/logo/logo.svg') }}"
                                        data-light="{{ asset('https://themesflat.co/html/ecomus/images/logo/logo.svg') }}"
                                        data-dark="{{ asset('https://themesflat.co/html/ecomus/images/logo/logo-white.svg') }}">
                                </span></a>
                                <div class="button-show-hide">
                                    <i class="icon-chevron-left"></i>
                                </div>
                                <form class="form-search flex-grow">
                                    <fieldset class="name">
                                        <input type="text" placeholder="Search" class="show-search" name="name"
                                            tabindex="2" value="" aria-required="true" required="">
                                    </fieldset>
                                    <div class="button-submit">
                                        <button class="" type="submit"><i class="icon-search"></i></button>
                                    </div>
                                    <div class="box-content-search" id="box-content-search">
                                        <ul class="mb-24">
                                            <li class="mb-14">
                                                <div class="body-title">Top selling product</div>
                                            </li>
                                            <li class="mb-14">
                                                <div class="divider"></div>
                                            </li>
                                            <li>
                                                <ul>
                                                    <li class="product-item gap14 mb-10">
                                                        <div class="image no-bg">
                                                            <img src="{{ asset('images/products/product-1.jpg') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Neptune
                                                                    Longsleeve</span></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="mb-10">
                                                        <div class="divider"></div>
                                                    </li>
                                                    <li class="product-item gap14 mb-10">
                                                        <div class="image no-bg">
                                                            <img src="{{ asset('images/products/product-2.jpg') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Ribbed
                                                                    Tank Top</span></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="mb-10">
                                                        <div class="divider"></div>
                                                    </li>
                                                    <li class="product-item gap14">
                                                        <div class="image no-bg">
                                                            <img src="{{ asset('images/products/product-3.jpg') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Ribbed
                                                                    modal T-shirt</span></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <ul class="">
                                            <li class="mb-14">
                                                <div class="body-title">Order product</div>
                                            </li>
                                            <li class="mb-14">
                                                <div class="divider"></div>
                                            </li>
                                            <li>
                                                <ul>
                                                    <li class="product-item gap14 mb-10">
                                                        <div class="image no-bg">
                                                            <img src="{{ asset('images/products/product-4.jpg') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Oversized
                                                                    Motif T-shirt</span></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="mb-10">
                                                        <div class="divider"></div>
                                                    </li>
                                                    <li class="product-item gap14 mb-10">
                                                        <div class="image no-bg">
                                                            <img src="{{ asset('images/products/product-5.jpg') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">V-neck
                                                                    linen T-shirt</span></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="mb-10">
                                                        <div class="divider"></div>
                                                    </li>
                                                    <li class="product-item gap14 mb-10">
                                                        <div class="image no-bg">
                                                            <img src="{{ asset('images/products/product-6.jpg') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Jersey
                                                                    thong body</span></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li class="mb-10">
                                                        <div class="divider"></div>
                                                    </li>
                                                    <li class="product-item gap14">
                                                        <div class="image no-bg">
                                                            <img src="{{ asset('images/products/product-7.jpg') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="flex items-center justify-between gap20 flex-grow">
                                                            <div class="name">
                                                                <a href="product-list.html" class="body-text">Jersey
                                                                    thong body</span></a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </form>
                            </div>
                            <div class="header-grid">
                                <div class="header-item country">
                                    <select class="image-select no-text">
                                        <option data-thumbnail="{{ asset('images/country/9.png') }}">VIE</option>
                                        {{-- <option data-thumbnail="{{ asset('images/country/1.png') }}">ENG</option>
                                        --}}
                                    </select>
                                </div>
                                <div class="header-item button-dark-light">
                                    <i class="icon-moon"></i>
                                </div>
                                <div class="popup-wrap noti type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <span class="text-tiny">1</span>
                                                <i class="icon-bell"></i>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton1">
                                            <li>
                                                <h6>Notifications</h6>
                                            </li>
                                            <li>
                                                <div class="noti-item w-full wg-user active">
                                                    <div class="image">
                                                        <img src="{{ asset('images/customers/customer-1.jpg') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="flex-grow">
                                                        <div class="flex items-center justify-between">
                                                            <a href="#" class="body-title">Cameron
                                                                Williamson</span></a>
                                                            <div class="time">10:13 PM</div>
                                                        </div>
                                                        <div class="text-tiny">Hello?</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="noti-item w-full wg-user active">
                                                    <div class="image">
                                                        <img src="{{ asset('images/customers/customer-2.jpg') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="flex-grow">
                                                        <div class="flex items-center justify-between">
                                                            <a href="#" class="body-title">Ralph Edwards</span></a>
                                                            <div class="time">10:13 PM</div>
                                                        </div>
                                                        <div class="text-tiny">Are you there? interested i this...
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="noti-item w-full wg-user active">
                                                    <div class="image">
                                                        <img src="{{ asset('images/customers/customer-3.jpg') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="flex-grow">
                                                        <div class="flex items-center justify-between">
                                                            <a href="#" class="body-title">Eleanor Pena</span></a>
                                                            <div class="time">10:13 PM</div>
                                                        </div>
                                                        <div class="text-tiny">Interested in this loads?</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="noti-item w-full wg-user active">
                                                    <div class="image">
                                                        <img src="{{ asset('images/customers/customer-1.jpg') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="flex-grow">
                                                        <div class="flex items-center justify-between">
                                                            <a href="#" class="body-title">Jane Cooper</span></a>
                                                            <div class="time">10:13 PM</div>
                                                        </div>
                                                        <div class="text-tiny">Okay...Do we have a deal?</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li><a href="#" class="tf-button w-full">View all</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="popup-wrap message type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <span class="text-tiny">1</span>
                                                <i class="icon-message-square"></i>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton2">
                                            <li>
                                                <h6>Message</h6>
                                            </li>
                                            <li>
                                                <div class="message-item item-1">
                                                    <div class="image">
                                                        <i class="icon-noti-1"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Discount available</div>
                                                        <div class="text-tiny">Morbi sapien massa, ultricies at rhoncus
                                                            at, ullamcorper nec diam</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="message-item item-2">
                                                    <div class="image">
                                                        <i class="icon-noti-2"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Account has been verified</div>
                                                        <div class="text-tiny">Mauris libero ex, iaculis vitae rhoncus
                                                            et</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="message-item item-3">
                                                    <div class="image">
                                                        <i class="icon-noti-3"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Order shipped successfully</div>
                                                        <div class="text-tiny">Integer aliquam eros nec sollicitudin
                                                            sollicitudin</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="message-item item-4">
                                                    <div class="image">
                                                        <i class="icon-noti-4"></i>
                                                    </div>
                                                    <div>
                                                        <div class="body-title-2">Order pending: <span>ID 305830</span>
                                                        </div>
                                                        <div class="text-tiny">Ultricies at rhoncus at ullamcorper
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li><a href="#" class="tf-button w-full">View all</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="header-item button-zoom-maximize">
                                    <div class="">
                                        <i class="icon-maximize"></i>
                                    </div>
                                </div>
                                <div class="popup-wrap apps type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton4" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-item">
                                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M3.625 0.812501C3.06874 0.812501 2.52497 0.977451 2.06246 1.28649C1.59995 1.59553 1.23946 2.03479 1.02659 2.5487C0.813719 3.06262 0.758022 3.62812 0.866543 4.17369C0.975064 4.71926 1.24293 5.2204 1.63626 5.61374C2.0296 6.00707 2.53074 6.27494 3.07631 6.38346C3.62188 6.49198 4.18738 6.43628 4.7013 6.22341C5.21522 6.01054 5.65447 5.65006 5.96351 5.18754C6.27255 4.72503 6.4375 4.18126 6.4375 3.625C6.4375 2.87908 6.14118 2.16371 5.61374 1.63626C5.08629 1.10882 4.37092 0.812501 3.625 0.812501ZM3.625 5.3125C3.29125 5.3125 2.96498 5.21353 2.68748 5.02811C2.40997 4.84268 2.19368 4.57913 2.06595 4.27078C1.93823 3.96243 1.90481 3.62313 1.96993 3.29579C2.03504 2.96844 2.19576 2.66776 2.43176 2.43176C2.66776 2.19576 2.96844 2.03504 3.29579 1.96993C3.62313 1.90481 3.96243 1.93823 4.27078 2.06595C4.57913 2.19368 4.84268 2.40997 5.02811 2.68748C5.21353 2.96498 5.3125 3.29125 5.3125 3.625C5.3125 4.07255 5.13471 4.50178 4.81824 4.81824C4.50178 5.13471 4.07255 5.3125 3.625 5.3125ZM10.375 6.4375C10.9313 6.4375 11.475 6.27255 11.9375 5.96351C12.4001 5.65447 12.7605 5.21522 12.9734 4.7013C13.1863 4.18738 13.242 3.62188 13.1335 3.07631C13.0249 2.53074 12.7571 2.0296 12.3637 1.63626C11.9704 1.24293 11.4693 0.975064 10.9237 0.866543C10.3781 0.758022 9.81262 0.813719 9.2987 1.02659C8.78479 1.23946 8.34553 1.59995 8.03649 2.06246C7.72745 2.52497 7.5625 3.06874 7.5625 3.625C7.5625 4.37092 7.85882 5.08629 8.38626 5.61374C8.91371 6.14118 9.62908 6.4375 10.375 6.4375ZM10.375 1.9375C10.7088 1.9375 11.035 2.03647 11.3125 2.2219C11.59 2.40732 11.8063 2.67087 11.934 2.97922C12.0618 3.28757 12.0952 3.62687 12.0301 3.95422C11.965 4.28156 11.8042 4.58224 11.5682 4.81824C11.3322 5.05425 11.0316 5.21496 10.7042 5.28008C10.3769 5.34519 10.0376 5.31177 9.72922 5.18405C9.42087 5.05633 9.15732 4.84003 8.9719 4.56253C8.78647 4.28502 8.6875 3.95876 8.6875 3.625C8.6875 3.17745 8.86529 2.74823 9.18176 2.43176C9.49823 2.11529 9.92745 1.9375 10.375 1.9375ZM3.625 7.5625C3.06874 7.5625 2.52497 7.72745 2.06246 8.03649C1.59995 8.34553 1.23946 8.78479 1.02659 9.2987C0.813719 9.81262 0.758022 10.3781 0.866543 10.9237C0.975064 11.4693 1.24293 11.9704 1.63626 12.3637C2.0296 12.7571 2.53074 13.0249 3.07631 13.1335C3.62188 13.242 4.18738 13.1863 4.7013 12.9734C5.21522 12.7605 5.65447 12.4001 5.96351 11.9375C6.27255 11.475 6.4375 10.9313 6.4375 10.375C6.4375 9.62908 6.14118 8.91371 5.61374 8.38626C5.08629 7.85882 4.37092 7.5625 3.625 7.5625ZM3.625 12.0625C3.29125 12.0625 2.96498 11.9635 2.68748 11.7781C2.40997 11.5927 2.19368 11.3291 2.06595 11.0208C1.93823 10.7124 1.90481 10.3731 1.96993 10.0458C2.03504 9.71844 2.19576 9.41776 2.43176 9.18176C2.66776 8.94576 2.96844 8.78504 3.29579 8.71993C3.62313 8.65481 3.96243 8.68823 4.27078 8.81595C4.57913 8.94368 4.84268 9.15997 5.02811 9.43748C5.21353 9.71498 5.3125 10.0412 5.3125 10.375C5.3125 10.8226 5.13471 11.2518 4.81824 11.5682C4.50178 11.8847 4.07255 12.0625 3.625 12.0625ZM10.375 7.5625C9.81874 7.5625 9.27497 7.72745 8.81246 8.03649C8.34995 8.34553 7.98946 8.78479 7.77659 9.2987C7.56372 9.81262 7.50802 10.3781 7.61654 10.9237C7.72506 11.4693 7.99293 11.9704 8.38626 12.3637C8.7796 12.7571 9.28074 13.0249 9.82631 13.1335C10.3719 13.242 10.9374 13.1863 11.4513 12.9734C11.9652 12.7605 12.4045 12.4001 12.7135 11.9375C13.0226 11.475 13.1875 10.9313 13.1875 10.375C13.1875 9.62908 12.8912 8.91371 12.3637 8.38626C11.8363 7.85882 11.1209 7.5625 10.375 7.5625ZM10.375 12.0625C10.0412 12.0625 9.71498 11.9635 9.43748 11.7781C9.15997 11.5927 8.94368 11.3291 8.81595 11.0208C8.68823 10.7124 8.65481 10.3731 8.71993 10.0458C8.78504 9.71844 8.94576 9.41776 9.18176 9.18176C9.41776 8.94576 9.71844 8.78504 10.0458 8.71993C10.3731 8.65481 10.7124 8.68823 11.0208 8.81595C11.3291 8.94368 11.5927 9.15997 11.7781 9.43748C11.9635 9.71498 12.0625 10.0412 12.0625 10.375C12.0625 10.8226 11.8847 11.2518 11.5682 11.5682C11.2518 11.8847 10.8226 12.0625 10.375 12.0625Z"
                                                        fill="#0A0A0C" />
                                                </svg>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton4">
                                            <li>
                                                <h6>Related apps</h6>
                                            </li>
                                            <li>
                                                <ul class="list-apps">
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-1.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Photoshop</div>
                                                        </span></a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-2.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">illustrator</div>
                                                        </span></a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-3.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Sheets</div>
                                                        </span></a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-4.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Gmail</div>
                                                        </span></a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-5.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Messenger</div>
                                                        </span></a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-6.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Youtube</div>
                                                        </span></a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-7.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Flaticon</div>
                                                        </span></a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-8.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Instagram</div>
                                                        </span></a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-9.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">PDF</div>
                                                        </span></a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a href="#" class="tf-button w-full">View all app</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="popup-wrap user type-header">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="header-user wg-user">
                                                <span class="image">
                                                    <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/avatar/avata.jpg') }}" alt="" style="width:40px;height:40px;object-fit:cover;">
                                                </span>
                                                <span class="flex flex-column">
                                                    <span class="body-text text-main-dark">{{ Auth::user()->name }}</span>
                                                    <span class="text-tiny">Quản trị viên bán hàng</span>
                                                </span>
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end has-content"
                                            aria-labelledby="dropdownMenuButton3">
                                            <li>
                                                <a href="{{ route('admin.users.edit', Auth::id()) }}" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                    <div class="body-title-2">Tài khoản</div>
                                                </span></a>
                                            </li>

                                            <li>
                                                <a href="/" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                    <div class="body-title-2">Trang Người Dùng</div>
                                                </span></a>
                                            </li>
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-mail"></i>
                                                    </div>
                                                    <div class="body-title-2">Hộp Thư</div>
                                                    <div class="number">27</div>
                                                </span></a>
                                            </li>
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-file-text"></i>
                                                    </div>
                                                    <div class="body-title-2">Công Việc</div>
                                                </span></a>
                                            </li>
                                            <li>
                                                <a href="setting.html" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-settings"></i>
                                                    </div>
                                                    <div class="body-title-2">Cài Đặt</div>
                                                </span></a>
                                            </li>
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-headphones"></i>
                                                    </div>
                                                    <div class="body-title-2">Hỗ Trợ</div>
                                                </span></a>
                                            </li>
                                            <li>
                                                <a href="logout" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-log-out"></i>
                                                    </div>
                                                    <div class="body-title-2">Đăng Xuất</div>
                                                </span></a>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /header-dashboard -->
                    <!-- main-content -->
                    <div class="main-content">
                        <!-- main-content-wrap -->
                        <div class="main-content-inner">
                            <!-- main-content-wrap -->
                            <div class="main-content-wrap">
                                @yield('main')
                            </div>
                            <!-- /main-content-wrap -->
                        </div>
                        <!-- /main-content-wrap -->
                        <!-- bottom-page -->
                        <div class="bottom-page">
                            <div class="body-text">Copyright © 2024 <a
                                    href="https://themesflat.co/html/ecomus/index.html">Ecomus</span></a>. Design by
                                Themesflat All rights reserved</div>
                        </div>
                        <!-- /bottom-page -->
                    </div>
                    <!-- /main-content -->
                </div>
                <!-- /section-content-right -->
            </div>
            <!-- /layout-wrap -->
        </div>
        <!-- /#page -->
    </div>
    <!-- /#wrapper -->

    <!-- Javascript -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="{{ asset('js/bootstrap.min.js') }}"></script> --}}
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('js/zoom.js') }}"></script>
    <script src="{{ asset('js/morris.min.js') }}"></script>
    <script src="{{ asset('js/raphael.min.js') }}"></script>
    <script src="{{ asset('js/morris.js') }}"></script>
    <script src="{{ asset('js/jvectormap.min.js') }}"></script>
    <script src="{{ asset('js/jvectormap-us-lcc.js') }}"></script>
    <script src="{{ asset('js/jvectormap-data.js') }}"></script>
    <script src="{{ asset('js/jvectormap.js') }}"></script>
    <script src="{{ asset('js/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-1.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-2.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-3.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-4.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-5.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-6.js') }}"></script>
    <script src="{{ asset('js/apexcharts/line-chart-7.js') }}"></script>
    <script src="{{ asset('js/switcher.js') }}"></script>
    <script defer src="{{ asset('js/theme-settings.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/admin-sidebar-improvements.js') }}"></script>
    <script src="{{ asset('js/sidebar-debug.js') }}"></script>
    @yield('scripts')

    @stack('scripts')
    @stack('styles')


    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.custom-alert').alert('close');
            }, 3500);
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#alert-stack .custom-alert').each(function(i, el) {
                setTimeout(function() {
                    $(el).fadeOut(400, function() { $(this).remove(); });
                }, 3000 + i * 300); // Cái sau trễ hơn cái trước 0.3s
            });
        });
    </script>
</body>


<!-- Mirrored from themesflat.co/html/ecomus/admin-ecomus/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 05 Jul 2025 17:41:11 GMT -->

</html>