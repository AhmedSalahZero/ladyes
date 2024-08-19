@if(Session::has('fail'))
    <div class="col-md-6 error-msg-div" id="success_msg">
        <div class="alert bg-danger alert-dismissible mb-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Oh snap!</strong> 
			<span id="fail-msg-id">{!! session()->get('fail') !!}</span>
        </div>
    </div>
@endif
