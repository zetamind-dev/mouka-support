@extends('layouts.app') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('content')

<body class="bg-light">

  <main role="main" class="container col-md-10">
    <div id="page-wrapper">
      <div class="row justify-content-end">
        <div class="col-sm-2">
          <h1 class="page-header">
            @if (Auth::user()->user_type === 0)
            <a href="{{ url('/tickets') }}" class="btn btn-md" role="button"
              style="background:#443FFF;color:white;font-size:16px; font-weight:bold" aria-pressed="true">
              <span class="fa fa-ticket"></span> Open New Ticket
            </a>
            @endif
          </h1>
        </div>
      </div>
      <br>

      <div class="row">
        <div class="col-sm-4">

          <div class="card" style="">
            <div class="card-header text-right"
              style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Total Tickets
            </div>
            <div class="card-body">
              <div>
                <div style="float:left">
                  <i class="fa fa-list-ul" style="font-size:50px; color:#2737A6"></i>
                </div>
                <div class=" " style="float:right">
                  <div class="huge">
                    <h1 style="color:#2737A6">
                      <strong>{{ $totalTickets }}</strong>
                    </h1>
                  </div>
                </div>
              </div>
              <!--
                  <div style="clear:both ">
                      <a href="#" class="btn btn-outline-primary btn-sm">View details</a>
                  </div>
                  -->
            </div>
          </div>
        </div>

        <div class="col-sm-4">
          <div class="card" style="">
            <div class="card-header text-right"
              style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Opened Tickets
            </div>

            <div class="card-body ">
              <div>
                <div style="float:left ">
                  <i class="fa fa-envelope-open-o" style="font-size:50px; color:#2737A6"></i>
                </div>
                <div class=" " style="float:right">
                  <div class="huge">
                    <h1 style="color:#2737A6">
                      <strong>{{ $totalTicketsOpen }}</strong>
                    </h1>
                  </div>
                </div>
              </div>
              <!--
                  <div style="clear:both;">
                      <a href="# " class="btn btn-outline-primary btn-sm ">View details</a>
                  </div>
                  -->
            </div>
          </div>
        </div>

        <div class="col-sm-4">
          <div class="card " style="">
            <div class="card-header text-right"
              style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Closed Tickets
            </div>
            <div class="card-body ">
              <div>
                <div style="float:left ">
                  <i class="fa fa-close" style="font-size:50px; color:#2737A6"></i>
                </div>
                <div class=" " style="float:right ">
                  <div class="huge">
                    <h1 style="color:#2737A6">
                      <strong>{{ $totalTicketsClosed }}</strong>
                    </h1>
                  </div>
                </div>
              </div>
              <!--
                  <div style="clear:both ">
                      <a href="# " class="btn btn-outline-primary btn-sm">View details</a>
                  </div>
                  -->
            </div>
          </div>
        </div>
      </div>

      <br>
      <br> @if ($tickets->isEmpty())
      <p>No Tickets have been created.</p>
      @else
      <div class="card-body col-lg-12">
        <table class="table table-responsive-md table-hover">
          <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
            <tr>
              <th>Ticket ID</th>
              <th>Title</th>
              @if (Auth::user()->user_type < 1) <th>Ticket Moderator</th>
                @else
                <th>Ticket Owner</th>
                @endif
                <th>Category</th>
                <th>Status</th>
                <th>Date Opened</th>
                <th>Last Updated</th>
                <th> Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tickets as $ticket)
              
            <tr>
              <td>
                <a href="{{ url('tickets/'. $ticket->ticket_id) }}">
                  {{ $ticket->ticket_id }}
                </a>
              </td>
              <td>
                {{ $ticket->title }}
              </td>
               
               @if (Auth::user()->user_type < 1)
              <td>
              @foreach ($moderators as $moderator)
              @if ($moderator->user_type === 2)
                 {{ $moderator->email }}
              @endif
              @endforeach</td>
               @else
                <td> {{ $ticket->ticket_owner }}</td>
              @endif
               


                <td>
                  @foreach ($categories as $category) @if ($category->id === $ticket->category_id)
                  {{ $category->name }} @endif @endforeach
                </td>
                <td>
                  @if ($ticket->status === 'Open')
                  <span class="label label-success text-success">{{ $ticket->status }}</span>
                  @else
                  <span class="label label-danger text-danger">{{ $ticket->status }}</span>
                  @endif
                </td>
                <td>{{ $ticket->created_at->format('F d, Y H:i') }}</td>
                <td>{{ $ticket->updated_at->format('F d, Y H:i')}}</td>
                <td>
                  <form action="{{ url('tickets/'. $ticket->ticket_id) }}" method="GET">
                    <button type="submit" class="btn btn-sm"
                      style="background:#2737A6;color:white;font-weight:bold">More
                      Details</button>
                  </form>
                </td>
            </tr>
           
            @endforeach

          </tbody>
        </table>

      </div>

      @endif

    </div>
  </main>

  @endsection