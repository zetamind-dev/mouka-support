@extends('layouts.app') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('content')

<body class="bg-light">
  <main role="main" class="container">
    <div class="col-md-10" style="margin:auto">
      @include('includes.flash')
      <div class="card">
        <div class="card-header" style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Add New
          Category</div>
        <div class="container">
          <div class="card-body">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('admin/category') }}">
              {!! csrf_field() !!}

              <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="col-md-4 control-label">Category name</label>

                <div class="col-md-12">
                  <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
                  @if($errors->has('title'))
                  <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                  </span>
                  @endif
                </div>
              </div>

                       <div class="col-md-12">
                         <label for="user" class="control-label">Select department</label>
                         <select id="deptId" type="select" class="form-control" name="deptId" style="height: 40px;"
                           required>
                           <option value="">Select Department</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }} </option>
                            @endforeach
                         </select>
                       </div>
                       <br>

              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">Email</label>

                <div class="col-md-12">
                  <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}">
                  @if($errors->has('email'))
                  <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                  @endif
                </div>
              </div>



              <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                  <button type="submit" class="btn" style="background:#2737A6;color:white; font-weight:bold">
                    <i class="fa fa-btn fa-ticket"></i> Add Category
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="col-md-10" style="margin:auto">
      <div class="card">
        <div class="container">
          <div class="card-body">
            @if ($categories->isEmpty())
            <p>No Category has been created.</p>
            @else
            <div class="table-responsive-md">
              <table class="table table-inverse table-hover">
                <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
                  <tr>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Created on</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($categories as $category)
                  <tr>
                    <td>{{ $category->name }}</td>
                    <td>@foreach ($departments as $department)
                        @if($department->id === $category->dept_id)
                            {{ $department->name }}
                        @endif
                    @endforeach</td>
                  <td>{{$category->email}}</td>
                    <td>{{ $category->created_at->format('F d, Y H:i') }}</td>
                    <td>
                      <form action="{{ url('admin/category/delete/' . $category->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger" style="font-weight:bold">Delete</button>
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </main>

  @endsection