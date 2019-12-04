@if (session('status'))
<div class="alert alert-success alert-dismissible fade show text-center">
    {{ session('status') }}
</div>
@endif
@if (session('invalid'))
<div class="alert alert-danger alert-dismissible fade show text-center">
    {{ session('invalid') }}
</div>
@endif
@if (session('update'))
<div class="alert alert-success alert-dismissible fade show text-center">
    {{ session('update') }}
</div>
@endif

@if (session('warning'))
<div class="alert alert-warning alert-dismissible fade show text-center">
    {{ session('warning') }}
</div>
@endif

@if (session('info'))
<div class="alert alert-info alert-dismissible fade show text-center">
    {{ session('info') }}
</div>
@endif
