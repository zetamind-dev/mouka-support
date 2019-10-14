@if (session('status'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('status') }}
</div>
@endif
@if (session('invalid'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('invalid') }}
</div>
@endif
@if (session('update'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('update') }}
</div>
@endif

@if (session('warning'))
<div class="alert alert-warning alert-dismissible fade show">
    {{ session('warning') }}
</div>
@endif
