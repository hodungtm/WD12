@section('body-class', 'login-page')
@extends('Client.Layouts.ClientLayout')
@section('main')
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<style>
main .reset-email-simple-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}
main .reset-email-simple-box {
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
main .reset-email-simple-box h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #222d32;
    text-align: center;
}
main .reset-email-simple-box p {
    color: #555;
    text-align: center;
    margin-bottom: 12px;
    font-size: 15px;
}
main .reset-email-simple-box .alert-success {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
    margin-bottom: 15px;
}
main .reset-email-simple-box .invalid-feedback {
    color: #ff4d4f;
    font-size: 13px;
    margin-top: 4px;
    text-align: left;
}
main .reset-email-simple-box button[type="submit"] {
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
main .reset-email-simple-box button[type="submit"]:hover {
    background: #179b8a;
}
@media (max-width: 600px) {
    main .reset-email-simple-box { padding: 18px 4px; max-width: 98vw; }
}
</style>
<div class="reset-email-simple-container">
    <div class="reset-email-simple-box">
        <h2>Quên mật khẩu 🔐</h2>
        <p>Vui lòng nhập địa chỉ email của bạn để nhận liên kết đặt lại mật khẩu.</p>
        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group" style="text-align:left;">
                <label for="email">Địa chỉ Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control @error('email') is-invalid @enderror" placeholder="Nhập email của bạn">
                @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit">Gửi liên kết đặt lại mật khẩu</button>
        </form>
    </div>
</div>
@endsection
