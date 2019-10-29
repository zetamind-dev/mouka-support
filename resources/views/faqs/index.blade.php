@extends('layouts.app') @section('title', 'Open Ticket') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet">

<!-- Including Dashboard Layour -->
@endsection @include('layouts.user-dashboard-nav') @section('navigation') @endsection @section('content')
<!-- Container -->
<div class="container col-md-5 ">
  <!-- Boostrap Grid -->
  <!-- Heading -->
  @if ($faqs->isEmpty())
  <p>We're sorry, FAQs are not available yet check back later. Thank you.</p>
  @else
  @foreach ($faqs as $faq)
  <div id="accordion_{{$faq->id}}">
    <div class="card mb-1">
      <div class="card-header rounded text-white bg-primary" id="headingOne_{{$faq->id}}">
        <h5 class="mb-0">
          <button class="btn btn-link text-light" data-toggle="collapse" data-target="#collapseOne_{{$faq->id}}"
            aria-expanded="true" aria-controls="collapseOne_{{$faq->id}}"
            style="color:white; font-size:17px;font-weight:bold">
            {{$faq->title}}
          </button>
          <span class="text-right text-light"><small>written by <i>{{$faq->author_name}}</i> on
              {{ $faq->created_at->format('F d, Y, H:i') }}</small></span>
        </h5>
      </div>

      <div id="collapseOne_{{$faq->id}}" class="collapse  ml-4 mr-4" aria-labelledby="headingOne_{{$faq->id}}"
        data-parent="#accordion_{{$faq->id}}">
        <br>
        {{$faq->body}}
        <br>
        @if (Auth::user()->id === $faq->author_id)
        <br>
        <div>
          <form action="{{ url('admin/faq/edit/' . $faq->id) }}" method="GET">
            <button type="submit" class="btn btn-info btn-sm" style="color:white;font-weight:bold">Edit</button>
          </form>
        </div>
        @endif
      </div>
    </div>
  </div>
  @endforeach
  @endif
</div>
</div>
<!-- End of Container -->
@endsection