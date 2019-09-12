@extends('layouts.app') @section('title', 'All Tickets') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/create.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('navigation') @endsection @section('content')
<div class="container card">
    <div class="col-lg-12 col-lg-offset-1 card-body">
        <div class="panel panel-default">

            <div class="card-body">
                @if ($tickets->isEmpty())
                <p>There are currently no dropped tickets.</p>
                @else
                <table class="table table-responsive">
                    <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
                        <tr>
                            <th>Ticket ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Date Opened</th>
                            <th>Ticket Owner</th>
                            <th style="text-align:center" colspan="2">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                        <tr>
                            <td>
                                {{ $ticket->ticket_id }}
                            </td>
                            <td>{{ $ticket->title }}</td>
                            <td>
                                @foreach ($categories as $category) @if ($category->id === $ticket->category_id)
                                {{ $category->name }} @endif @endforeach
                            </td>
                            <td>
                                {{$ticket->created_at->format('F d, Y H:i')}}
                            </td>
                            <td>
                                {{$ticket->ticket_owner}}
                            </td>
                            <td>
                                Ticket dropped by owner.
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @endif
            </div>
        </div>
    </div>
</div>
@endsection