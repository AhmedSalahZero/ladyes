@props([
	'pageTitle',
	'createRoute'
])
<div class="card-header" style="padding-bottom: 0px">
                                    <h4 class="card-title">{{$pageTitle}}</h4>
                                    <a class="heading-elements-toggle"><i
                                            class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            @if($user->can(getPermissionName('create')))
                                            <li>
                                                <a href="{{$createRoute}}" style="border-radius: 10px;padding: 10px"
                                                   class="btn-info block-page">
                                                    {{__('Create')}}
                                                </a>
                                            </li>
                                            @endif
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
