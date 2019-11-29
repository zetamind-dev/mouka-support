@extends('layouts.app') @section('title', 'All Tickets') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/create.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('navigation') @endsection @section('content')
@include('includes.flash')
<form class="form-horizontal col-md-12" role="form" method="POST" action="{{url('admin/reports/export')}}"
  enctype="multipart/form-data">
  @csrf
<div class="container card-body align-items-center">
  <div class="form-row">
    <div class="col-md-4">
      <a href="{{url('admin/reports')}}" class="btn btn-secondary">
        Go Back
      </a>
    </div>

 
<input type="hidden" id="status" name="status" value="{{$query_params['status']}}">
<input type="hidden" id="category" name="category" value="{{$query_params['category']}}">
<input type="hidden" id="location" name="location" value="{{$query_params['location']}}">
   <div class="col-md-4">
     <button type="submit" class="btn btn-success">
       <i class="fa fa-btn fa-book"></i> Export to excel
     </button>
   </div>
   </div>
  </form>
  <br>
  @isset($tickets)
  @include('reports.query-param', compact('query_params'))
  @include('reports.filter-table', compact('tickets', 'users', 'categories'))
  @endisset
</div>

@endsection