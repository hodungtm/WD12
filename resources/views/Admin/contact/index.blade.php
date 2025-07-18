@extends('admin.layouts.AdminLayout')

@section('main')
  <div class="main-content-inner" style="padding-top: 10px; margin-top: 0;">
    <div class="main-content-wrap" style="padding-top: 0; margin-top: 0;">
      @if (session('success'))
                <div class="alert"
                    style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">
                    <i class="icon-check-circle" style="margin-right: 6px;"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert"
                    style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 8px; margin-bottom: 15px; font-weight: 600;">
                    <i class="icon-alert-triangle" style="margin-right: 6px;"></i> {{ session('error') }}
                </div>
            @endif
      <div class="flex items-center flex-wrap justify-between gap20 mb-30">
        <h3>Danh sách liên hệ</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
          <li><a href="#"><div class="text-tiny">Bảng điều khiển</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><a href="#"><div class="text-tiny">Liên hệ</div></a></li>
          <li><i class="icon-chevron-right"></i></li>
          <li><div class="text-tiny">Danh sách liên hệ</div></li>
        </ul>
      </div>

      <div class="wg-box">
        <div class="wg-table table-contact-list mt-3" style="overflow-x: auto;">
          <ul class="table-title flex mb-14" style="gap:0;">
            <li style="width: 50px; text-align: center;"><div class="body-title">ID</div></li>
            <li style="width: 150px; text-align: center;"><div class="body-title">Họ tên</div></li>
            <li style="width: 200px; text-align: center;"><div class="body-title">Email</div></li>
            <li style="width: 130px; text-align: center;"><div class="body-title">SĐT</div></li>
            <li style="flex: 1;"><div class="body-title">Nội dung</div></li>
            <li style="width: 140px; text-align: center;"><div class="body-title">Ngày gửi</div></li>
            <li style="width: 100px; text-align: center;"><div class="body-title">Hành động</div></li>
          </ul>

          <ul class="flex flex-column">
            @foreach($contacts as $contact)
            <li class="wg-product item-row flex align-items-center mb-10" style="gap:0;">
              <div class="body-text" style="width: 50px; text-align: center;">#{{ $contact->id }}</div>
              <div class="body-text" style="width: 150px; text-align: center;">{{ $contact->name }}</div>
              <div class="body-text" style="width: 200px; text-align: center;">{{ $contact->email }}</div>
              <div class="body-text" style="width: 130px; text-align: center;">{{ $contact->phone }}</div>
              <div class="body-text" style="flex: 1;">{{ Str::limit($contact->message, 80) }}</div>
              <div class="body-text" style="width: 140px; text-align: center;">{{ $contact->created_at->format('d/m/Y') }}</div>
              <div class="list-icon-function" style="width: 100px; text-align: center;">
                <a href="{{ route('Admin.contacts.show', $contact->id) }}" class="item eye" title="Xem chi tiết"><i class="icon-eye"></i></a>
                <form action="{{ route('Admin.contacts.destroy', $contact->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?');" style="display:inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" title="Xoá" style="background: none; border: none;">
                    <i class="icon-trash-2" style="color: red; font-size: 20px;"></i>
                  </button>
                </form>
              </div>
            </li>
            @endforeach
          </ul>
        </div>

        <div class="divider mt-3"></div>
        <div class="flex items-center justify-between flex-wrap gap10">
          <div class="text-tiny">Tổng: {{ $contacts->total() }} liên hệ</div>
          {{ $contacts->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>

  <style>
    .body-title {
      font-weight: bold;
    }
    .item.eye, .item.trash {
      margin: 0 5px;
    }
  </style>
@endsection
