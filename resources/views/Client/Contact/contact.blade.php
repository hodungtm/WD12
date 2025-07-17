@extends('Client.Layouts.ClientLayout')


@section('css')
<style>
    .container {
        max-width: 1360px;
    }

    .contact-info {
        background-color: #fff7e6;
        border-radius: 12px;
        padding: 30px 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        color: #333;
        margin-bottom: 30px;
    }

    .contact-info h3 {
        font-size: 22px;
        font-weight: 700;
        color: #1f2d3d;
        margin-bottom: 15px;
        text-transform: uppercase;
    }

    .contact-info p {
        font-size: 15px;
        margin-bottom: 10px;
        color: #444;
        line-height: 1.6;
    }

    .contact-info i {
        color: #007b8f;
        /* icon màu xanh đậm thể thao */
        margin-right: 8px;
    }

    .contact-info a.btn {
        margin-top: 10px;
        background-color: #033b3f;
        border: none;
        font-weight: 700;
        color: #fff;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    .contact-info a.btn:hover {
        background-color: #05545b;
    }

    .contact-form .form-row {
        display: flex;
        gap: 20px;
    }

    .contact-form .form-row>div {
        flex: 1;
    }
</style>
@endsection
@section('main')

<main class="main">
    <div class="category-banner banner p-0">
        <div class="row align-items-center no-gutters m-0 text-center text-lg-left">
            <div
                class="col-md-4 col-xl-2 offset-xl-2 d-flex justify-content-center justify-content-lg-start my-5 my-lg-0">
                <div class="d-flex flex-column justify-content-center">
                    <h3 class="text-left text-light text-uppercase m-0">Ưu đãi</h3>
                    <h2 class="text-uppercase m-b-1">Giảm 20%</h2>
                    <h3 class="font-weight-bold text-uppercase heading-border ml-0 m-b-3">Thể thao</h3>
                </div>
            </div>
            <div class="col-md-5 col-lg-4 text-md-center my-5 my-lg-0"
                style="background-image: url('{{ asset('assets/images/demoes/demo27/banners/shop-banner-bg.png') }}');">
                <img class="d-inline-block" src="{{ asset('assets/images/demoes/demo27/banners/shop-banner.png') }}" alt="banner"
                    width="400" height="242">
            </div>
            <div class="col-md-3 my-5 my-lg-0">
                <h4 class="font5 line-height-1 m-b-4">Khuyến mãi mùa hè</h4>
                <a href="#" class="btn btn-teritary btn-lg ml-0">Xem tất cả khuyến mãi</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="wishlist-title">
            <div class="row">
                {{-- Form liên hệ --}}
                <div class="col-lg-6 mb-4" style="overflow: hidden">
                    <div class="contact-info mb-4 p-4 bg-white rounded shadow-sm border">
                        <h3 class="text-uppercase fw-bold mb-3" style="color: #033b3f;">Cửa hàng Sudes Sport</h3>
                        <p class="mb-3" style="color: #333;">
                            Sudes Sport - Nhà bán lẻ & phân phối thương hiệu các mặt hàng về thể thao như giày chạy bộ,
                            đồ bơi, kính bơi, giày thể thao, đồ tập gym,... với chất lượng hàng đầu tại Việt Nam.
                        </p>

                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                            <strong>Địa chỉ:</strong> Số 70 Lữ Gia, Phường 15, Quận 11, TP. Hồ Chí Minh
                        </p>

                        <p class="mb-2">
                            <i class="fas fa-phone-alt me-2 text-primary"></i>
                            <strong>Hotline:</strong> 1900 6750
                        </p>

                        <p class="mb-3">
                            <i class="fas fa-envelope me-2 text-primary"></i>
                            <strong>Email:</strong> support@sapo.vn
                        </p>

                    </div>

                    <div class="contact-form bg-light p-4 rounded shadow-sm h-100">
                        <h2 class="mb-4 text-uppercase text-center" style="color: #333;">Liên hệ với chúng tôi</h2>


                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif


                        <form action="{{ route('client.contact.submit') }}" method="POST"
                            class="contact-form p-4 rounded border-1 bg-white">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name">Họ tên *</label>
                                    <input type="text" class="form-control" id="name" name="name" required
                                        placeholder="Nhập họ tên của bạn">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                        placeholder="Nhập email của bạn">
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone">Số điện thoại *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required
                                    placeholder="Nhập số điện thoại">
                            </div>

                            <div class="form-group mb-3">
                                <label for="message">Nội dung *</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required
                                    placeholder="Viết nội dung liên hệ..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-dark px-4 py-2">Gửi liên hệ</button>
                        </form>

                    </div>
                </div>

                {{-- Bản đồ --}}
                <div class="col-lg-6 mb-4">
                    <div class="map-container rounded overflow-hidden shadow-sm"
                        style="height: 500px; min-height: 400px;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.1346149038925!2d106.6296627748047!3d10.800680458761192!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752930a1571215%3A0x52b14aa82a5ec8bb!2zMTUwIE5ndXnhu4VuIFBow7osIFBoxrDhu51uZyA2LCBCw6xuaCBDaMOtbmgsIFRow6BuaCBwaOG7kSBI4bqhY2gsIFZpZXRuYW0!5e0!3m2!1svi!2s!4v1689012345678!5m2!1svi!2s"
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- End .container -->
</main><!-- End .main -->
@endsection

@section('js')

</script>
@endsection