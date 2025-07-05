<!DOCTYPE html>
<html lang="en">
<head>
    <title>Trang quản trị | Admin</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body onload="time()" class="app sidebar-mini rtl">

<header class="app-header">
    <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <ul class="app-nav">
        <li>
            <a class="app-nav__item" href="/index.html"><i class='bx bx-log-out bx-rotate-180'></i></a>
        </li>
    </ul>
</header>

<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="app-sidebar__user">
        <img class="app-sidebar__user-avatar" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTK2qGYzkY30aJPaED4wkyhyvDy7N3Tj8VFNtJqdZastNoGS8djsTgwSml2d042norw0C8&usqp=CAU" width="50px" alt="User Image">
        <div>
            <p class="app-sidebar__user-name"><b>Sudes Sport</b></p>
            <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
        </div>
    </div>
    <hr>

    <ul class="app-menu">

        {{-- Trang thống kê --}}
        <li>
            <a class="app-menu__item" href="{{ route('admin.dashboard') }}">
                <i class='app-menu__icon bx bx-bar-chart-alt-2'></i>
                <span class="app-menu__label">Trang thống kê</span>
            </a>
        </li>

        {{-- Sản phẩm --}}
        <li>
            <a class="app-menu__item" href="{{ route('products.index') }}">
                <i class='app-menu__icon bx bx-cube'></i>
                <span class="app-menu__label">Sản phẩm</span>
            </a>
        </li>

        {{-- Danh mục --}}
        <li>
            <a class="app-menu__item" href="{{ route('Admin.categories.index') }}">
                <i class='app-menu__icon bx bx-list-ul'></i>
                <span class="app-menu__label">Danh mục</span>
            </a>
        </li>

        {{-- Thuộc tính sản phẩm --}}
        <li>
            <a class="app-menu__item" href="{{ route('catalog.index') }}">
                <i class='app-menu__icon bx bx-collection'></i>
                <span class="app-menu__label">Thuộc tính</span>
            </a>
        </li>

        {{-- Bài viết --}}
        <li>
            <a class="app-menu__item" href="{{ route('posts.index') }}">
                <i class='app-menu__icon bx bx-edit'></i>
                <span class="app-menu__label">Bài viết</span>
            </a>
        </li>

        {{-- Phản hồi (Đánh giá + Bình luận) --}}
        <li class="treeview">
            <a class="app-menu__item" href="#" data-toggle="treeview">
                <i class='app-menu__icon bx bx-comment-detail'></i>
                <span class="app-menu__label">Phản hồi</span>
                <i class="treeview-indicator fa fa-angle-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a class="app-menu__item" href="{{ route('Admin.reviews.index') }}">
                        <i class='app-menu__icon bx bx-star'></i>
                        <span class="app-menu__label">Đánh giá</span>
                    </a>
                </li>
                <li>
                    <a class="app-menu__item" href="{{ route('Admin.comments.index') }}">
                        <i class='app-menu__icon bx bx-message'></i>
                        <span class="app-menu__label">Bình luận</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Đơn hàng --}}
        <li>
            <a class="app-menu__item" href="{{ route('admin.orders.index') }}">
                <i class='app-menu__icon bx bx-cart'></i>
                <span class="app-menu__label">Đơn hàng</span>
            </a>
        </li>

        {{-- Mã giảm giá --}}
        <li>
            <a class="app-menu__item" href="{{ route('admin.discounts.index') }}">
                <i class='app-menu__icon bx bx-purchase-tag'></i>
                <span class="app-menu__label">Mã giảm giá</span>
            </a>
        </li>

        {{-- Tài khoản --}}
        <li>
            <a class="app-menu__item" href="{{ route('admin.users.index') }}">
                <i class='app-menu__icon bx bx-user'></i>
                <span class="app-menu__label">Tài khoản</span>
            </a>
        </li>

        {{-- Banner --}}
        <li>
            <a class="app-menu__item" href="{{ route('admin.banners.index') }}">
                <i class='app-menu__icon bx bx-image'></i>
                <span class="app-menu__label">Banner</span>
            </a>
        </li>

    </ul>
</aside>


<main class="app-content">
    @yield('main')
    @yield('scripts')
</main>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/plugins/pace.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/chart.js') }}"></script>

<script>
    function time() {
        var today = new Date();
        var weekday = ["Chủ Nhật", "Thứ Hai", "Thứ Ba", "Thứ Tư", "Thứ Năm", "Thứ Sáu", "Thứ Bảy"];
        var day = weekday[today.getDay()];
        var dd = today.getDate().toString().padStart(2, '0');
        var mm = (today.getMonth() + 1).toString().padStart(2, '0');
        var yyyy = today.getFullYear();
        var h = today.getHours();
        var m = today.getMinutes().toString().padStart(2, '0');
        var s = today.getSeconds().toString().padStart(2, '0');
        document.getElementById("clock")?.innerHTML = `<span class="date">${day}, ${dd}/${mm}/${yyyy} - ${h} giờ ${m} phút ${s} giây</span>`;
        setTimeout(time, 1000);
    }
</script>

<script>
    function toggleDiscountInput() {
        const selected = document.querySelector('input[name="discount_type"]:checked')?.value;
        document.getElementById('amount_input')?.style.setProperty('display', selected === 'amount' ? 'block' : 'none');
        document.getElementById('percent_input')?.style.setProperty('display', selected === 'percent' ? 'block' : 'none');
    }

    document.addEventListener('DOMContentLoaded', function () {
        toggleDiscountInput();
        document.querySelectorAll('input[name="discount_type"]').forEach(function (input) {
            input.addEventListener('change', toggleDiscountInput);
        });
    });
</script>

@stack('scripts')
</body>
</html>
