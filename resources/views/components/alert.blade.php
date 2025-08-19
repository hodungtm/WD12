<div id="alert-stack">
   @if (session('success'))
        <div class="custom-alert" role="alert" data-type="success">
            <div class="alert-content">
                <div class="alert-header">
                    <span class="icon-warning"><i class="fas fa-check"></i></span>
                    <div class="alert-title">Success</div>
                </div>
                <div class="alert-message">{{ session('success') }}</div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="custom-alert" role="alert" data-type="error">
            <div class="alert-content">
                <div class="alert-header">
                    <span class="icon-warning"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="alert-title">Error</div>
                </div>
                <div class="alert-message">{{ session('error') }}</div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="custom-alert" role="alert" data-type="error">
                <div class="alert-content">
                    <div class="alert-header">
                        <span class="icon-warning"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="alert-title">Error</div>
                    </div>
                    <div class="alert-message">{{ $error }}</div>
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endforeach
    @endif
</div> 