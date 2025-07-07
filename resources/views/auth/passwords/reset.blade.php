@section('body-class', 'login-page')
@extends('Client.Layouts.ClientLayout')
@section('main')
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<style>
main .reset-simple-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}
main .reset-simple-box {
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
    text-align: center;
}
main .reset-simple-box h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #222d32;
    text-align: center;
}
main .reset-simple-box p {
    color: #555;
    text-align: center;
    margin-bottom: 12px;
    font-size: 15px;
}
main .reset-simple-box .invalid-feedback {
    color: #ff4d4f;
    font-size: 13px;
    margin-top: 4px;
    text-align: left;
}
main .reset-simple-box button[type="submit"] {
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
main .reset-simple-box button[type="submit"]:hover {
    background: #179b8a;
}
@media (max-width: 600px) {
    main .reset-simple-box { padding: 18px 4px; max-width: 98vw; }
}
</style>
<div class="reset-simple-container">
    <div class="reset-simple-box">
        <h2>Đặt lại mật khẩu 🔐</h2>
        <p>Vui lòng nhập mật khẩu mới của bạn bên dưới để hoàn tất quá trình.</p>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="form-group" style="text-align:left;">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required class="form-control @error('email') is-invalid @enderror" placeholder="Nhập địa chỉ email">
                @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group" style="text-align:left;">
                <label for="password">Mật khẩu mới</label>
                <input id="password" type="password" name="password" required class="form-control @error('password') is-invalid @enderror" placeholder="Tạo mật khẩu mới">
                @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group" style="text-align:left;">
                <label for="password-confirm">Nhập lại mật khẩu</label>
                <input id="password-confirm" type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu">
            </div>
            <button type="submit">Đặt lại mật khẩu</button>
        </form>
    </div>
</div>
@endsection
