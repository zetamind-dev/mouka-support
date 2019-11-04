@extends('layouts.app') @section('title', 'Open Ticket') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet">

<!-- Including Dashboard Layour -->
@endsection @include('layouts.user-dashboard-nav') @section('navigation') @endsection @section('content')
<!-- Container -->
<div class="container col-md-5 ">
  <!-- Boostrap Grid -->
  <!-- Heading -->


  @include('includes.flash')

  <!-- Begining of New Ticket Form -->
  <form class="form-horizontal col-md-12" role="form" method="POST" action="{{ url('admin/edit-user') }}"
    enctype="multipart/form-data">
    @csrf
    <div class="form-row">
      <div class="col-md-8">
        <label for="user" class="control-label">@if (Auth::user()->location === "Head Office")
            Lagos Plant & Head Office
        @else
            {{Auth::user()->location}}
        @endif</label>
        <select id="userId" type="select" class="form-control" name="userId" style="height: 40px;" required>
          <option value="">Select User</option>
          @foreach ($users as $user)
          <option value="{{ $user->id }}">{{ $user->name }} &nbsp&nbsp <small>&nbsp&nbsp &nbsp&nbsp
              &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp
              &nbsp&nbsp{{ $user->location }}</small>
          </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label for="location" class="control-label">Transfer to</label>
        <select id="location" type="select" class="form-control" name="location" style="height: 40px;" required>
          <option value="">Select Destination Plant</option>
          <option value="Head Office">Head Office</option>
          <option value="Lagos">Lagos</option>
          <option value="Benin">Benin</option>
          <option value="Kaduna">Kaduna</option>
        </select>
      </div>
    </div>
    <br>

    <div class="form-group">
      <div class="col-md-12 col-md-offset-4">
        <button type="submit" class="btn btn-primary" style="font-weight:bold;background:#2737A6;color:white">
          Transfer
        </button>
      </div>
    </div>

  </form>
  <!-- End of New Ticket Form -->


</div>
<!-- End of Container -->
@endsection