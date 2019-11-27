@extends('layouts.app') @section('title', 'All Tickets') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/create.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('navigation') @endsection @section('content')
@include('includes.flash')

<div class="container">
    <div class="card-body">
        <h4>Filter Ticket by :</h4>
    </div>
    <!-- Begining of New Ticket Form -->
    <form class="form-horizontal col-md-12" role="form" method="POST" action="{{ url('admin/reports/filter') }}"
        enctype="multipart/form-data">
        @csrf
        <div class="form-row">
            <div class="col-md-4">
                <label for="date-from">Date From</label>
                <input type="date" class="form-control" name="date-from">
            </div>

            <div class="col-md-4">
                <label for="date-to">Date To</label>
                <input type="date" class="form-control" name="date-to">
            </div>
        </div>
        <br>
        <div class="form-row">
            <div class="col-md-4">
                <label for="category">Category</label>
                <select id="category" type="select" class="form-control" name="category" style="height: 40px;">
                    <option value="">Select Category</option>
                    <option value="1">NAV/QLIKVIEW</small>
                    <option value="2">NETWORKS</small>
                    <option value="All">All</small>
                    </option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="priority">Priority</label>
                <select id="priority" type="select" class="form-control" name="priority" style="height: 40px;">
                    <option value="">Select Priority</option>
                    <option value="high">High</option>
                    <option value="medium">medium</option>
                    <option value="low">Low</option>
                    <option value="All">All</option>
                </select>
            </div>
        </div>
        <br>
        <div class="form-row">
            <div class="col-md-4">
                <label for="status">Status</label>
                <select id="status" type="select" class="form-control" name="status" style="height: 40px;">
                    <option value="">Select Status</option>
                    <option value="Open">Open</small>
                    <option value="Close">Close</small>
                    <option value="Both">All</small>
                    </option>
                </select>
            </div>

            <div class="col-md-4">
                <label for="Location">Location</label>
                <select id="location" type="select" class="form-control" name="location" style="height: 40px;">
                    <option value="">Select Location</option>
                    <option value="Head Office">Head Office</option>
                    <option value="Lagos">Lagos</option>
                    <option value="Kaduna">Kaduna</option>
                    <option value="Benin">Benin</option>
                    <option value="All">All Location</option>
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




@endsection