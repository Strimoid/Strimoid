@if (Session::has('success_msg'))
    <div class="alert alert-dismissable alert-success" role="alert">
        {!! Session::get('success_msg') !!}
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-hidden="true"></button>
    </div>
@endif

@if (Session::has('info_msg'))
    <div class="alert alert-dismissable alert-info" role="alert">
        {!! Session::get('info_msg') !!}
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-hidden="true"></button>
    </div>
@endif

@if (Session::has('warning_msg'))
    <div class="alert alert-dismissable alert-warning" role="alert">
        {!! Session::get('warning_msg') !!}
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-hidden="true"></button>
    </div>
@endif

@if (Session::has('danger_msg'))
    <div class="alert alert-dismissable alert-danger" role="alert">
        {!! Session::get('danger_msg') !!}
        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-hidden="true"></button>
    </div>
@endif
