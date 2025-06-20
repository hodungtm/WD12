<!DOCTYPE html>
<html lang="en">

<head>
    <title>Danh sách nhân viên | Quản trị Admin</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    {{-- <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css"> --}} <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> --}} <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    {{-- 
        CÁC SCRIPT NÊN ĐẶT Ở CUỐI BODY ĐỂ TRANG TẢI NHANH HƠN.
        Riêng jQuery và Bootstrap Bundle JS cần được tải trước các script khác sử dụng chúng.
    --}}
</head>

<body onload="time()" class="app sidebar-mini rtl">
    <header class="app-header">
        <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
        <ul class="app-nav">
            <li><a class="app-nav__item" href="/index.html"><i class='bx bx-log-out bx-rotate-180'></i> </a></li>
        </ul>
    </header>

    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
        <div class="app-sidebar__user">
            <img class="app-sidebar__user-avatar" src="https://img.tripi.vn/cdn-cgi/image/width=700,height=700/https://gcs.tripi.vn/public-tripi/tripi-feed/img/482621VPj/anh-mo-ta.png" width="50px" alt="User Image">
            <div>
                <p class="app-sidebar__user-name"><b>Sudes Sport</b></p>
                <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
            </div>
        </div>
        <hr>
        <ul class="app-menu">
            <li><a class="app-menu__item" href="{{ route('admin.dashboard') }}"><i class='app-menu__icon bx bx-purchase-tag-alt'></i><span class="app-menu__label">Thống kê</span></a></li>
            <li><a class="app-menu__item" href="{{ route('Admin.categories.index') }}"><i class='app-menu__icon bx bx-purchase-tag-alt'></i><span class="app-menu__label">Quản lý danh mục</span></a></li>
            <li><a class="app-menu__item" href="{{ route('Admin.reviews.index') }}"><i class='app-menu__icon bx bx-task'></i><span class="app-menu__label">Quản lý đánh giá</span></a></li>
            <li><a class="app-menu__item" href="{{ route('Admin.comments.index') }}"><i class='app-menu__icon bx bx-user-voice'></i><span class="app-menu__label">Quản lý bình luận</span></a></li>
            <li><a class="app-menu__item" href="{{ route('posts.index') }}"><i class='app-menu__icon bx bx-edit'></i><span class="app-menu__label">Quản lý Bài Viết</span></a></li>
            <li><a class="app-menu__item " href="table-data-table.html"><i class='app-menu__icon bx bx-id-card'></i> <span class="app-menu__label">Quản lý nhân viên</span></a></li>
            <li><a class="app-menu__item" href="{{ route('admin.discounts.index') }}"><i class='app-menu__icon bx bx-purchase-tag-alt'></i><span class="app-menu__label">Quản lý Mã Giảm Giá</span></a></li>
            <li><a class="app-menu__item" href="{{ route('admin.attribute.index') }}"><i class='app-menu__icon bx bx-task'></i><span class="app-menu__label">Quản lý thuộc tính</span></a></li>
            <li><a class="app-menu__item" href="{{ route('admin.attributeValue.index') }}"><i class='app-menu__icon bx bx-task'></i><span class="app-menu__label">Quản lý giá trị</span></a></li>
            <li><a class="app-menu__item" href="{{ route('admin.brand.index') }}"><i class='app-menu__icon bx bx-task'></i><span class="app-menu__label">Quản lý thương hiệu</span></a></li>
            <li><a class="app-menu__item" href="#"><i class='app-menu__icon bx bx-user-voice'></i><span class="app-menu__label">Quản lý khách hàng</span></a></li>
            <li><a class="app-menu__item" href="{{ route("Admin.products.index") }}"><i class='app-menu__icon bx bx-purchase-tag-alt'></i><span class="app-menu__label">Quản lý sản phẩm</span></a></li>
            <li><a class="app-menu__item" href="{{ route('admin.orders.index') }}"><i class='app-menu__icon bx bx-task'></i><span class="app-menu__label">Quản lý đơn hàng</span></a></li>
            <li><a class="app-menu__item" href="{{ route('admin.users.index') }}"><i class='app-menu__icon bx bx-user'></i><span class="app-menu__label">Quản lý tài khoản</span></a></li>
            <li><a class="app-menu__item" href="{{ route('admin.banners.index') }}"><i class='app-menu__icon bx bx-image'></i><span class="app-menu__label">Quản lý banner</span></a></li>
            <li><a class="app-menu__item" href="{{ route('admin.dashboard') }}"><i class='app-menu__icon bx bx-bar-chart-alt-2'></i><span class="app-menu__label">Trang Thống Kê</span></a></li>
        </ul>
    </aside>

    <main class="app-content">
        @yield('main')
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('js/main.js') }}"></script>

    {{-- <script src="{{ asset('js/popper.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/bootstrap.min.js') }}"></script> --}}

    <script src="{{ asset('js/plugins/pace.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/plugins/chart.js') }}"></script>

    <script src="https://unpkg.com/boxicons@latest/dist/boxicons.js"></script>

