@props([
	'items'
])
<div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
								@foreach($items as $itemArr)
								@if(!$loop->last)
                                <li class="breadcrumb-item"><a href="{{$itemArr['route']}}">{{$itemArr['title']}} </a>
                                </li>
                               @else 
                                <li class="breadcrumb-item active ">{{$itemArr['title']}}
                                </li>
								@endif 
								@endforeach 
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
