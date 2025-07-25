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
    <div class="page-header mb-4"
        style="background-image: url('http://127.0.0.1:8000/storage/posts/auirElmcdHdViRMdXtQi6FtLGwY1ezwxqv99ju5K.png'); height: 280px; display: flex; align-items:center; background-size: 100%">
        <div class="container d-flex flex-column align-items-left">
            <h1
                style="font-size: 45px; font-weight: 700; color: rgb(255, 255, 255);text-shadow: 2px 2px 5px rgba(12, 12, 12, 0.8); text-align: left">
                Tin tức
            </h1>
            <nav aria-label="breadcrumb" class="breadcrumb-nav" style="background-color: #ffffff00; border-top: 0px">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"
                                style="font-size: 20px; font-weight: 700;color: #000000;text-shadow: 2px 2px 5px rgba(141, 141, 141, 0.9);">Trang
                                chủ</a>
                        </li>
                        <li style="font-size: 20px; font-weight: 700;color: #ffffff;text-shadow: 2px 2px 5px rgba(141, 141, 141, 0.6);"
                            class="breadcrumb-item active" aria-current="page">Tin tức</li>
                    </ol>
                </div>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="wishlist-title">

        </div>
        <div class="row">
            <div class="col-lg-9">
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
                                    <a href="{{ route('client.listblog.detail', $post->id) }}"
                                        style="position: relative; left: -33px">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="Post" class="rounded"
                                            style="width: 90px;" style="object-fit: cover;">
                                    </a>
                                </div>
                                <div class="post-info">
                                    <a href="{{ route('client.listblog.detail', $post->id) }}"
                                        class="fw-bold d-block text-dark">{{ $post->title }}</a>
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