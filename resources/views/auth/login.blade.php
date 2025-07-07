@extends('Client.Layouts.ClientLayout')
@section('main')
@section('body-class', 'login-page')
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<style>
/* --- FIX HEADER LỆCH TRÊN TRANG LOGIN --- */
/* (XÓA TOÀN BỘ ĐOẠN CSS GHI ĐÈ HEADER/LAYOUT ở đây) */
main .login-simple-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}
main .login-simple-box {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    padding: 36px 32px 32px 32px;
    max-width: 420px;
    width: 100%;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 18px;
}
main .login-simple-box h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #222d32;
    text-align: center;
}
main .login-simple-box .body-text {
    color: #555;
    text-align: center;
    margin-bottom: 18px;
}
main .login-simple-box .form-group {
    margin-bottom: 16px;
}
main .login-simple-box label {
    color: #222d32;
    font-weight: 500;
    margin-bottom: 6px;
}
main .login-simple-box input[type="email"],
main .login-simple-box input[type="password"] {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: #f8f9fa;
    color: #222d32;
    font-size: 15px;
}
main .login-simple-box input[type="email"]:focus,
main .login-simple-box input[type="password"]:focus {
    border-color: #20b2aa;
    outline: none;
}
main .login-simple-box .form-check {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 10px;
}
main .login-simple-box .form-check label {
    margin-bottom: 0;
    color: #555;
    font-weight: 400;
}
main .login-simple-box .forgot {
    color: #20b2aa;
    text-align: right;
    display: block;
    font-size: 13px;
    margin-bottom: 10px;
    text-decoration: none;
}
main .login-simple-box .forgot:hover {
    text-decoration: underline;
}
main .login-simple-box button[type="submit"] {
    width: 100%;
    background: #20b2aa;
    color: #fff;
    font-size: 16px;
    padding: 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    margin-top: 8px;
    transition: background 0.2s;
}
main .login-simple-box button[type="submit"]:hover {
    background: #179b8a;
}
main .login-simple-box .register-link {
    text-align: center;
    margin-top: 18px;
    font-size: 15px;
}
main .login-simple-box .register-link a {
    color: #20b2aa;
    font-weight: 600;
    text-decoration: none;
}
main .login-simple-box .register-link a:hover {
    text-decoration: underline;
}
@media (max-width: 600px) {
    main .login-simple-box { padding: 18px 4px; max-width: 98vw; }
}
</style>
<div class="login-simple-container">
    <div class="login-simple-box">
        <h2>Đăng nhập</h2>
        <div class="body-text">Vui lòng nhập email và mật khẩu để đăng nhập</div>
        @if (
$errors->any())
            <div class="alert alert-danger" style="font-size:15px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        @if (session('status'))
            <div class="alert alert-success" style="font-size:15px;">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" placeholder="Nhập email" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input id="password" type="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>
            <a href="{{ route('password.request') }}" class="forgot">Quên mật khẩu?</a>
            <div class="form-check">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ghi nhớ đăng nhập</label>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
        <div class="register-link">
            Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
        </div>
    </div>
</div>
@endsection
