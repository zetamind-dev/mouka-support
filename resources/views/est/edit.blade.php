@extends('layouts.app') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('content')

<body class="bg-light">

  <main role="main" class="container col-md-6">
    <div class="container">
      @include('includes.flash')
      <div class="card">
        <div class="card-header" style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Update
          Escalation Level
        </div>
        <div class="card-body">
          <div class="container">
            <form method="POST" action="{{ url('admin/escalation/'. $escalation->id . '/update') }}">
              {!! csrf_field() !!}
              <div class="form-group row">
                <div class="col-md-6">
                  <label for="name">Full Name</label>
                <input id="name" type="text" class="form-control" name="name" value="{{$escalation->name}}" placeholder="John Doe" required
                    autofocus>
                </div>

                <div class="col-md-6">
                  <label for="email">Email Address</label>
                <input id="email" type="email" class="form-control" name="email" value="{{$escalation->email}}" placeholder="johndoe@test.com"
                    required>
                </div>
              </div>

              <div class="form-row">
                <div class="col-md-6">
                  <label for="level">Level</label>
                  <select id="level" type="level" class="form-control" name="level" style="height: 45px;" required>
                    @if ($escalation->level === "first level")
                    <option value="{{$escalation->level}}">first level</option>
                    @elseif($escalation->level === "second level")
                    <option value="{{$escalation->level}}">second level</option>
                    @else
                    <option value="{{$escalation->level}}">third level</option>
                    @endif
                    <option value="1">first level</option>
                    <option value="2">second level</option>
                    <option value="3">third level</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="location">Location</label>
                  <select id="location" type="location " class="form-control" name="location" style="height: 45px;"
                    required>
                    @if ($escalation->location === "Head Office")
                    <option value="{{$escalation->location}}">Head Office</option>
                    @elseif($escalation->location === "Lagos")
                    <option value="{{$escalation->location}}">Lagos</option>
                    @elseif($escalation->location === "Benin")
                    <option value="{{$escalation->location}}">Benin</option>
                    @else
                    <option value="{{$escalation->location}}">Kaduna</option>
                    @endif
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
                    @if ($escalation->format === "day")
                    <option value="{{$escalation->format}}">In Days</option>
                    @else
                    <option value="{{$escalation->format}}">In Hours</option>
                    @endif
                    <option value="hour">In Hours</option>
                    <option value="day">In Days</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="duration">Enter Frequency</label>
                  <input id="duration" type="number" min="0" class="form-control" name="duration" value="{{$escalation->duration}}" placeholder="e.g 2"
                    required>
                </div>
              </div>
              <br>
              <br>
              <div>
                <button type="submit" class="btn btn-primary" style="color:white; font-weight:bold">
                  Update Level
                </button>
              </div>
            </form>

          </div>

        </div>
      </div>
    </div>
    <br>




   

        </div>
      </div>
  </main>
</body>
@endsection