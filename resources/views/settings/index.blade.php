@extends('layouts.app') @section('title', 'My Tickets') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('content')
<div class="container col-md-6">
    <div class="col-md-10">
        @include('includes.flash')
        <div class="card">
            <div class="card-header" style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Update
                Details</div>
            <div class="card-body">
                <form action="{{ url('/settings') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="new">Phone Number:</label>
                        <input type="number" class="form-control" id="new" name="telephone">
                    </div>

                    <div class="form-group">
                        <label for="new">Employee No.:</label>
                        <input type="text" class="form-control" id="new" name="employeeno">
                    </div>

                    <div class="form-group">
                        <label for="new">New Password</label>
                        <input type="password" class="form-control" id="new" name="new-password">
                    </div>

                    <button type="submit" class="btn btn-info">Update
                    </button>
                </form>
            </div>
        </div>
    </div>
    <br><br>
   <div class="container-fluid">
        <table class="table table-responsive table-striped">
            <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Employee Number</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
            <td>{{Auth::user()->name}}</td>
            <td>{{Auth::user()->email}}</td>
            <td>@if (Auth::user()->telephone === "" || Auth::user()->telephone === null)
               NILL
            @else
              {{Auth::user()->telephone}}
            @endif</td>
            <td>@if (Auth::user()->employeeno === "" || Auth::user()->employeeno === null)
                NILL
                @else
                {{Auth::user()->employeeno}}
                @endif</td>
            <td>{{Auth::user()->location}}</td>
            </tbody>
        </table>
   </div>
</div>
@endsection