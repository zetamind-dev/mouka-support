<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ticket Status</title>
</head>

<body>

        <p>
            A support ticket with id {{ $ticket->ticket_id }} has been Opened by {{$user->name}} from @if ($ticket->location === 'Head Office')
                Head Office
            @else
                {{$ticket->location}} Plant for your attention
            @endif
        </p>
        <br> 
        <p>The details of the ticket are as shown below:</p>
        <br>
        <p><b>Title: </b> {{ $ticket->title }}</p>
        <br>
        <p><b>Category: </b> {{ $category->name }}</p>
        <br>
        <p><b>Priority: </b> {{ $ticket->priority }}</p>
        <br>
        <p><b>Status: </b> {{ $ticket->status }}</p>
        <br>
        <p><b>Complaint: </b> {{ $ticket->message }}</p>
</body>

</html>