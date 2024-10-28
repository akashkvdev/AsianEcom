@if (Session::has('error'))
{{-- <div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    <h4><i class="icon fa fa-ban"></i>Error!</h4> {{Session::get('error')}}
</div> --}}

<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-ban"></i> Error!</h5>{{Session::get('error')}}
  .
    </div>
@endif

@if (Session::has('success'))
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    <h4><i class="icon fa fa-check"></i> Success !</h4> {{Session::get('success')}}
</div>
@endif