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

    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
      <label for="title" class="col-md-2 control-label">Title</label>

      <div class="col-md-12">
        <input id="title" type="text" class="form-control" style="line-height: 40px;" name="title"
          value="{{ old('title') }}" maxlength="30"> @if ($errors->has('title'))
        <span class="help-block">
          <strong>{{ $errors->first('title') }}</strong>
        </span>
        @endif
      </div>
    </div>


    <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
      <label for="category" class="col-md-4 control-label">Category</label>

      <div class="col-md-12">
        <select id="category" type="category" class="form-control" name="category" style="height: 55px;">
          <option value="">Select Category</option>
          @foreach ($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>

        @if ($errors->has('category'))
        <span class="help-block">
          <strong>{{ $errors->first('category') }}</strong>
        </span>
        @endif
      </div>
    </div>

    <div class="form-group{{ $errors->has('copy_email2') ? ' has-error' : '' }}">
      <label for="copy_email2" class="col-md-2 control-label">CopyEmail</label>

      <div class="col-md-12">
        <input id="copy_email" type="text" class="form-control" style="line-height: 40px;" name="copy_email2"
          value="{{ old('copy_email2') }}" maxlength="50"> @if ($errors->has('copy_email2'))
        <span class="help-block">
          <strong>{{ $errors->first('copy_email2') }}</strong>
        </span>
        @endif
      </div>
    </div>




    <div class="form-group{{ $errors->has('priority') ? ' has-error' : '' }}">
      <label for="priority" class="col-md-4 control-label">Priority</label>

      <div class="col-md-12">
        <select id="priority" type="" class="form-control" name="priority" style="height: 55px;">
          <option value="">Select Priority</option>
          <option value="low">Low</option>
          <option value="medium">Medium</option>
          <option value="high">High</option>
        </select>

        @if ($errors->has('priority'))
        <span class="help-block">
          <strong>{{ $errors->first('priority') }}</strong>
        </span>
        @endif
      </div>
    </div>


    <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
      <label for="location" class="col-md-4 control-label">Ticket Location</label>

      <div class="col-sm-4">
        <select id="location" type="" class="form-control" name="location" style="height: 30px;">
          <option value="{{Auth::user()->location}}"> {{Auth::user()->location}} </option>
        </select>
      </div>
    </div>

    <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
      <label for="message" class="col-md-4 control-label">Message</label>

      <div class="col-md-12">
        <textarea rows="5" id="article-ckeditor" class="form-control" name="message"></textarea>
        @if($errors->has('message'))
        <span class="help-block">
          <strong>{{ $errors->first('message') }}</strong>
        </span>
        @endif
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-6">
        <input type="file" id="picture" name="picture">
      </div>
    </div>


    <div class="form-group">
      <div class="col-md-12 col-md-offset-4">
        <button type="submit" class="btn btn-primary" style="font-weight:bold;background:#2737A6;color:white">
          <i class="fa fa-btn fa-ticket"></i> Open Ticket
        </button>
      </div>
    </div>

  </form>
  <!-- End of New Ticket Form -->


</div>
<!-- End of Container -->
@endsection