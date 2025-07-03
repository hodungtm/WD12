<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
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

        .login-container {
            display: flex;
            width: 900px;
            max-width: 95%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            background-color: #fff;
        }

        .login-left {
            flex: 1.2;
            background-image: url('storage/banners/2QMhqva4eHfBBGdgbOToUr0mRvTsYBj0AyKocWUn.jpg');
            background-size: cover;
            background-position: center;
            min-height: 450px;
        }

        .login-right {
            flex: 0.8;
            padding: 65px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form {
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

        input[type="text"],
        input[type="email"],
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

        .signup-link {
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        }

        .signup-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
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
<div class="login-container">
    <div class="login-left"></div>
    <div class="login-right">
        <div class="login-form">
            <h1>Chào mừng bạn 🎉</h1>
            <p>Đăng ký tài khoản mới để bắt đầu hành trình của bạn cùng chúng tôi.</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Họ và tên</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="@error('name') is-invalid @enderror" placeholder="Nhập họ và tên">
                    @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Địa chỉ Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="@error('email') is-invalid @enderror" placeholder="Nhập email">
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input id="password" type="password" name="password" required
                           class="@error('password') is-invalid @enderror" placeholder="Tạo mật khẩu">
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">Nhập lại mật khẩu</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu">
                </div>

                <button type="submit">Đăng Ký</button>
            </form>

            <div class="signup-link">
                Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
            </div>
            <p class="copyright">© 2025 MỌI QUYỀN ĐƯỢC BẢO LƯU</p>
        </div>
    </div>
</div>
</body>
</html>
