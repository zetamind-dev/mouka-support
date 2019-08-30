@extends('layouts.app') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('content')

<body class="bg-light">

  <main role="main" class="container col-lg-6">
    <div class="container">
      <div class="card">
        <div class="card-header" style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Add New User
        </div>
        <div class="card-body">
          <div class="container">
            @include('includes.flash')
            <form method="POST" action="{{ url('/admin/users') }}">
              @csrf

              <div class="form-group row">

                <div class="col-md-12">
                  <label for="name">Full Name</label>

                  <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                    name="name" value="{{ old('name') }}" required autofocus> @if ($errors->has('name'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('name') }}</strong>
                  </span>
                  @endif
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-4">
                  <label for="email">Employee No.</label>

                  <input id="employeeno" type="text"
                    class="form-control{{ $errors->has('employeeno') ? ' is-invalid' : '' }}" name="employeeno"
                    value="{{ old('employeeno') }}" required> @if ($errors->has('employeeno'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('employeeno') }}</strong>
                  </span>
                  @endif
                </div>

                <div class="col-md-8">
                  <label for="department">Department</label>

                  <select id="department" type="department" class="form-control" name="department" style="height: 55px;"
                    required>
                    <option value="">Select Department</option>
                    @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                  </select>

                  @if ($errors->has('department'))
                  <span class="help-block">
                    <strong>{{ $errors->first('department') }}</strong>
                  </span>
                  @endif

                </div>
              </div>
              <br>

              <div class="form-row">
                <div class="col-md-8">
                  <label for="email">E-Mail Address</label>

                  <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                    name="email" value="{{ old('email') }}" required> @if ($errors->has('email'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                  @endif
                </div>

                <div class="col-md-4">
                  <label for="telephone">Telephone</label>

                  <input id="telephone" type="number"
                    class="form-control{{ $errors->has('telephone') ? ' is-invalid' : '' }}" name="telephone"
                    value="{{ old('telephone') }}" required> @if ($errors->has('telephone'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('telephone') }}</strong>
                  </span>
                  @endif
                </div>

              </div>
              <br>

              <div class="form-row">

                <div class="col-md-6">
                  <label for="password">Password</label>
                  <input id="password" type="password"
                    class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                    placeholder="Must be 8-20 characters long" required> @if ($errors->has('password'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                  @endif
                </div>

                <div class="col-md-6">
                  <label for="password-confirm">Confirm Password</label>
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                    required>
                </div>
              </div>
              <br>
              @if(Auth::user()->user_type === 2)
              <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                <label for="location" class="col-md-4 control-label">Location</label>
                <div class="col-md-6">
                  <select name="location" class="form control">
                    <option value="Head Office">Head Office</option>
                    <option value="Ikeja">Ikeja</option>
                    <option value="Benin">Benin</option>
                    <option value="Kaduna">Kaduna</option>
                  </select>
                  @if ($errors->has('location'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('location') }}</strong>
                  </span>
                  @endif
                </div>

              </div>

              <div class="form-group{{ $errors->has('user_type') ? ' has-error' : '' }}">
                <label for="user_type" class="col-md-4 control-label">User Type</label>
                <div class="col-md-6">
                  <select name="user_type" class="form control">
                    <option value="0">User</option>
                    <option value="1">Moderator</option>
                    <option value="2">Admin</option>
                  </select>
                  @if ($errors->has('user_type'))
                  <span class="invalid-feedback">
                    <strong>{{ $errors->first('user_type') }}</strong>
                  </span>
                  @endif
                </div>
              </div>
              @endif
              <br>

              <div>
                <button type="submit" class="btn" style="background:#2737A6;color:white; font-weight:bold">
                  Add User
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
            <table class="table table-responsive">
              <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
                <tr>
                  <th>Name</th>
                  <th>User Type</th>
                  <th>Email</th>
                  <th>Registered On</th>
                  <th>Location</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                <tr>
                  <td>{{ $user->name }}</td>
                  @if ($user->user_type === 0)
                  <td>User</td>
                  @elseif ($user->user_type === 1)
                  <td>Moderator</td>
                  @else
                  <td>Admin</td>
                  @endif

                  <td>{{ $user->email }}</td>
                  <td>{{ $user->created_at->format('F d, Y H:i') }}</td>
                  <td>{{ $user->location }}</td>
                  <td>
                    <form action="{{ url('admin/users/' . $user->id) }}" method="POST">
                      @csrf
                      <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </main>
</body>
@endsection