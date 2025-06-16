<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
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

        .reset-container {
            display: flex;
            width: 900px;
            max-width: 95%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            background-color: #fff;
        }

        .reset-left {
            flex: 1.2;
            background-image: url('/storage/banners/2QMhqva4eHfBBGdgbOToUr0mRvTsYBj0AyKocWUn.jpg');
            background-size: cover;
            background-position: center;
            min-height: 450px;
        }

        .reset-right {
            flex: 0.8;
            padding: 65px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .reset-form {
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

        .copyright {
            margin-top: 20px;
            font-size: 10px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="reset-container">
    <div class="reset-left"></div>
    <div class="reset-right">
        <div class="reset-form">
            <h1>Đặt lại mật khẩu 🔐</h1>
            <p>Vui lòng nhập mật khẩu mới của bạn bên dưới để hoàn tất quá trình.</p>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required
                           class="@error('email') is-invalid @enderror" placeholder="Nhập địa chỉ email">
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu mới</label>
                    <input id="password" type="password" name="password" required
                           class="@error('password') is-invalid @enderror" placeholder="Tạo mật khẩu mới">
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">Nhập lại mật khẩu</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required
                           placeholder="Nhập lại mật khẩu">
                </div>

                <button type="submit">Đặt lại mật khẩu</button>
            </form>

            <p class="copyright">© 2025 MỌI QUYỀN ĐƯỢC BẢO LƯU</p>
        </div>
    </div>
</div>
</body>
</html>
