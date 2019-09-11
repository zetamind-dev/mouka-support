@extends('layouts.app') @section('title', $ticket->title) @section('external-css')


<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('content')

<div class="col-md-8 col-lg-6" style="margin:auto">
  @include('includes.flash')
  <div class="card">



    <div class="card-header" style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Ticket ID :
      {{ $ticket->ticket_id }}</div>
    <div class="container">
      <form class="form-horizontal col-md-12" role="form" method="POST"
        action="{{ url('tickets/'. $ticket->id . '/update')}}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
          <div class="container">
            <div class="form-group">
              <label for="title">Title</label>
              <input type="text" class="form-control" id="title" value="{{ $ticket->title }}" name="title" required>
            </div>
            <div class="form-row mb-4">
              <div class="col-md-4">
                <label for="category">Previous Category</label>
                <input type="text" class="form-control" id="category" value="{{ $category->name }}" name="old-category"
                  readonly></span>
              </div>
              <div class="col-md-4">
                <label for="Priority">Previous Priority</label>
                <input type="text" class="form-control" id="proirity" value="{{ $ticket->priority }}"
                  name="old-priority" readonly>
              </div>
            </div>
            <div class="form-row mb-4">
              <div class="col-md-4">
                <select id="category" type="category" class="form-control" name="category" style="height: 35px;"
                  required>
                  <option value="">Change Category</option>
                  @foreach ($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <select id="category" type="priority" class="form-control" name="priority" style="height: 35px;"
                  required>
                  <option value="">Change Priority</option>
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="comment">Message</label>
              <textarea class="form-control" rows="6" id="article-ckeditor" name="message"
                required>{{ $ticket->message }}</textarea>
            </div>
            <div class="form-row">
              <div class="col-md-4">
                <label for="status">Status</label>
                <input type="text" class="form-control" id="status" value="{{ $ticket->status }}" name="status"
                  readonly>
              </div>
              <div class="col-md-4">
                <label for="updated_at">Last Updated </label>
                <input type="text" class="form-control" id="updated_at" name="updated_at"
                  value="{{ $ticket->updated_at->format('F d, Y H:i') }}" readonly>
              </div>
              <div class="col-md-4">
                <label for="created_at">Date Opened</label>
                <input type="text" class="form-control" id="opened"
                  value="{{ $ticket->created_at->format('F d, Y H:i') }}" readonly>
              </div>
            </div>
          </div>

          <div class="form-row pt-4 ">
            <div class="form-group">
              <div class="col-md-6">
                <input type="file" id="picture" name="picture">
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12 col-md-offset-4">
                <button type="submit" class="btn btn-info" style="font-weight:bold;color:white">
                  <i class="fa fa-btn fa-ticket"></i> Update Ticket
                </button>
              </div>
            </div>
          </div>
      </form>
    </div>
  </div>
</div>
</div>
@endsection