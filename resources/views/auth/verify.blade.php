<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác minh email</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('storage/banners/2QMhqva4eHfBBGdgbOToUr0mRvTsYBj0AyKocWUn.jpg');
            background-size: cover;
            background-position: center;
        }

        .verify-container {
            display: flex;
            width: 900px;
            max-width: 95%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            background-color: #fff;
        }

        .verify-left {
            flex: 1.2;
            background-image: url('storage/banners/2QMhqva4eHfBBGdgbOToUr0mRvTsYBj0AyKocWUn.jpg');
            background-size: cover;
            background-position: center;
            min-height: 450px;
        }

        .verify-right {
            flex: 0.8;
            padding: 65px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .verify-box {
            width: 100%;
            max-width: 350px;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
            margin-bottom: 15px;
        }

        button {
            background: none;
            border: none;
            color: #007bff;
            padding: 0;
            margin: 0;
            font-size: 14px;
            cursor: pointer;
            text-decoration: underline;
        }

        .copyright {
            margin-top: 20px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="verify-container">
    <div class="verify-left"></div>
    <div class="verify-right">
        <div class="verify-box">
            <h1>Xác minh email ✉️</h1>

            @if (session('resent'))
                <div class="alert-success">
                    Liên kết xác minh mới đã được gửi đến email của bạn.
                </div>
            @endif

            <p>Trước khi tiếp tục, vui lòng kiểm tra hộp thư của bạn để xác minh địa chỉ email.</p>
            <p>Nếu bạn chưa nhận được email, bạn có thể yêu cầu gửi lại.</p>

            <form method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit">Gửi lại liên kết xác minh</button>
            </form>

            <p class="copyright">© 2025 MỌI QUYỀN ĐƯỢC BẢO LƯU</p>
        </div>
    </div>
</div>
</body>
</html>
