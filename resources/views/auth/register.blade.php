<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
        }
        .left {
            flex: 1;
            background: url('{{ asset('images/avatar/2QMhqva4eHfBBGdgbOToUr0mRvTsYBj0AyKocWUn.jpg') }}') no-repeat center center/cover;
        }
        .right {
            flex: 1;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        .login-form {
            width: 100%;
            max-width: 400px;
        }
        .login-form h1 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 24px;
        }
        .login-form p {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .signup-link {
            text-align: center;
            font-size: 14px;
            margin-top: 15px;
        }
        .signup-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }
        .invalid-feedback {
            color: red;
            font-size: 12px;
            margin-top: 4px;
        }
        .is-invalid {
            border-color: red;
        }
    </style>
</head>
<body>
<div class="left"></div>
<div class="right">
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
    </div>
</div>
</body>
</html>
