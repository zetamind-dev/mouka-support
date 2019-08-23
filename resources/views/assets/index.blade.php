@extends('layouts.app') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav') @section('content')

<body class="bg-light">

    <main role="main" class="container col-lg-6">

        <div class="container">

            <div class="card">
                <div class="card-header" style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Add New Assett</div>
                <div class="card-body">
                    <div class="container">
                        @include('includes.flash')

                        <form method="POST" action="{{ url('/assets') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="employeeno" value="{{ Auth::user()->employeeno }}">
                            <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                            <input type="hidden" name="email" value="{{ Auth::user()->email }}">


                            <div class="form-group{{ $errors->has('computer_type') ? ' has-error' : '' }}">
                                <label for="computer_type" class="col-md-4 control-label">Computer Type</label>

                                <div class="col-md-12">
                                    <select id="computer_type" type="" class="form-control" name="computer_type" style="height: 55px;">
                                        <option value="Laptop">Select Type</option>
                                        <option value="Laptop">Laptop</option>
                                        <option value="Desktop">Desktop</option>

                                    </select>

                                    @if ($errors->has('computer_type'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('computer_type') }}</strong>
                </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('laptop_name') ? ' has-error' : '' }}">
                                <label for="laptop_name" class="col-md-4 control-label">laptop Name</label>

                                <div class="col-md-12">
                                    <select id="laptop_name" type="" class="form-control" name="laptop_name" style="height: 55px;">
                                        <option value="">Select Laptop</option>
                                        <option value="HP">HP</option>
                                        <option value="Lenovo">Lenovo</option>
                                        <option value="Dell">Dell</option>
                                        <option value="Envy">Envy</option>
                                    </select>

                                    @if ($errors->has('laptop_name'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('laptop_name') }}</strong>
                </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('laptop_model') ? ' has-error' : '' }}">
                                <label for="laptop_model" class="col-md-2 control-label">Laptop Model</label>

                                <div class="col-md-12">
                                    <input id="laptop_model" type="text" class="form-control" style="line-height: 40px;" name="laptop_model" value="{{ old('laptop_model') }}" maxlength="30"> @if ($errors->has('laptop_model'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('laptop_model') }}</strong>
                </span>
                                    @endif
                                </div>
                            </div>



                            <div class="form-group{{ $errors->has('laptop_serial_no') ? ' has-error' : '' }}">
                                <label for="title" class="col-md-4 control-label">Laptop Serial No</label>

                                <div class="col-md-12">
                                    <input id="laptop_serial_no" type="text" class="form-control" style="line-height: 40px;" name="laptop_serial_no" value="{{ old('laptop_serial_no') }}" maxlength="30"> @if ($errors->has('laptop_serial_no'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('laptop_serial_no') }}</strong>
                </span>
                                    @endif
                                </div>
                            </div>




                            <div class="form-group{{ $errors->has('laptop_duration') ? ' has-error' : '' }}">
                                <label for="laptop_duration" class="col-md-4 control-label">Laptop Duration</label>

                                <div class="col-md-12">
                                    <input id="laptop_duration" type="text" class="form-control" style="line-height: 40px;" name="laptop_duration" value="{{ old('laptop_serial_no') }}" maxlength="30"> @if ($errors->has('laptop_serial_no'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('laptop_duration') }}</strong>
                </span>
                                    @endif
                                </div>
                            </div>




                            <div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
                                <label for="remark" class="col-md-4 control-label">Complain/Comment</label>

                                <div class="col-md-12">
                                    <textarea rows="5" id="remark" class="form-control" name="remark"></textarea> @if ($errors->has('remark'))
                                        <span class="help-block">
                    <strong>{{ $errors->first('remark') }}</strong>
                </span>
                                    @endif
                                </div>
                            </div>


                            <br>

                            <div>
                                <button type="submit" class="btn" style="background:#2737A6;color:white; font-weight:bold">
                                   Add Asset
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
                                    <th>Employee No</th>
                                    <th>Laptop Name</th>
                                    <th>Laptop Model</th>
                                    <th>Laptop Serial No</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assets as $asset)
                                <tr>
                                    <td>{{ $asset->employeeno }}</td>
                                    <td>{{ $asset->laptop_name }}</td>
                                    <td>{{ $asset->laptop_model }}</td>
                                    <td>{{ $asset->laptop_serial_no }}</td>
                                    <td>{{ $asset->created_at->format('F d, Y H:i') }}</td>
                                    <td>
                                        <form action="{{ url('assets/' . $asset->id) }}" method="POST">
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

    @endsection