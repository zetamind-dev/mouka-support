@extends('layouts.app') @section('title', 'My Tickets') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('content')
<div class="container col-md-6">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header" style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Update
                Details</div>
            <div class="card-body">
                @include('includes.flash')
                <form action="{{ url('/settings') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="current">Current Phone Number</label>
                        <input class="form-control" id="current" value="{{ $oldTelephone }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="new">New Phone Number:</label>
                        <input type="number" class="form-control" id="new" name="telephone" required>
                    </div>

                    <div class="form-group">
                        <label for="current">Current Employee No</label>
                        <input class="form-control" id="current" value="{{ $oldEmployeeno }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="new">Employee No.:</label>
                        <input type="text" class="form-control" id="new" name="employeeno" required>
                    </div>

                    <div class="form-group">
                        <label for="new">New Password</label>
                        <input type="password" class="form-control" id="new" name="new-password" required>
                    </div>

                    <div class="form-group">
                        <label for="new">Confirm New Password</label>
                        <input type="password" class="form-control" id="new" name="confirmed-password" required>
                    </div>


                    <button type="submit" class="btn" style="background:#2737A6;color:white;font-weight:bold">Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection