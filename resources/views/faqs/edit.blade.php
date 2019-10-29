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
  <form class="form-horizontal col-md-12" role="form" method="POST" action="{{ url('admin/faq/update/' . $faq->id) }}"
    enctype="multipart/form-data">
    @csrf
    <br>
    <div class="form-group">
      <label for="title" class="col-md-2 control-label">Title</label>

      <div class="col-md-12">
      <input id="title" type="text" class="form-control" style="line-height: 40px;" name="title" maxlength="200" value="{{$faq->title}}"
          required>
      </div>
    </div>


    <div class="form-row mb-4">
      <div class="col-md-6">
        <label for="category" class="col-md-4 control-label">Category</label>

        <select id="category_id" type="select" class="form-control" name="category_id" style="height: 40px;" required>
        <option value="">Select Category</option>
          @foreach ($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-4">
        <label for="category">Previous Category</label>
        <input type="text" class="form-control" id="category" value="{{ $faq->category_name }}" name="old-category"
          readonly></span>
      </div>
    </div>

    <div class="form-group">
      <label for="body" class="col-md-4 control-label">Body</label>
    <textarea rows="5" id="article-ckeditor" class="form-control" name="body" maxlength=250 required>{{$faq->body}}</textarea>
    </div>


    <div class="form-group">
      <div class="col-md-12 col-md-offset-4">
        <button type="submit" class="btn btn-info" style="font-weight:bold;color:white">
           Update
        </button>
      </div>
    </div>

  </form>
  <!-- End of New Ticket Form -->


</div>
<!-- End of Container -->
@endsection