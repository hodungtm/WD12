<!DOCTYPE html>
<html lang="en">
<head>
<title>Trang quản trị | Admin</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Main CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
<!-- or -->
<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
<!-- Font-icon css-->
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<!-- Bootstrap JS (v5) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Bundle JS (gồm cả Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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
        <a href="/home">
        <img class="app-sidebar__user-avatar" src="https://img.tripi.vn/cdn-cgi/image/width=700,height=700/https://gcs.tripi.vn/public-tripi/tripi-feed/img/482621VPj/anh-mo-ta.png" width="50px" alt="User Image">
        </a>
        <div>
            <p class="app-sidebar__user-name"><b>Sudes Sport</b></p>
            <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
        </div>
    </div>
    <hr>

  <ul class="app-menu">

    {{-- Trang chủ Admin --}}
    <li>
        <a class="app-menu__item" href="{{ route('admin.dashboard') }}">
            <i class='app-menu__icon bx bx-bar-chart-alt-2'></i>
            <span class="app-menu__label">Trang thống kê</span>
        </a>
    </li>

    {{-- Quản lý sản phẩm --}}
 <li>
    <a class="app-menu__item" href="{{ route('catalog.index') }}">
        <i class='app-menu__icon bx bx-collection'></i>
        <span class="app-menu__label">Thuộc tính (Catalog)</span>
    </a>
</li>
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
            <span class="app-menu__label">Quản lý danh mục</span>
        </a>
    </li>

    {{-- Bài viết --}}
    <li>
        <a class="app-menu__item" href="{{ route('posts.index') }}">
            <i class='app-menu__icon bx bx-edit'></i>
            <span class="app-menu__label">Quản lý bài viết</span>
        </a>
    </li>

    {{-- Đánh giá - Bình luận --}}
    <li class="treeview">
        <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class='app-menu__icon bx bx-comment-detail'></i>
            <span class="app-menu__label">Phản hồi khách hàng</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a class="treeview-item" href="{{ route('Admin.reviews.index') }}">Đánh giá</a></li>
            <li><a class="treeview-item" href="{{ route('Admin.comments.index') }}">Bình luận</a></li>
        </ul>
    </li>

    {{-- Mã giảm giá --}}
    <li>
        <a class="app-menu__item" href="{{ route('admin.discounts.index') }}">
            <i class='app-menu__icon bx bx-purchase-tag'></i>
            <span class="app-menu__label">Mã giảm giá</span>
        </a>
    </li>

    {{-- Đơn hàng --}}
    <li>
        <a class="app-menu__item" href="{{ route('admin.orders.index') }}">
            <i class='app-menu__icon bx bx-cart'></i>
            <span class="app-menu__label">Quản lý đơn hàng</span>
        </a>
    </li>

    {{-- Quản lý tài khoản --}}
    <li>
        <a class="app-menu__item" href="{{ route('admin.users.index') }}">
            <i class='app-menu__icon bx bx-user'></i>
            <span class="app-menu__label">Quản lý tài khoản khách</span>
        </a>
    </li>

    {{-- Quản trị viên (nếu có mở lại route admins) --}}
    {{-- 
    <li>
        <a class="app-menu__item" href="{{ route('admin.admins.index') }}">
            <i class='app-menu__icon bx bx-shield'></i>
            <span class="app-menu__label">Quản lý admin</span>
        </a>
    </li>
    --}}

    {{-- Banner --}}
    <li>
        <a class="app-menu__item" href="{{ route('admin.banners.index') }}">
            <i class='app-menu__icon bx bx-image'></i>
            <span class="app-menu__label">Quản lý banner</span>
        </a>
    </li>

    {{-- Nhật ký hệ thống --}}
    <li>
        <a class="app-menu__item" href="{{ route('admin.audit_logs.index') }}">
            <i class='app-menu__icon bx bx-book'></i>
            <span class="app-menu__label">Nhật ký hệ thống</span>
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



  <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="https://unpkg.com/boxicons@latest/dist/boxicons.js"></script>
<!--===============================================================================================-->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!--===============================================================================================-->
<script src="{{ asset('js/main.js') }}"></script>
<!--===============================================================================================-->
<script src="{{ asset('js/plugins/pace.min.js') }}"></script>
<!--===============================================================================================-->
<script type="text/javascript" src="{{ asset('js/plugins/chart.js') }}"></script>
<!--===============================================================================================-->
<script type="text/javascript">
    // Your custom JavaScript code here


    var data = {
      labels: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6"],
      datasets: [{
        label: "Dữ liệu đầu tiên",
        fillColor: "rgba(255, 213, 59, 0.767), 212, 59)",
        strokeColor: "rgb(255, 212, 59)",
        pointColor: "rgb(255, 212, 59)",
        pointStrokeColor: "rgb(255, 212, 59)",
        pointHighlightFill: "rgb(255, 212, 59)",
        pointHighlightStroke: "rgb(255, 212, 59)",
        data: [20, 59, 90, 51, 56, 100]
      },
      {
        label: "Dữ liệu kế tiếp",
        fillColor: "rgba(9, 109, 239, 0.651)  ",
        pointColor: "rgb(9, 109, 239)",
        strokeColor: "rgb(9, 109, 239)",
        pointStrokeColor: "rgb(9, 109, 239)",
        pointHighlightFill: "rgb(9, 109, 239)",
        pointHighlightStroke: "rgb(9, 109, 239)",
        data: [48, 48, 49, 39, 86, 10]
      }
      ]
    };
    var ctxl = $("#lineChartDemo").get(0).getContext("2d");
    var lineChart = new Chart(ctxl).Line(data);

    var ctxb = $("#barChartDemo").get(0).getContext("2d");
    var barChart = new Chart(ctxb).Bar(data);
  </script>
  <script type="text/javascript">
    //Thời Gian
=======
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
