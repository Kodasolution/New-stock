@if (session()->has('success'))
    <div class="alert alert-solid-success" role="alert">
        <h6 class="alert-heading mb-1">Success!</h6>
        <span>{{ session()->get('success') }}</span>
    </div>
@endif
@if (session()->has('error'))
    <div class="alert alert-solid-danger" role="alert">
        <h6 class="alert-heading mb-1">Error!</h6>
        <span>{{ session()->get('error') }}</span>
    </div>
@endif