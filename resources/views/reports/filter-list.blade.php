@extends('layouts.app') @section('title', 'All Tickets') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/create.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('navigation') @endsection @section('content')
@include('includes.flash')

<div class="container card-body align-items-center">
  <div class="form-row">
    <div class="col-md-4">
      <a href="{{url('admin/reports')}}" class="btn btn-secondary">
        Go Back
      </a>
    </div>

    <div class="col-md-4">
      <button type="submit" class="btn btn-success">
        <i class="fa fa-btn fa-book"></i> Export to excel
      </button>
    </div>
  </div>
  <br>
  <table class="table table-responsive table-striped">
    <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Location</th>
        <th>Ticket ID</th>
        <th>Ticket Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Priority</th>
        <th>Moderator</th>
        <th>Date Opened</th>
        <th>Ticket Duration</th>
      </tr>
    </thead>


  </table>
</div>

@endsection