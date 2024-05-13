@props([
'title',
'value',
'icon',
'hasProgress'=>false,
'progressColor'=>'bg-gradient-x-danger',
'progressPercentage'=>0
])
<div class="col-xl-3 col-lg-6 col-12">
    <div class="card pull-up">
        <div class="card-content">
            <div class="card-body">
                <div class="media d-flex">
                    <div class="media-body text-left">
                        <h3 class="success">{{$value}}</h3>
                        <h1> {{ $title }} </h1>
                    </div>
                    <div>
                        <i class="la {{ $icon }} " style="font-size:4em "></i>
                        {{-- <i class="icon-heart danger font-large-2 float-right"></i> --}}
                    </div>
                </div>
				@if($hasProgress)
                <div class="progress progress-sm mt-1 mb-0 box-shadow-2">
                    <div class="progress-bar {{ $progressColor }}" role="progressbar" style="width: {{ $progressPercentage }}%" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
				@endif 
            </div>
        </div>
    </div>
</div>
