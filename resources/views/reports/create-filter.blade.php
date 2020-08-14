@extends('layouts.app') @section('title', 'All Tickets') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/create.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('navigation') @endsection @section('content')
@include('includes.flash')

<div class="container">
    <div class="card-body">
        <h4>Filter Ticket:</h4>
    </div>
    <!-- Begining of New Ticket Form -->
    <form class="form-horizontal col-md-12" role="form" method="POST" action="{{ url('admin/reports/filter') }}"
        enctype="multipart/form-data">
        @csrf
        <div class="form-row mb-4">
            <div class="col-md-4">
                <label for="date_from">From</label>
                <input class="form-control" type="date" name="date_from" id="date_from" required>
            </div>
            <div class="col-md-4">
                <label for="date_to">To</label>
                <input class="form-control" type="date" name="date_to" id="date_to" required>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-4">
                <label for="user" class="control-label">Department</label>
                <select id="deptReport" type="select" class="form-control" name="deptReport" style="height: 40px;"
                    required>
                    <option value="">Select Department</option>
                    @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }} </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="category" class="col-md-4 control-label">Category</label>
                <select id="report" type="select" class="form-control" name="report" style="height: 40px;"
                    required>
                    <option value=""> </option>
                </select>
                </select>
            </div>
        </div>
        <br>
        <div class="form-row">

            <div class="col-md-4">
                <label for="status">Status</label>
                <select id="status" type="select" class="form-control" name="status" style="height: 40px;">
                    <option value="all">All Status</option>
                    <option value="Open">Open</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="Location">Location</label>
                <select id="location" type="select" class="form-control" name="location" style="height: 40px;">
                    <option value="all">All Locations</option>
                    <option value="Head Office">Head Office</option>
                    <option value="Lagos">Lagos</option>
                    <option value="Kaduna">Kaduna</option>
                    <option value="Benin">Benin</option>
                </select>
            </div>
        </div>

        <br>

        <div class="form-group">
            <div class="col-md-12 col-md-offset-4">
                <button type="submit" class="btn btn-warning">
                    <i class="fa fa-btn fa-filter"></i> filter
                </button>
            </div>
        </div>

    </form>
</div>

@isset($tickets)
@include('reports.query-param', compact('query_params'))
@include('reports.filter-table', compact('tickets', 'users', 'categories'))
@endisset)

@endsection