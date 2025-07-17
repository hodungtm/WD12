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
            <div class="col-lg-12">
                <div class="blog-section row">
                    @foreach ($posts as $post)
                    @php
                    // Lấy ngày & tháng từ created_at
                    $day = \Carbon\Carbon::parse($post->created_at)->format('d');
                    $month = \Carbon\Carbon::parse($post->created_at)->format('M');
                    @endphp

                    <div class="col-md-6 col-lg-4">
                        <article class="post">
                            <div class="post-media">
                                <a href="{{ route('client.listblog.detail', $post->id) }}">
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                                        width="225" height="280" style="object-fit: cover; width: 100%; height: 280px;">
                                </a>
                                <div class="post-date">
                                    <span class="day">{{ $day }}</span>
                                    <span class="month">{{ $month }}</span>
                                </div>
                            </div><!-- End .post-media -->

                            <div class="post-body">
                                <h2 class="post-title">
                                    <a href="{{ route('client.listblog.detail', $post->id) }}">{{ $post->title }}</a>
                                </h2>
                                <div class="post-content">
                                    <p style="color: #000000; ">{{ Str::limit(strip_tags($post->content), 100, '...') }}
                                    </p>
                                </div><!-- End .post-content -->
                                <a href="{{ route('client.listblog.detail', $post->id) }}" class="post-comment">
                                    {{ $post->comments_count ?? 0 }} Comments
                                </a>
                            </div><!-- End .post-body -->
                        </article><!-- End .post -->
                    </div>
                    @endforeach
                    
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $posts->links('pagination::bootstrap-4') }}
                </div>

            </div><!-- End .col-lg-9 -->

            
        </div><!-- End .row -->
    </div><!-- End .container -->
</main><!-- End .main -->
@endsection

@section('js')

</script>
@endsection