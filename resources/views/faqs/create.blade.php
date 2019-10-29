@extends('layouts.app') @section('title', 'Open Ticket') @section('external-css')
<link href="{{ asset('css/userdashboard.css') }}" rel="stylesheet">

<!-- Including Dashboard Layour -->
@endsection @include('layouts.user-dashboard-nav') @section('navigation') @endsection @section('content')
<!-- Container -->
<div class="container col-md-5 ">
  <!-- Boostrap Grid -->
  <!-- Heading -->


  @include('includes.flash')

  <!-- Begining of New Ticket Form -->
  <div class="card">
    <div class="card-header" style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">Add FAQ to help users</div>
    <div class="container">
      <div class="card-body">
        <form class="form-horizontal col-md-12" role="form" method="POST" action="{{ url('/admin/faq') }}"
          enctype="multipart/form-data">
          @csrf
          <br>
          <div class="form-group">
            <label for="title" class="col-md-2 control-label">Title</label>

            <div class="col-md-12">
              <input id="title" type="text" class="form-control" style="line-height: 40px;" name="title" maxlength="200"
                required>
            </div>
          </div>


          <div class="form-group">
            <label for="category" class="col-md-4 control-label">Category</label>

            <div class="col-md-12">
              <select id="category_id" type="select" class="form-control" name="category_id" style="height: 55px;"
                required>
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            <label for="body" class="col-md-4 control-label">Body</label>
            <textarea rows="5" id="article-ckeditor" class="form-control" name="body" maxlength=250 required></textarea>
          </div>


          <div class="form-group">
            <div class="col-md-12 col-md-offset-4">
              <button type="submit" class="btn btn-primary" style="font-weight:bold;background:#2737A6;color:white">
                <i class="fa fa-btn fa-ticket"></i> Submit
              </button>
            </div>
          </div>

        </form>
        <!-- End of New Ticket Form -->


      </div>
    </div>
  </div>
</div>
<br>

 <div class="col-md-5" style="margin:auto">
   <div class="card">
     <div class="container">
       <div class="card-body">
         @if ($faqs->isEmpty())
         <p>You have not created any FAQ yet.</p>
         @else
         <div class="table-responsive-md">
           <table class="table table-inverse table-hover">
             <thead style="background:#2737A6;color:white; font-size:17px; font-weight:bold;">
               <tr>
                 <th>Title</th>
                 <th>Created on</th>
                 <th>Edit</th>
                 <th>Delete</th>
               </tr>
             </thead>
             <tbody>
               @foreach ($faqs as $faq)
               <tr>
                 <td>{{ $faq->title }}</td>
                 <td>{{ $faq->created_at->format('F d, Y H:i') }}</td>
                 <td>
                   <form action="{{ url('admin/faq/edit/' . $faq->id) }}" method="GET">
                     @csrf
                     <button type="submit" class="btn btn-info btn-sm" style="font-weight:bold">Edit</button>
                   </form>
                 </td>
                   <td>
                     <form action="{{ url('admin/faq/delete/' . $faq->id) }}" method="POST">
                       @csrf
                       <button type="submit" class="btn btn-danger btn-sm" style="font-weight:bold">Delete</button>
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
<!-- End of Container -->
@endsection