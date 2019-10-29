@extends('layouts.app') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('content')

<body class="bg-light">

  <main role="main" class="container col-md-6">
    <div class="container">
      @include('includes.flash')
      <div class="card">
        <div class="card-header" style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Create New
          Escalation Level
        </div>
        <div class="card-body">
          <div class="container">
            <form method="POST" action="{{ url('admin/escalation') }}">
              {!! csrf_field() !!}
              <div class="form-group row">
                <div class="col-md-6">
                  <label for="name">Full Name</label>
                  <input id="name" type="text" class="form-control" name="name" placeholder="John Doe" required
                    autofocus>
                </div>

                <div class="col-md-6">
                  <label for="email">Email Address</label>
                  <input id="email" type="email" class="form-control" name="email" placeholder="johndoe@test.com"
                    required>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-6">
                  <label for="level">Level</label>
                  <select id="level" type="level" class="form-control" name="level" style="height: 45px;" required>
                    <option value="">select level</option>
                    <option value="1">first level</option>
                    <option value="2">second level</option>
                    <option value="3">third level</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="location">Location</label>
                  <select id="location" type="location " class="form-control" name="location" style="height: 45px;"
                    required>
                    <option value="">select location</option>
                    <option value="Head Office">Head Office</option>
                    <option value="Lagos">Lagos</option>
                    <option value="Benin">Benin</option>
                    <option value="Kaduna">Kaduna</option>
                  </select>
                </div>
              </div>
              <br>

              <div class="form-row">
                <div class="col-md-4">
                  <label for="format">Frequency Format</label>
                  <select id="format" type="format " class="form-control" name="format" style="height: 38px;" required>
                    <option value="">Choose Format</option>
                    <option value="12">12 hours</option>
                    <option value="24">24 hours</option>
                    <option value="48">48 hours</option>
                  </select>
                </div>
              </div>
              <br>
              <br>
              <div>
                <button type="submit" class="btn btn-primary" style="color:white; font-weight:bold">
                  Submit
                </button>
              </div>
            </form>

          </div>

        </div>
      </div>
    </div>
    <br>




    <div class="container">

      <div class="card">
        <div class="card-body">
          <div class="container">
            <table class="table table-responsive table-striped">
              <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Location</th>
                  <th>Level</th>
                  <th>Frequency</th>
                  <th colspan="2" class="text-center">Action</th>
                </tr>
              </thead>

              @foreach ($escalations as $escalation)
              <tbody>
                <tr>
                  <td>{{$escalation->id}}</td>
                  <td>{{$escalation->name}}</td>
                  <td>{{$escalation->email}}</td>
                  <td>{{$escalation->location}}</td>
                   <td>{{$escalation->level}}</td>
                  <td>{{$escalation->format}}</td>
                <td>
                  <form action="{{ url('admin/escalation/'. $escalation->id) }}" method="GET">
                    <button type="submit" class="btn btn-info btn-sm" style="color:white;font-weight:bold">Edit</button>
                  </form>
                </td>
                <td>
                  <form action="{{ url('admin/escalation/delete/' . $escalation->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" style="font-weight:bold">Delete</button>
                  </form>
                </td>
                </tr>
              </tbody>
              @endforeach
            </table>
          </div>

        </div>
      </div>
  </main>
</body>
@endsection