<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đăng nhập</title>
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    height: 100vh;
    display: flex;
}

.left {
    flex: 1;
    background: url('storage/banners/2QMhqva4eHfBBGdgbOToUr0mRvTsYBj0AyKocWUn.jpg') no-repeat center center/cover;
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

.form-check {
    margin: 10px 0;
    font-size: 14px;
}

.form-check input {
    margin-right: 5px;
}

.forgot {
    display: block;
    text-align: right;
    font-size: 12px;
    margin-bottom: 10px;
    color: #007bff;
    text-decoration: none;
}

.or {
    text-align: center;
    margin: 15px 0;
    color: #777;
    font-size: 14px;
}

.social-button {
    display: block;
    width: 100%;
    text-align: center;
    padding: 10px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
    border-radius: 5px;
    font-size: 14px;
    color: #333;
    text-decoration: none;
}

.social-button img {
    vertical-align: middle;
    height: 20px;
    margin-right: 8px;
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
</style>
</head>
<body>
<div class="left"></div>
<div class="right">
    <div class="login-form">
        <h1>Chào mừng trở lại 👋</h1>
        <p>Hôm nay là một ngày mới. Đó là ngày của bạn. Bạn định hình nó. Đăng nhập để bắt đầu quản lý các dự án của bạn.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" placeholder="Nhập email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input id="password" type="password" placeholder="Nhập mật khẩu" name="password" required>
            </div>

            <a href="{{ route('password.request') }}" class="forgot">Quên mật khẩu?</a>

            <div class="form-check">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ghi nhớ</label>
            </div>

            <button type="submit">Đăng Nhập</button>
        </form>

        <div class="or">Hoặc</div>

        <a href="#" class="social-button">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/2023_Facebook_icon.svg/900px-2023_Facebook_icon.svg.png" alt="Facebook">
            Đăng Nhập Facebook
        </a>

        <a href="#" class="social-button">
            <img src="https://img.icons8.com/color/512/google-logo.png" alt="Google">
            Đăng Nhập Google
        </a>

        <div class="signup-link">
            Không có tài khoản? <a href="{{ route('register') }}">Đăng ký</a>
        </div>
    </div>
</div>
</body>
</html>
