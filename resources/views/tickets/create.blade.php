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
  <form class="form-horizontal col-md-12" role="form" method="POST" action="{{ url('/tickets') }}"
    enctype="multipart/form-data">
    @csrf
    @if (Auth::user()->user_type > 0)
    <div class="form-row">

      <div class="col-md-6">
        <label for="user" class="control-label">Create a Ticket for</label>
        <select id="userId" type="select" class="form-control" name="userId" style="height: 40px;" required>
          <option value="">Select User</option>
          @foreach ($users as $user)
          <option value="{{ $user->id }}">{{ $user->name }} &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp
            &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp &nbsp&nbsp{{$user->location}} </option>
          @endforeach
        </select>
      </div>

      <div class="col-md-6">
        <label for="status" class="control-label">Select Ticket Status</label>
        <select id="status" type="select" class="form-control" name="status" style="height: 40px;" required>
          <option value="">Select Status</option>
          <option value="Open">Open</option>
          <option value="Closed">Closed</option>
        </select>
      </div>
    </div>
    @endif
    <br>
    <div class="form-group">
      <label for="title" class="col-md-2 control-label">Title</label>

      <div class="col-md-12">
        <input id="title" type="text" class="form-control" style="line-height: 40px;" name="title" maxlength="30"
          required>
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-12">
        <label for="user" class="control-label">Department</label>
        <select id="deptCategory" type="select" class="form-control" name="deptCategory" style="height: 40px;" required>
          <option value="">Select Department</option>
          @foreach ($departments as $department)
          <option value="{{ $department->id }}">{{ $department->name }} </option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="form-group">
      <label for="category" class="col-md-4 control-label">Category</label>
      <div class="col-md-12">
        <select id="category" type="category" class="form-control" name="category" style="height: 55px;" required>
          <option value=""> </option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <label for="copy_email2" class="col-md-4 control-label">Copy Email (optional)</label>
      <div class="col-md-4">
        <input id="copy_email2" type="email" class="form-control" style="line-height: 40px;" name="copy_email2"
          maxlength="30">
      </div>
    </div>


    <div class="form-group">
      <label for="priority" class="col-md-4 control-label">Priority</label>
      <div class="col-md-12">
        <select id="priority" type="select" class="form-control" name="priority" style="height: 55px;" required>
          <option value="">Select Priority</option>
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>
      </div>
    </div>

    <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
      <label for="message" class="col-md-4 control-label">Message</label>
      <textarea rows="5" id="" class="form-control" name="message" maxlength=250 required></textarea>
      @if($errors->has('message'))
      <span class="help-block">
        <strong class="text-danger">{{ $errors->first('message') }}</strong>
      </span>
      @endif
    </div>

    <div class="form-group">
      <div class="col-md-6">
        <input type="file" id="picture" name="picture">
      </div>
    </div>



    <div class="form-group">
      <div class="col-md-12 col-md-offset-4">
        <button type="submit" class="btn btn-primary" style="font-weight:bold;background:#2737A6;color:white">
          <i class="fa fa-btn fa-ticket"></i> Submit
        </button>
      </div>
    </div>

  </form>
  <!-- End of New Ticket Form -->


</div>
<!-- End of Container -->
@endsection