<div class="dropdown ml-2 transactions">

    <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Travels') }} </button>
    <div class="dropdown-menu">
        <a data-toggle="modal" data-target="#view-travels-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Travels') }} </a>
        <a data-toggle="modal" data-target="#view-travels-completed-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Completed Travels') }} </a>
        <a data-toggle="modal" data-target="#view-travels-on-the-way-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View On The Way Travels') }} </a>
        <a data-toggle="modal" data-target="#view-travels-cancelled-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View Cancelled Travels') }} </a>
    </div>
    <div class="modal fade" id="view-travels-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View Travels For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table datatable datatable-js">
                        <thead>
                            <tr>
                                @include('components.common.all-travels-th')
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->travels as $index=>$travel)
                            @include('components.common.all-travels-tr',['model'=>$travel,'travelType'=>'all'])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="view-travels-cancelled-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View Cancelled Travels For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table datatable datatable-js">
                        <thead>
                            <tr>
                                @include('components.common.cancelled-travels-th')
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->cancelledTravels as $index=>$travel)
                            @include('components.common.cancelled-travels-tr',['model'=>$travel,'travelType'=>'cancelled'])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="view-travels-on-the-way-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View On The Way Travels For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table datatable datatable-js">
                        <thead>
                            <tr>
                                @include('components.common.on-the-way-travels-th')
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->onTheWayTravels as $index=>$travel)
                            @include('components.common.on-the-way-travels-tr',['model'=>$travel,'travelType'=>'on-the-way'])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>

            </div>
        </div>
    </div>





    <div class="modal fade" id="view-travels-completed-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
            <div class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('View Completed Travels For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table datatable datatable-js">
                        <thead>
                            <tr>
                                @include('components.common.completed-travels-th')
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($model->completedTravels as $index=>$travel)
                            @include('components.common.completed-travels-tr',['model'=>$travel,'travelType'=>'completed'])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>

            </div>
        </div>
    </div>

</div>
