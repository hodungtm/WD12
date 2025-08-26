<div id="alert-stack">
    @if (session('success'))
        <div class="custom-alert" role="alert" data-type="success">
            <span class="alert-text">{{ session('success') }}</span>
            <span class="icon-warning"><i class="fas fa-check-circle"></i></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="custom-alert" role="alert" data-type="error">
            <span class="alert-text">{{ session('error') }}</span>
            <span class="icon-warning"><i class="fas fa-exclamation-triangle"></i></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="custom-alert" role="alert" data-type="error">
                <span class="alert-text">{{ $error }}</span>
                <span class="icon-warning"><i class="fas fa-exclamation-triangle"></i></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endforeach
    @endif
</div> 