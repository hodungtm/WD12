@extends('Client.Layouts.ClientLayout')


@section('css')
<style>
    .container {
        max-width: 1360px;
    }

    .post-rank {
        background-color: #f9a825;
        color: white;
        font-weight: 700;
        width: 24px;
        height: 24px;
        text-align: center;
        line-height: 24px;
        border-radius: 50%;
        margin-right: 10px;
        font-size: 16px;
    }

    .simple-post-list li:last-child {
        padding-top: 0;
    }

    a:hover {
        text-decoration: none;
    }

    .text-dark {
        margin-left: 4px;
        font-weight: 700;
        font-size: 16px;
    }

    .text-dark:hover {
        color: #f9e9cf
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

        </div>
        <div class="row">
            <div class="col-lg-9">
                <article class="post-single">
                    {{-- Ảnh đại diện bài viết --}}
                    
                    {{-- Tiêu đề bài viết --}}
                    <h1 class="post-title" style="font-weight: bold; font-size: 28px;">
                        {{ $post->title }}
                    </h1>
                    {{-- Ngày đăng --}}
                    <div class="post-meta text-muted mb-2" style="font-size: 14px;">
                        <i class="icon-calendar"></i> {{ \Carbon\Carbon::parse($post->created_at)->format('d/m/Y') }}
                    </div>
                    @if ($post->image)
                    <div class="post-media">
                        <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid rounded"
                            alt="{{ $post->title }}" style="max-height: 350px; object-fit: cover; width: 100%;">
                    </div>
                    @endif


                    {{-- Nội dung bài viết --}}
                    <div class="post-content mb-5" style="line-height: 1.8; font-size: 16px;">
                        {!! $post->content !!}
                    </div>
                </article>
            </div><!-- End .col-lg-9 -->


            <div class="sidebar-overlay"></div>
            <aside class="sidebar mobile-sidebar col-lg-3">
                <div class="sidebar-wrapper" data-sticky-sidebar-options='{"offsetTop": 72}'>
                    <div class="widget widget-post"
                        style="background-color: #f9e9cf; padding: 20px; border-radius: 10px;">
                        <h4 class="widget-title" style="color: #b58d00; font-weight: 700;">TIN TỨC NỔI BẬT</h4>

                        <ul class="simple-post-list p-0 m-0">
                            @foreach ($recentPosts as $index => $post)
                            <li class="d-flex align-items-center" style="margin-bottom: 0;">
                                <div class="post-rank" style="position: relative; left: -10px; z-index: 1">{{ $index + 1
                                    }}</div>

                                <div class="post-media me-2" style="margin-right: 20px">
                                    <a href="" style="position: relative; left: -33px">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="Post" class="rounded"
                                            style="width: 90px;" style="object-fit: cover;">
                                    </a>
                                </div>
                                <div class="post-info">
                                    <a href="" class="fw-bold d-block text-dark">{{ $post->title }}</a>
                                    <div class="post-meta text-muted" style="font-size: 14px; font-weight: 700">{{
                                        \Carbon\Carbon::parse($post->created_at)->format('d/m/Y') }}</div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div><!-- End .sidebar-wrapper -->
            </aside><!-- End .col-lg-3 -->
        </div><!-- End .row -->
    </div><!-- End .container -->
</main><!-- End .main -->
@endsection

@section('js')

</script>
@endsection