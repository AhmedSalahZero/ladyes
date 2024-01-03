@if(Session::has('errors'))
    <div class="col-md-6" id="success_msg">
        <div class="alert bg-danger alert-dismissible mb-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Oh snap!</strong> Strekkode må være en ikke-gjentatt verdi
        </div>
    </div>
@endif
