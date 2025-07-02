<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url('storage/banners/2QMhqva4eHfBBGdgbOToUr0mRvTsYBj0AyKocWUn.jpg'); /* Đường dẫn ảnh nền */
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

        .form-check {
            display: flex;
            align-items: center;
            font-size: 14px;
            margin-top: 10px;
        }

        .form-check input {
            margin-right: 5px;
        }

        .social-login {
            margin-top: 15px;
            text-align: center;
        }

        .social-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 5px;
            text-decoration: none;
            color: #333;
            width: 100%;
            max-width: 180px;
            font-size: 14px;
        }

        .social-button img {
            margin-right: 8px;
            height: 20px;
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

        a.forgot {
            font-size: 12px;
            display: block;
            text-align: right;
            margin-bottom: 10px;
        }
        .form-check label {
    display: flex;
    align-items: center;
    font-size: 14px;
}

    </style>
</head>
<body>
<div class="login-container">
    <div class="login-left"></div>
    <div class="login-right">
        <div class="login-form">
            <h1>Chào mừng trở lại 👋</h1>
            <p>Hôm nay là một ngày mới. Đó là ngày của bạn. Bạn định hình nó. Đăng nhập để bắt đầu quản lý các dự án của bạn.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" placeholder=" nhập email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" placeholder=" nhập mật khẩu" class="@error('password') is-invalid @enderror" name="password" required>
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <a href="{{ route('password.request') }}" class="forgot">Quên mật khẩu?</a>

                <div class="form-check">
                    <label for="remember">
                        <input type="checkbox" name="remember" id="remember" style="margin-right: 8px;" {{ old('remember') ? 'checked' : '' }}>
                        Ghi nhớ
                    </label>
                </div>

                <button type="submit">Đăng Nhập</button>
            </form>

            <div style="margin-top: 15px; text-align: center; color: #777; font-size: 14px;">Hoặc</div>

            <div class="social-login">
                <a href="#" class="social-button">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/2023_Facebook_icon.svg/900px-2023_Facebook_icon.svg.png" alt="Facebook"> Đăng Nhập Facebook
                </a>
                <a href="#" class="social-button">
                    <img src="https://img.icons8.com/color/512/google-logo.png" alt="Google"> Đăng Nhập Google
                </a>
            </div>

            <div class="signup-link">
                Không có tài khoản? <a href="{{ route('register') }}">Đăng ký </a>
            </div>
            <p class="copyright">© 2025 MỌI QUYỀN ĐƯỢC BẢO LƯU</p>
        </div>
    </div>
</div>
</body>
</html>
