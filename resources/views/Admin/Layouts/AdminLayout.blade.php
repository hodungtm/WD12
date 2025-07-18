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
    <title>Ecomus - Ultimate Admin Dashboard HTML</title>

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">




    <!-- Font -->
    <link rel="stylesheet" href="{{ asset('font/fonts.css') }}">

    <!-- Icon -->
    <link rel="stylesheet" href="{{ asset('icon/style.css') }}">

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images/favicon.png') }}">

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
                        </a>
                        <div class="button-show-hide">
                            <i class="icon-chevron-left"></i>
                        </div>
                    </div>
                    <div class="section-menu-left-wrap">
                        <div class="center">
                            <div class="center-item">
                                <ul class="">
                                    <li class="menu-item has-children">
                                        <a href="javascript:void(0);" class="menu-item-button">
                                            <div class="icon"><i class="icon-file-plus"></i></div>
                                            <div class="text">Sản phẩm</div>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item"><a href="{{ route('products.index') }}">
                                                    <div class="text">Danh sách sản phẩm</div>
                                                </a></li>
                                            <li class="sub-menu-item"><a href="{{ route('products.create') }}">
                                                    <div class="text">Thêm sản phẩm</div>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item has-children">
                                        <a href="javascript:void(0);" class="menu-item-button">
                                            <div class="icon"><i class="icon-shopping-cart"></i></div>
                                            <div class="text">Đơn hàng</div>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item"><a href="{{ route('admin.orders.index') }}">
                                                    <div class="text">Danh sách đơn hàng</div>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item has-children">
                                        <a href="javascript:void(0);" class="menu-item-button">
                                            <div class="icon"><i class="icon-layers"></i></div>
                                            <div class="text">Danh mục</div>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item"><a href="{{ route('Admin.categories.index') }}">
                                                    <div class="text">Danh sách danh mục</div>
                                                </a></li>
                                            <li class="sub-menu-item"><a href="{{ route('Admin.categories.create') }}">
                                                    <div class="text">Thêm danh mục</div>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item has-children">
                                        <a href="javascript:void(0);" class="menu-item-button">
                                            <div class="icon"><i class="icon-message-square"></i></div>
                                            <div class="text">Bình luận</div>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item"><a href="{{ route('Admin.comments.index') }}">
                                                    <div class="text">Danh sách bình luận</div>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item has-children">
                                        <a href="javascript:void(0);" class="menu-item-button">
                                            <div class="icon"><i class="icon-star"></i></div>
                                            <div class="text">Đánh giá</div>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item"><a href="{{ route('Admin.reviews.index') }}">
                                                    <div class="text">Danh sách đánh giá</div>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item has-children">
                                        <a href="javascript:void(0);" class="menu-item-button">
                                            <div class="icon"><i class="icon-user"></i></div>
                                            <div class="text">Người dùng</div>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item"><a href="{{ route('admin.users.index') }}">
                                                    <div class="text">Danh sách người dùng</div>
                                                </a></li>
                                            <li class="sub-menu-item"><a href="{{ route('admin.users.create') }}">
                                                    <div class="text">Thêm người dùng</div>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item has-children">
                                        <a href="javascript:void(0);" class="menu-item-button">
                                            <div class="icon"><i class="icon-file-text"></i></div>
                                            <div class="text">Bài viết</div>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item"><a href="{{ route('posts.index') }}">
                                                    <div class="text">Danh sách bài viết</div>
                                                </a></li>
                                            <li class="sub-menu-item"><a href="{{ route('posts.create') }}">
                                                    <div class="text">Thêm bài viết</div>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item has-children">
                                        <a href="javascript:void(0);" class="menu-item-button">
                                            <div class="icon"><i class="icon-tag"></i></div>
                                            <div class="text">Mã Giảm Giá</div>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item"><a href="{{ route('admin.discounts.index') }}">
                                                    <div class="text">Danh sách mã giảm giá</div>
                                                </a></li>
                                            <li class="sub-menu-item"><a href="{{ route('admin.discounts.create') }}">
                                                    <div class="text">Thêm mã giảm giá</div>
                                                </a></li>
                                            <li class="sub-menu-item"><a href="{{ route('admin.discounts.report') }}">
                                                    <div class="text">Báo cáo giảm giá</div>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item has-children">
                                        <a href="javascript:void(0);" class="menu-item-button">
                                            <div class="icon"><i class="icon-image"></i></div>
                                            <div class="text">Banner</div>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item"><a href="{{ route('admin.banners.index') }}">
                                                    <div class="text">Danh sách banner</div>
                                                </a></li>
                                            <li class="sub-menu-item"><a href="{{ route('admin.banners.create') }}">
                                                    <div class="text">Thêm banner</div>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item has-children">
                                        <a href="javascript:void(0);" class="menu-item-button">
                                            <div class="icon"><i class="icon-settings"></i></div>
                                            <div class="text">Thuộc tính sản phẩm</div>
                                        </a>
                                        <ul class="sub-menu">
                                            <li class="sub-menu-item"><a href="{{ route('catalog.index') }}">
                                                    <div class="text">Danh sách thuộc tính</div>
                                                </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item has-children">
                                        <a href="{{ route('Admin.contacts.index') }}" class="menu-item-button">
                                            <div class="icon"><i class="icon-settings"></i></div>
                                            <div class="text">Quản lý liên hệ</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="setting.html" class="">
                                            <div class="icon">
                                                <svg width="24" height="22" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M6.85353 1.81675C7.00421 0.91265 7.78644 0.25 8.70302 0.25H11.297C12.2136 0.25 12.9958 0.91265 13.1465 1.81675L13.36 3.0978C13.3789 3.21129 13.463 3.34312 13.6275 3.43418C13.7099 3.47981 13.7914 3.52694 13.8719 3.57554C14.0328 3.67272 14.1891 3.67975 14.297 3.63933L15.5139 3.18345C16.3722 2.86189 17.3372 3.208 17.7955 4.00177L19.0924 6.24821C19.5507 7.04199 19.368 8.05075 18.6603 8.63329L17.656 9.46011C17.5671 9.5333 17.4952 9.67172 17.4987 9.85864C17.4996 9.90566 17.5 9.95278 17.5 10C17.5 10.0472 17.4996 10.0943 17.4987 10.1413C17.4952 10.3283 17.5671 10.4667 17.656 10.5399L18.6603 11.3667C19.368 11.9492 19.5507 12.958 19.0924 13.7518L17.7955 15.9982C17.3372 16.792 16.3722 17.1381 15.5139 16.8165L14.297 16.3607C14.1891 16.3202 14.0328 16.3273 13.8719 16.4244C13.7914 16.4731 13.7099 16.5202 13.6275 16.5658C13.463 16.6569 13.3789 16.7887 13.36 16.9022L13.1465 18.1832C12.9958 19.0874 12.2136 19.75 11.297 19.75H8.70302C7.78644 19.75 7.00421 19.0873 6.85353 18.1832L6.64002 16.9022C6.62111 16.7887 6.53703 16.6569 6.37251 16.5658C6.29008 16.5202 6.20861 16.4731 6.12812 16.4245C5.96719 16.3273 5.81089 16.3203 5.703 16.3607L4.48613 16.8166C3.62781 17.1381 2.66282 16.792 2.20454 15.9982L0.907557 13.7518C0.44927 12.958 0.632026 11.9493 1.33966 11.3667L1.81634 11.9457L1.33966 11.3667L2.34401 10.5399C2.43291 10.4667 2.50477 10.3283 2.50131 10.1414C2.50044 10.0943 2.5 10.0472 2.5 10C2.5 9.95279 2.50044 9.90568 2.50131 9.85866C2.50477 9.67174 2.43291 9.53331 2.34401 9.46012L1.33966 8.63331C0.632025 8.05076 0.44927 7.042 0.907556 6.24823L2.20454 4.00179C2.66283 3.20801 3.62781 2.8619 4.48613 3.18346L5.70298 3.63934C5.81087 3.67975 5.96717 3.67273 6.12811 3.57555C6.2086 3.52695 6.29008 3.47981 6.37251 3.43418C6.53703 3.34312 6.62111 3.21129 6.64002 3.0978L6.85353 1.81675ZM8.70302 1.75C8.5197 1.75 8.36326 1.88253 8.33312 2.06335L8.11961 3.3444C8.01385 3.97899 7.59798 4.47031 7.09896 4.74654C7.03304 4.78303 6.96787 4.82073 6.90348 4.85961C6.41435 5.15497 5.77999 5.26999 5.17675 5.044L3.95989 4.58812C3.78823 4.52381 3.59523 4.59303 3.50358 4.75179L2.20659 6.99823C2.11494 7.15698 2.15149 7.35873 2.29301 7.47524L3.29737 8.30206C3.79348 8.71048 4.01162 9.31573 4.00105 9.88643C4.00035 9.92419 4 9.96205 4 10C4 10.038 4.00035 10.0758 4.00105 10.1136C4.01162 10.6843 3.79348 11.2895 3.29737 11.698L2.29302 12.5248L1.96067 12.1211L2.29302 12.5248C2.15149 12.6413 2.11494 12.843 2.20659 13.0018L3.50358 15.2482C3.59523 15.407 3.78823 15.4762 3.95989 15.4119L5.17676 14.956C5.78 14.73 6.41436 14.845 6.90349 15.1404C6.96787 15.1793 7.03304 15.217 7.09896 15.2535C7.59798 15.5297 8.01385 16.021 8.11961 16.6556L8.33312 17.9366C8.36326 18.1175 8.5197 18.25 8.70302 18.25H11.297C11.4803 18.25 11.6367 18.1175 11.6669 17.9366L11.8804 16.6556C11.9862 16.021 12.402 15.5297 12.901 15.2535C12.967 15.217 13.0321 15.1793 13.0965 15.1404C13.5856 14.845 14.22 14.73 14.8233 14.956L16.0401 15.4119C16.2118 15.4762 16.4048 15.407 16.4964 15.2482L17.7934 13.0018C17.8851 12.843 17.8485 12.6413 17.707 12.5248L16.7026 11.6979C16.2065 11.2895 15.9884 10.6843 15.999 10.1136C15.9997 10.0758 16 10.038 16 10C16 9.96205 15.9996 9.92419 15.9989 9.88642C15.9884 9.31571 16.2065 8.71046 16.7026 8.30204L17.707 7.47523C17.8485 7.35872 17.8851 7.15697 17.7934 6.99821L16.4964 4.75177C16.4048 4.59302 16.2118 4.5238 16.0401 4.58811L14.8232 5.04399C14.22 5.26998 13.5856 5.15496 13.0965 4.8596C13.0321 4.82072 12.967 4.78303 12.901 4.74654C12.402 4.47031 11.9862 3.979 11.8804 3.3444L11.6669 2.06335C11.6367 1.88253 11.4803 1.75 11.297 1.75H8.70302ZM9.99977 7.74992C8.75713 7.74992 7.74977 8.75728 7.74977 9.99992C7.74977 11.2426 8.75713 12.2499 9.99977 12.2499C11.2424 12.2499 12.2498 11.2426 12.2498 9.99992C12.2498 8.75728 11.2424 7.74992 9.99977 7.74992ZM6.24977 9.99992C6.24977 7.92885 7.9287 6.24992 9.99977 6.24992C12.0708 6.24992 13.7498 7.92885 13.7498 9.99992C13.7498 12.071 12.0708 13.7499 9.99977 13.7499C7.9287 13.7499 6.24977 12.071 6.24977 9.99992Z"
                                                        fill="#0A0A0C" />
                                                </svg>
                                            </div>
                                            <div class="text">Cài Đặt</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="faq.html" class="">
                                            <div class="icon">
                                                <svg width="24" height="22" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M18.2691 7.55799C17.9409 7.215 17.6014 6.86156 17.4734 6.55078C17.355 6.26612 17.348 5.79429 17.3411 5.33725C17.328 4.48761 17.3141 3.5248 16.6446 2.85536C15.9752 2.18592 15.0124 2.17199 14.1627 2.15893C13.7057 2.15196 13.2339 2.145 12.9492 2.02661C12.6393 1.89864 12.285 1.55913 11.942 1.23094C11.3413 0.653772 10.6588 0 9.75 0C8.84116 0 8.15953 0.653772 7.55799 1.23094C7.215 1.55913 6.86156 1.89864 6.55078 2.02661C6.26786 2.145 5.79429 2.15196 5.33725 2.15893C4.48761 2.17199 3.5248 2.18592 2.85536 2.85536C2.18592 3.5248 2.17634 4.48761 2.15893 5.33725C2.15196 5.79429 2.145 6.26612 2.02661 6.55078C1.89864 6.86069 1.55913 7.215 1.23094 7.55799C0.653772 8.15866 0 8.84116 0 9.75C0 10.6588 0.653772 11.3405 1.23094 11.942C1.55913 12.285 1.89864 12.6384 2.02661 12.9492C2.145 13.2339 2.15196 13.7057 2.15893 14.1627C2.17199 15.0124 2.18592 15.9752 2.85536 16.6446C3.5248 17.3141 4.48761 17.328 5.33725 17.3411C5.79429 17.348 6.26612 17.355 6.55078 17.4734C6.86069 17.6014 7.215 17.9409 7.55799 18.2691C8.15866 18.8462 8.84116 19.5 9.75 19.5C10.6588 19.5 11.3405 18.8462 11.942 18.2691C12.285 17.9409 12.6384 17.6014 12.9492 17.4734C13.2339 17.355 13.7057 17.348 14.1627 17.3411C15.0124 17.328 15.9752 17.3141 16.6446 16.6446C17.3141 15.9752 17.328 15.0124 17.3411 14.1627C17.348 13.7057 17.355 13.2339 17.4734 12.9492C17.6014 12.6393 17.9409 12.285 18.2691 11.942C18.8462 11.3413 19.5 10.6588 19.5 9.75C19.5 8.84116 18.8462 8.15953 18.2691 7.55799ZM17.2636 10.9783C16.8466 11.4136 16.4148 11.8637 16.1859 12.4165C15.9665 12.9475 15.9569 13.5542 15.9482 14.1419C15.9395 14.7512 15.9299 15.3893 15.6592 15.6592C15.3885 15.9291 14.7547 15.9395 14.1419 15.9482C13.5542 15.9569 12.9475 15.9665 12.4165 16.1859C11.8637 16.4148 11.4136 16.8466 10.9783 17.2636C10.5431 17.6806 10.0982 18.1071 9.75 18.1071C9.40179 18.1071 8.95346 17.6788 8.52167 17.2636C8.08989 16.8483 7.63634 16.4148 7.08355 16.1859C6.55252 15.9665 5.94576 15.9569 5.35815 15.9482C4.74877 15.9395 4.11067 15.9299 3.8408 15.6592C3.57094 15.3885 3.56049 14.7547 3.55179 14.1419C3.54308 13.5542 3.5335 12.9475 3.31413 12.4165C3.08518 11.8637 2.65339 11.4136 2.23641 10.9783C1.81942 10.5431 1.39286 10.0982 1.39286 9.75C1.39286 9.40179 1.82116 8.95346 2.23641 8.52167C2.65165 8.08989 3.08518 7.63634 3.31413 7.08355C3.5335 6.55252 3.54308 5.94576 3.55179 5.35815C3.56049 4.74877 3.57007 4.11067 3.8408 3.8408C4.11154 3.57094 4.74529 3.56049 5.35815 3.55179C5.94576 3.54308 6.55252 3.5335 7.08355 3.31413C7.63634 3.08518 8.08641 2.65339 8.52167 2.23641C8.95694 1.81942 9.40179 1.39286 9.75 1.39286C10.0982 1.39286 10.5465 1.82116 10.9783 2.23641C11.4101 2.65165 11.8637 3.08518 12.4165 3.31413C12.9475 3.5335 13.5542 3.54308 14.1419 3.55179C14.7512 3.56049 15.3893 3.57007 15.6592 3.8408C15.9291 4.11154 15.9395 4.74529 15.9482 5.35815C15.9569 5.94576 15.9665 6.55252 16.1859 7.08355C16.4148 7.63634 16.8466 8.08641 17.2636 8.52167C17.6806 8.95694 18.1071 9.40179 18.1071 9.75C18.1071 10.0982 17.6788 10.5465 17.2636 10.9783ZM10.7946 14.2768C10.7946 14.4834 10.7334 14.6854 10.6186 14.8572C10.5038 15.0289 10.3407 15.1628 10.1498 15.2419C9.95888 15.321 9.74884 15.3417 9.5462 15.3014C9.34356 15.261 9.15742 15.1616 9.01133 15.0155C8.86523 14.8694 8.76574 14.6832 8.72543 14.4806C8.68512 14.2779 8.70581 14.0679 8.78488 13.877C8.86394 13.6861 8.99784 13.523 9.16963 13.4082C9.34142 13.2934 9.54339 13.2321 9.75 13.2321C10.0271 13.2321 10.2928 13.3422 10.4887 13.5381C10.6846 13.734 10.7946 13.9997 10.7946 14.2768ZM13.2321 8.00893C13.2321 9.52192 12.0343 10.7885 10.4464 11.0802V11.1429C10.4464 11.3276 10.3731 11.5047 10.2425 11.6353C10.1118 11.7659 9.9347 11.8393 9.75 11.8393C9.5653 11.8393 9.38816 11.7659 9.25755 11.6353C9.12695 11.5047 9.05357 11.3276 9.05357 11.1429V10.4464C9.05357 10.2617 9.12695 10.0846 9.25755 9.95398C9.38816 9.82337 9.5653 9.75 9.75 9.75C10.9017 9.75 11.8393 8.96652 11.8393 8.00893C11.8393 7.05134 10.9017 6.26786 9.75 6.26786C8.59828 6.26786 7.66071 7.05134 7.66071 8.00893V8.35714C7.66071 8.54185 7.58734 8.71899 7.45674 8.84959C7.32613 8.9802 7.14899 9.05357 6.96429 9.05357C6.77958 9.05357 6.60244 8.9802 6.47184 8.84959C6.34123 8.71899 6.26786 8.54185 6.26786 8.35714V8.00893C6.26786 6.28092 7.8296 4.875 9.75 4.875C11.6704 4.875 13.2321 6.28092 13.2321 8.00893Z"
                                                        fill="#111111" />
                                                </svg>
                                            </div>
                                            <div class="text">FAQ</div>
                                        </a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="login.html" class="">
                                            <div class="icon">
                                                <svg width="24" height="22" viewBox="0 0 20 20" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M8.125 18.6875C8.125 18.903 8.0394 19.1097 7.88702 19.262C7.73465 19.4144 7.52799 19.5 7.3125 19.5H1.625C1.19402 19.5 0.780698 19.3288 0.475951 19.024C0.171205 18.7193 0 18.306 0 17.875V1.625C0 1.19402 0.171205 0.780698 0.475951 0.475951C0.780698 0.171205 1.19402 0 1.625 0H7.3125C7.52799 0 7.73465 0.0856026 7.88702 0.237976C8.0394 0.390349 8.125 0.597012 8.125 0.8125C8.125 1.02799 8.0394 1.23465 7.88702 1.38702C7.73465 1.5394 7.52799 1.625 7.3125 1.625H1.625V17.875H7.3125C7.52799 17.875 7.73465 17.9606 7.88702 18.113C8.0394 18.2653 8.125 18.472 8.125 18.6875ZM19.2623 9.17516L15.1998 5.11266C15.0474 4.9602 14.8406 4.87455 14.625 4.87455C14.4094 4.87455 14.2026 4.9602 14.0502 5.11266C13.8977 5.26511 13.812 5.47189 13.812 5.6875C13.812 5.90311 13.8977 6.10989 14.0502 6.26234L16.7263 8.9375H7.3125C7.09701 8.9375 6.89035 9.0231 6.73798 9.17548C6.5856 9.32785 6.5 9.53451 6.5 9.75C6.5 9.96549 6.5856 10.1722 6.73798 10.3245C6.89035 10.4769 7.09701 10.5625 7.3125 10.5625H16.7263L14.0502 13.2377C13.8977 13.3901 13.812 13.5969 13.812 13.8125C13.812 14.0281 13.8977 14.2349 14.0502 14.3873C14.2026 14.5398 14.4094 14.6255 14.625 14.6255C14.8406 14.6255 15.0474 14.5398 15.1998 14.3873L19.2623 10.3248C19.3379 10.2494 19.3978 10.1598 19.4387 10.0611C19.4796 9.9625 19.5006 9.85678 19.5006 9.75C19.5006 9.64322 19.4796 9.5375 19.4387 9.43886C19.3978 9.34023 19.3379 9.25062 19.2623 9.17516Z"
                                                        fill="#111111" />
                                                </svg>
                                            </div>
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
                                </a>
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
                                                                    Longsleeve</a>
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
                                                                    Tank Top</a>
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
                                                                    modal T-shirt</a>
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
                                                                    Motif T-shirt</a>
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
                                                                    linen T-shirt</a>
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
                                                                    thong body</a>
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
                                                                    thong body</a>
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
                                                                Williamson</a>
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
                                                            <a href="#" class="body-title">Ralph Edwards</a>
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
                                                            <a href="#" class="body-title">Eleanor Pena</a>
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
                                                            <a href="#" class="body-title">Jane Cooper</a>
                                                            <div class="time">10:13 PM</div>
                                                        </div>
                                                        <div class="text-tiny">Okay...Do we have a deal?</div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li><a href="#" class="tf-button w-full">View all</a></li>
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
                                            <li><a href="#" class="tf-button w-full">View all</a></li>
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
                                                        </a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-2.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">illustrator</div>
                                                        </a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-3.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Sheets</div>
                                                        </a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-4.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Gmail</div>
                                                        </a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-5.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Messenger</div>
                                                        </a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-6.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Youtube</div>
                                                        </a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-7.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Flaticon</div>
                                                        </a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-8.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">Instagram</div>
                                                        </a>
                                                    </li>
                                                    <li class="item">
                                                        <div class="image">
                                                            <img src="{{ asset('images/apps/item-9.png') }}" alt="">
                                                        </div>
                                                        <a href="#">
                                                            <div class="text-tiny">PDF</div>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a href="#" class="tf-button w-full">View all app</a></li>
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
                                                </a>
                                            </li>

                                            <li>
                                                <a href="/" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                    <div class="body-title-2">Trang Người Dùng</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-mail"></i>
                                                    </div>
                                                    <div class="body-title-2">Hộp Thư</div>
                                                    <div class="number">27</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-file-text"></i>
                                                    </div>
                                                    <div class="body-title-2">Công Việc</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="setting.html" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-settings"></i>
                                                    </div>
                                                    <div class="body-title-2">Cài Đặt</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-headphones"></i>
                                                    </div>
                                                    <div class="body-title-2">Hỗ Trợ</div>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="logout" class="user-item">
                                                    <div class="icon">
                                                        <i class="icon-log-out"></i>
                                                    </div>
                                                    <div class="body-title-2">Đăng Xuất</div>
                                                </a>
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
                                    href="https://themesflat.co/html/ecomus/index.html">Ecomus</a>. Design by
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