<table class="table table-responsive table-striped">
    <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
      <tr>
        <th>#</th>
        <th>User's Email</th>
        <th>Location</th>
        <th>Ticket ID</th>
        <th>Ticket Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Priority</th>
        <th>Date Opened</th>
      </tr>
    </thead>

    <tbody>
      @foreach ($tickets as $ticket)
      <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$ticket->ticket_owner}}</td>
        <td>{{$ticket->location}}</td>
        <td>{{$ticket->ticket_id}}</td>
        <td>{{$ticket->title}}</td>
        <td>@foreach ($categories as $category)
          @if ($category->id === $ticket->category_id)
          {{$category->name}}
          @endif
          @endforeach</td>
        <td>@if ($ticket->status === 'Open')
          <span class="text-success">{{$ticket->status}}</span>
          @else
          <span class="text-danger">{{$ticket->status}}</span>
          @endif</td>
        <td>{{$ticket->priority}}</td>
        <td>{{$ticket->created_at}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>