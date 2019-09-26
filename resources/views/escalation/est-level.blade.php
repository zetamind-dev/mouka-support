@extends('layouts.app') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('content')

<body class="bg-light">

  <main role="main" class="container col-md-6">
    <div class="container">
      <div class="card">
        <div class="card-header" style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Create New Escalation Level
        </div>
        <div class="card-body">
          <div class="container">
            @include('includes.flash')
            <form method="POST" action="{{ url('admin/est-level') }}">
              {!! csrf_field() !!}
              <div class="form-group row">
                <div class="col-md-6">
                  <label for="name">Full Name</label>
                  <input id="name" type="text" class="form-control" name="name" placeholder="John Doe" required autofocus> 
                </div>

                <div class="col-md-6">
                  <label for="email">Email Address</label>
                  <input id="email" type="email" class="form-control" name="email" placeholder="johndoe@test.com" required>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-6">
                  <label for="level">Level</label>
                  <select id="level" type="level" class="form-control" name="level" style="height: 45px;"
                    required>
                    <option value="">select level</option>
                    <option value="1">first level</option>
                    <option value="2">second level</option>
                    <option value="3">third level</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="location">Location</label>
                  <select id="location" type="location " class="form-control" name="location" style="height: 45px;" required>
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
                <label for="format">Duration Format</label>
                <select id="format" type="format " class="form-control" name="format"
                style="height: 38px;" required>
                <option value="">Choose Duration Format</option>
                <option value="hours">In Hours</option>
                <option value="days">In Days</option>
                </select>
              </div>
              <div class="col-md-4">
                 <label for="duration">Enter Duration</label>
                 <input id="duration" type="duration" class="form-control" name="duartion" placeholder="e.g 2" required>
              </div>
            </div>
            <br>
            <br>
            <div>
              <button type="submit" class="btn btn-primary" style="color:white; font-weight:bold">
                Create
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
                  <th>Duration</th>
                  <th colspan="2" class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>User Name</td>
                  <td>Type</td>
                  <td>Email</td>
                  <td>Location</td>
                  <td>Duration</td>
                  <td>
                    <form action="{{ url('admin/users/') }}" method="POST">
                      @csrf
                      <button type="submit" class="btn btn-info btn-sm">Edit</button>
                    </form>
                  </td>
                  <td>
                    <form action="{{ url('admin/users/') }}" method="POST">
                      @csrf
                      <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
    </div>
  </main>
</body>
@endsection