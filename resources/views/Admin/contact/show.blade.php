@extends('admin.layouts.AdminLayout')

@section('main')
<div class="main-content-inner">
  <div class="main-content-wrap">

    <div class="flex items-center flex-wrap justify-between gap20 mb-30">
      <h3>Chi tiết liên hệ: {{ $contact->name }}</h3>
      <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
        <li><a href="#"><div class="text-tiny">Bảng điều khiển</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><a href="{{ route('admin.contacts.index') }}"><div class="text-tiny">Liên hệ</div></a></li>
        <li><i class="icon-chevron-right"></i></li>
        <li><div class="text-tiny">Chi tiết</div></li>
      </ul>
    </div>

    {{-- Box: Thông tin liên hệ --}}
    <div class="wg-box mb-30">
      <div class="title-box">
        <i class="icon-user"></i>
        <div class="body-text">Thông tin liên hệ</div>
      </div>
      <div class="flex flex-wrap gap20 mb-4">
        <div class="w-full md:w-1/2">
          <label class="body-title">Họ tên</label>
          <div class="form-control" readonly>{{ $contact->name }}</div>
        </div>
        <div class="w-full md:w-1/2">
          <label class="body-title">Email</label>
          <div class="form-control" readonly>{{ $contact->email }}</div>
        </div>
        <div class="w-full md:w-1/2">
          <label class="body-title">Số điện thoại</label>
          <div class="form-control" readonly>{{ $contact->phone }}</div>
        </div>
      </div>
    </div>
    {{-- Box: Nội dung tin nhắn --}}
    <div class="wg-box mb-30">
      <div class="title-box">
        <i class="icon-message-circle"></i>
        <div class="body-text">Nội dung tin nhắn</div>
      </div>
      <div class="flex flex-wrap gap20 mb-4">
        <div class="w-full">
          <label class="body-title">Nội dung</label>
          <div class="form-control" style="min-height: 120px;" readonly>{{ $contact->message }}</div>
        </div>
      </div>
    </div>
    {{-- Box: Thông tin hệ thống --}}
    <div class="wg-box mb-30">
      <div class="title-box">
        <i class="icon-info"></i>
        <div class="body-text">Thông tin hệ thống</div>
      </div>
      <div class="flex flex-wrap gap20 mb-4">
        <div class="w-full md:w-1/2">
          <label class="body-title">Ngày gửi</label>
          <div class="form-control" readonly>{{ $contact->created_at->format('d/m/Y H:i') }}</div>
        </div>
      </div>
    </div>

    <div class="flex gap10 mt-4">
      <a href="{{ route('admin.contacts.index') }}" class="tf-button style-1">Quay lại danh sách</a>
    </div>

  </div>
</div>
@endsection