<<<<<<< HEAD
  {{------------------------ AE GẮN LINK CHUYỂN TRANG Ở DƯỚI ĐÂY NHÉ -------------------------------}}
  <!-- Sidebar menu-->
  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
  <aside class="app-sidebar">
    <div class="app-sidebar__user"><img class="app-sidebar__user-avatar"
        src="https://img.tripi.vn/cdn-cgi/image/width=700,height=700/https://gcs.tripi.vn/public-tripi/tripi-feed/img/482621VPj/anh-mo-ta.png"
        width="50px" alt="User Image">
      <div>
        <p class="app-sidebar__user-name"><b>Sudes Sport</b></p>
        <p class="app-sidebar__user-designation">Chào mừng bạn trở lại</p>
      </div>
    </div>
    <hr>
    <ul class="app-menu">
      <li>
        <a class="app-menu__item" href="{{ route('admin.dashboard') }}">
          <i class='app-menu__icon bx bx-purchase-tag-alt'></i>
          <span class="app-menu__label">Thống kê</span>
        </a>
      </li>
      <li>
        <a class="app-menu__item" href="{{ route('Admin.categories.index') }}">
          <i class='app-menu__icon bx bx-purchase-tag-alt'></i>
          <span class="app-menu__label">Quản lý danh mục</span>
        </a>
      </li>
      <li><a class="app-menu__item" href="{{ route('Admin.reviews.index') }}"><i
            class='app-menu__icon bx bx-task'></i><span class="app-menu__label">Quản lý đánh giá</span></a></li>
      <li><a class="app-menu__item" href="{{ route('Admin.comments.index') }}"><i
            class='app-menu__icon bx bx-user-voice'></i><span class="app-menu__label">Quản lý bình luận</span></a></li>
      <li><a class="app-menu__item" href="{{ route('posts.index') }}"><i class='app-menu__icon bx bx-edit'></i><span
            class="app-menu__label">Quản lý Bài Viết</span></a></li>
      <li><a class="app-menu__item " href="table-data-table.html"><i class='app-menu__icon bx bx-id-card'></i> <span
            class="app-menu__label">Quản lý nhân viên</span></a></li>
      <li>
        <a class="app-menu__item" href="{{ route('admin.discounts.index') }}">
          <i class='app-menu__icon bx bx-purchase-tag-alt'></i>
          <span class="app-menu__label">Quản lý Mã Giảm Giá</span>
        </a>
      </li>
      <li><a class="app-menu__item" href="#"><i class='app-menu__icon bx bx-user-voice'></i><span
            class="app-menu__label">Quản lý khách hàng</span></a></li>

     
      <li><a class="app-menu__item" href="{{ route('admin.orders.index') }}"><i
            class='app-menu__icon bx bx-task'></i><span class="app-menu__label">Quản lý đơn hàng</span></a></li>
      <li><a class="app-menu__item" href="{{ route('admin.admins.index') }}"><i
            class='app-menu__icon bx bx-user'></i><span class="app-menu__label">Quản lý tài khoản admin
          </span></a></li>
      <li>
        <a class="app-menu__item" href="{{ route('admin.banners.index') }}">
          <i class='app-menu__icon bx bx-image'></i>
          <span class="app-menu__label">Quản lý banner</span>
        </a>
      </li>
     <li>
  <a class="app-menu__item" href="{{ route('admin.dashboard') }}">
    <i class='app-menu__icon bx bx-bar-chart-alt-2'></i>
    <span class="app-menu__label">Trang Thống Kê</span>
  </a>
