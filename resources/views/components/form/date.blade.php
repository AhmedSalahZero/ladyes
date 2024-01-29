@props([
'model',
'label',
'name',
'placeholder',
'isRequired' ,
"id"
])
<div class="form-group row">
    <div class="col-md-3">
        <label for="{{$id}}" class="label-control">
            {{ $label }}
            @if($isRequired)
            <span class="required">*</span>
            @endif

        </label>
    </div>
    <div class="col-md-9">
        <div class="kt-input-icon">
            <div class="input-group date">
                <input  id="{{$id}}" type="date" name="{{ $name }}" value="{{ $model && $model->{$name} ? explode(' ',$model->{$name})[0] : old($name) }}" class="form-control" placeholder="{{ $placeholder }}" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-calendar-check-o"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
