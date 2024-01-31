@props([
'id',
'label',
'name',
'isRequired' ,
'model'=>$model,
'placeholder'=>$placeholder ?? null,
'class'=>$class ?? '',
'hint'=>'',
'rows'=>9,
'value'=>old($name)
])

<div class="form-group row">

    <label class="col-md-3 label-control" for="{{ $id }}">

        {{ $label }}

        @if($isRequired)
        <span class="required">*</span>
        @endif
    </label>
    <div class="col-md-9">
        <textarea 
		rows="{{ $rows }}"
		 name="{{ $name }}"   id="{{ $id }}" class="form-control border-primary {{ $class }}" placeholder="{{ $placeholder }}"   
		
		@if($isRequired)
		required
		@endif 
		>{{ $model ?  $model->{$name} : $value  }}</textarea>
		
		
        @error($name)
        <span class="text-danger">{{$message}}</span>
        @enderror
		@if($hint)
        <span class="text-gray">{{$hint}}</span>
		@endif 
    </div>
</div>