</li>
                <li>
                <a class="app-menu__item" href="catalog"><i class='app-menu__icon bx bx-collection'></i><span class="app-menu__label">catalog</span></a>
            </li>
            <li>
                <a class="app-menu__item" href="products"><i class='app-menu__icon bx bx-cube'></i><span class="app-menu__label">Products</span></a>
            </li>
=======
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
            }, {
                label: "Dữ liệu kế tiếp",
                fillColor: "rgba(9, 109, 239, 0.651)  ",
                pointColor: "rgb(9, 109, 239)",
                strokeColor: "rgb(9, 109, 239)",
                pointStrokeColor: "rgb(9, 109, 239)",
                pointHighlightFill: "rgb(9, 109, 239)",
                pointHighlightStroke: "rgb(9, 109, 239)",
                data: [48, 48, 49, 39, 86, 10]
            }]
        };
        var ctxl = $("#lineChartDemo").get(0).getContext("2d");
        var lineChart = new Chart(ctxl).Line(data);
>>>>>>> main

        var ctxb = $("#barChartDemo").get(0).getContext("2d");
        var barChart = new Chart(ctxb).Bar(data);

        //Thời Gian
        function time() {
            var today = new Date();
            var weekday = new Array(7);
            weekday[0] = "Chủ Nhật";
            weekday[1] = "Thứ Hai";
            weekday[2] = "Thứ Ba";
            weekday[3] = "Thứ Tư";
            weekday[4] = "Thứ Năm";
            weekday[5] = "Thứ Sáu";
            weekday[6] = "Thứ Bảy";
            var day = weekday[today.getDay()];
            var dd = today.getDate();
            var mm = today.getMonth() + 1;
            var yyyy = today.getFullYear();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            nowTime = h + " giờ " + m + " phút " + s + " giây";
            if (dd < 10) {
                dd = '0' + dd
            }
            if (mm < 10) {
                mm = '0' + mm
            }
            today = day + ', ' + dd + '/' + mm + '/' + yyyy;
            tmp = '<span class="date"> ' + today + ' - ' + nowTime +
                '</span>';
            document.getElementById("clock").innerHTML = tmp;
            clocktime = setTimeout("time()", "1000", "Javascript");

            function checkTime(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            }
        }
    </script>

    {{-- Kéo @yield('scripts') ra khỏi @section('scripts') --}}
    @yield('scripts') 

    {{-- Đoạn script cho discount_type nên nằm trong @section('scripts') của view con --}}
    {{-- hoặc nếu nó là script chung cho layout thì đặt trực tiếp ở đây nhưng không cần @section/@endsection --}}
    {{-- ĐOẠN NÀY LÀ ĐANG BỊ SAI VỊ TRÍ, ĐẶT @section TRONG LAYOUT LÀ SAI --}}
    {{-- Thay vì thế này:
    @section('scripts')
        <script>
            // ...
        </script>
    @endsection
    --}}
    {{-- Hãy xóa nó khỏi layout và đặt vào `add.blade.php` của bạn nếu chỉ cần cho trang đó, hoặc
         đặt nó thẳng vào layout nếu nó là script chung cho tất cả các trang dùng layout này. --}}

    {{-- Để đảm bảo nó được chạy, hãy đưa nó ra ngoài và bỏ @section/@endsection --}}
    <script>
        function toggleDiscountInput() {
            const selected = document.querySelector('input[name="discount_type"]:checked')?.value;
            document.getElementById('amount_input').style.display = selected === 'amount' ? 'block' : 'none';
            document.getElementById('percent_input').style.display = selected === 'percent' ? 'block' : 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleDiscountInput(); // Khi load trang
            document.querySelectorAll('input[name="discount_type"]').forEach(function (input) {
                input.addEventListener('change', toggleDiscountInput);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>