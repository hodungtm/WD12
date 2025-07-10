@section('body-class', 'login-page')
@extends('Client.Layouts.ClientLayout')
@section('main')
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<style>
main .register-simple-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}
main .register-simple-box {
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
main .register-simple-box h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #222d32;
    text-align: center;
}
main .register-simple-box .body-text {
    color: #555;
    text-align: center;
    margin-bottom: 18px;
}
main .register-simple-box .form-group {
    margin-bottom: 16px;
}
main .register-simple-box label {
    color: #222d32;
    font-weight: 500;
    margin-bottom: 6px;
}
main .register-simple-box input[type="text"],
main .register-simple-box input[type="email"],
main .register-simple-box input[type="password"] {
    width: 100%;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background: #f8f9fa;
    color: #222d32;
    font-size: 15px;
}
main .register-simple-box input:focus {
    border-color: #20b2aa;
    outline: none;
}
main .register-simple-box button[type="submit"] {
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
main .register-simple-box button[type="submit"]:hover {
    background: #179b8a;
}
main .register-simple-box .login-link {
    text-align: center;
    margin-top: 18px;
    font-size: 15px;
}
main .register-simple-box .login-link a {
    color: #20b2aa;
    font-weight: 600;
    text-decoration: none;
}
main .register-simple-box .login-link a:hover {
    text-decoration: underline;
}
@media (max-width: 600px) {
    main .register-simple-box { padding: 18px 4px; max-width: 98vw; }
}
</style>
<div class="register-simple-container">
    <div class="register-simple-box">
        <h2>Đăng ký tài khoản</h2>
        <div class="body-text">Tạo tài khoản mới để bắt đầu hành trình cùng chúng tôi</div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label for="name">Họ và tên</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Nhập họ và tên" class="@error('name') is-invalid @enderror">
                @error('name')
                <span class="invalid-feedback" style="color: #ff4d4f; font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Nhập email" class="@error('email') is-invalid @enderror">
                @error('email')
                <span class="invalid-feedback" style="color: #ff4d4f; font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input id="password" type="password" name="password" required placeholder="Tạo mật khẩu" class="@error('password') is-invalid @enderror">
                @error('password')
                <span class="invalid-feedback" style="color: #ff4d4f; font-size: 13px;">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password-confirm">Nhập lại mật khẩu</label>
                <input id="password-confirm" type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu">
            </div>
            <button type="submit">Đăng ký</button>
        </form>
        <div class="login-link">
            Đã có tài khoản?
            <button type="button" onclick="window.location.href='{{ route('login') }}'" style="background: none; border: none; color: #20b2aa; font-weight: 600; text-decoration: none; cursor: pointer; font-size: 15px; padding: 0; margin-left: 4px;">Đăng nhập</button>
        </div>
    </div>
</div>
@endsection
