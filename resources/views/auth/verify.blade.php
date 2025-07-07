@section('body-class', 'login-page')
@extends('Client.Layouts.ClientLayout')
@section('main')
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<style>
main .verify-simple-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}
main .verify-simple-box {
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
main .verify-simple-box h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #222d32;
    text-align: center;
}
main .verify-simple-box p {
    color: #555;
    text-align: center;
    margin-bottom: 12px;
    font-size: 15px;
}
main .verify-simple-box .alert-success {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 5px;
    font-size: 14px;
    margin-bottom: 15px;
}
main .verify-simple-box button[type="submit"] {
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
main .verify-simple-box button[type="submit"]:hover {
    background: #179b8a;
}
@media (max-width: 600px) {
    main .verify-simple-box { padding: 18px 4px; max-width: 98vw; }
}
</style>
<div class="verify-simple-container">
    <div class="verify-simple-box">
        <h2>Xác minh email ✉️</h2>
        @if (session('resent'))
            <div class="alert-success">
                Liên kết xác minh mới đã được gửi đến email của bạn.
            </div>
        @endif
        <p>Trước khi tiếp tục, vui lòng kiểm tra hộp thư của bạn để xác minh địa chỉ email.</p>
        <p>Nếu bạn chưa nhận được email, bạn có thể yêu cầu gửi lại.</p>
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit">Gửi lại liên kết xác minh</button>
        </form>
    </div>
</div>
@endsection
