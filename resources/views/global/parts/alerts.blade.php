@if (Session::has('success_msg'))
    <div class="alert alert-dismissable alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {!! Session::get('success_msg') !!}
    </div>
@endif

@if (Session::has('info_msg'))
    <div class="alert alert-dismissable alert-info">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {!! Session::get('info_msg') !!}
    </div>
@endif

@if (Session::has('warning_msg'))
    <div class="alert alert-dismissable alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {!! Session::get('warning_msg') !!}
    </div>
@endif

@if (Session::has('danger_msg'))
    <div class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {!! Session::get('danger_msg') !!}
    </div>
@endif
