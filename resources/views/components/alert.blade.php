<div id="alert-stack">
    @if (session('success'))
        <div class="custom-alert" role="alert">
            <span class="icon-warning"><i class="fas fa-check-circle"></i></span>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="custom-alert" role="alert">
            <span class="icon-warning"><i class="fas fa-exclamation-triangle"></i></span>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="custom-alert" role="alert">
                <span class="icon-warning"><i class="fas fa-exclamation-triangle"></i></span>
                {{ $error }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endforeach
    @endif
</div> 