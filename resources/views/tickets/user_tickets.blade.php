@extends('layouts.app') @section('title', 'My Tickets') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet"> @endsection @include('layouts.user-dashboard-nav')
@section('content')
<div class="container">
    @include('includes.flash')
    <br>
    <br> @if ($tickets->isEmpty())
    <p>You have not created any tickets.</p>
    @else
    <table class="table table-responsive-md table-hover">
        <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
            <tr>
                <th>Ticket ID</th>
                <th> Title</th>
                <th> Category</th>
                <th> Status</th>
                <th> Last Updated</th>
                @foreach ($tickets as $ticket)
                @if ($ticket->status === 'Open')
                <th> Update</th>
                <th> Drop</th>
                @else
                <th> Action</th>
                @endif
                @endforeach
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
                <td>{{ $ticket->updated_at }}</td>
                @if ($ticket->status === 'Open')
                <td>
                    <form action="{{ url('tickets/edit/'. $ticket->ticket_id) }}" method="GET">
                        <button type="submit" class="btn btn-info btn-sm"
                            style="color:white;font-weight:bold">Edit</button>
                    </form>
                </td>
                <td>
                    <form action="{{ url('tickets/'. $ticket->id . '/delete') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm"
                            style="color:white;font-weight:bold">Delete</button>
                    </form>
                </td>
                @else
                <td>
                    <form action="{{ url('tickets/'. $ticket->ticket_id) }}" method="GET">
                        <button type="submit" class="btn btn-primary btn-sm"
                            style="background:#2737A6;color:white;font-weight:bold">Comment</button>
                    </form>

                </td>
                @endif
            </tr>
            @endforeach

        </tbody>
    </table>

    {{ $tickets->render() }} @endif

</div>
@endsection