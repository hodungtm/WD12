@section('body-class', 'login-page')
@extends('Client.Layouts.ClientLayout')
@section('main')
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<style>
main .confirm-simple-container {
    min-height: 70vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
}
main .confirm-simple-box {
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
main .confirm-simple-box h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: #222d32;
    text-align: center;
}
main .confirm-simple-box p {
    color: #555;
    text-align: center;
    margin-bottom: 12px;
    font-size: 15px;
}
main .confirm-simple-box .invalid-feedback {
    color: #ff4d4f;
    font-size: 13px;
    margin-top: 4px;
    text-align: left;
}
main .confirm-simple-box button[type="submit"] {
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
main .confirm-simple-box button[type="submit"]:hover {
    background: #179b8a;
}
main .confirm-simple-box .forgot-link {
    display: block;
    text-align: right;
    margin-top: 10px;
    font-size: 13px;
}
main .confirm-simple-box .forgot-link a {
    color: #20b2aa;
    text-decoration: none;
}
main .confirm-simple-box .forgot-link a:hover {
    text-decoration: underline;
}
@media (max-width: 600px) {
    main .confirm-simple-box { padding: 18px 4px; max-width: 98vw; }
}
</style>
<div class="confirm-simple-container">
    <div class="confirm-simple-box">
        <h2>Xác nhận mật khẩu 🔒</h2>
        <p>Vì lý do bảo mật, vui lòng nhập mật khẩu trước khi tiếp tục.</p>
        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="form-group" style="text-align:left;">
                <label for="password">Mật khẩu</label>
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Nhập mật khẩu">
                @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit">Xác nhận</button>
            @if (Route::has('password.request'))
                <div class="forgot-link">
                    <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
