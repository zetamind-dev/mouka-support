<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Escalated Tickets</title>
</head>

<body>

  <p>
   The Following Tickets has not been attended to  
   @if ($duration === 48)
    for the past two days 
   @elseif ($duration > 48 || $duration < 144)
    for more than Four days now
    @elseif ($duration === 144  || $duration > 144) 
    for more than a week now
   @endif
  </p>
  <br>
   <div class="card-body col-lg-12">
     <table class="table table-responsive-md table-hover">
       <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
         <tr>
           <th> <small>Ticket ID</small></th>
           <th><small>Ticket Title</small></th>
           <th><small>Ticket Owner</small></th>
           <th><small>Ticket Moderator</small></th>
           <th><small>Ticket Location</small></th>
           <th><small>Category</small></th>
           <th><small>Ticket Priority</small></th>
           <th><small>Ticket Duration</small></th>
         </tr>
       </thead>
       @foreach ($tickets_log as $ticket)
       <tbody>
         <tr>
            <td><small>{{ $ticket->ticket_id }}</small></td>
            <td><small>{{ $ticket->title }}</small></td>
            <td><small>{{ $ticket->ticket_owner }}</small></td>
            <td><small>{{ $ticket->copy_email2 }}</small></td>
            <td>{{ $ticket->location }}</td>
            @foreach ($categories as $category)
            <td>@if ($category->id === $ticket->category_id)
               <small> {{ $category->name }}</small>
            @endif
           </td>
            @endforeach
            <td><small>{{ $ticket->priority }}</small></td>
            <td><small>{{ $ticket->created_at->format('d') }} days ago</small></td>
         </tr>
       </tbody>
        @endforeach
     </table>

   </div>

</body>

</html>