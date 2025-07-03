<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận mật khẩu</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('/storage/banners/2QMhqva4eHfBBGdgbOToUr0mRvTsYBj0AyKocWUn.jpg');
            background-size: cover;
            background-position: center;
        }

        .confirm-container {
            display: flex;
            width: 900px;
            max-width: 95%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            background-color: #fff;
        }

        .confirm-left {
            flex: 1.2;
            background-image: url('/storage/banners/2QMhqva4eHfBBGdgbOToUr0mRvTsYBj0AyKocWUn.jpg');
            background-size: cover;
            background-position: center;
            min-height: 450px;
        }

        .confirm-right {
            flex: 0.8;
            padding: 65px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .confirm-form {
            width: 100%;
            max-width: 350px;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 24px;
            text-align: center;
        }

        p {
            color: #555;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .is-invalid {
            border-color: red;
        }

        .invalid-feedback {
            color: red;
            font-size: 12px;
            margin-top: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            margin-top: 10px;
        }

        .forgot-link {
            display: block;
            text-align: right;
            margin-top: 10px;
            font-size: 13px;
        }

        .forgot-link a {
            color: #007bff;
            text-decoration: none;
        }

        .copyright {
            margin-top: 20px;
            font-size: 10px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="confirm-container">
    <div class="confirm-left"></div>
    <div class="confirm-right">
        <div class="confirm-form">
            <h1>Xác nhận mật khẩu 🔒</h1>
            <p>Vì lý do bảo mật, vui lòng nhập mật khẩu trước khi tiếp tục.</p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input id="password" type="password" name="password"
                           class="@error('password') is-invalid @enderror"
                           required autocomplete="current-password"
                           placeholder="Nhập mật khẩu">
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit">Xác nhận</button>

                @if (Route::has('password.request'))
                    <div class="forgot-link">
                        <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                    </div>
                @endif
            </form>

            <p class="copyright">© 2025 MỌI QUYỀN ĐƯỢC BẢO LƯU</p>
        </div>
    </div>
</div>
</body>
</html>
