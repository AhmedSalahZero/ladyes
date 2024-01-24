@props([
'id',
'label',
'type' ,
'name',
'isRequired' ,
'model'=>$model,
'placeholder'=>$placeholder ?? null,
'class'=>$class ?? '',
'hint'=>''
])

<div class="form-group row">

    <label class="col-md-3 label-control" for="{{ $id }}">

        {{ $label }}

        @if($isRequired)
        <span class="required">*</span>
        @endif
    </label>
    <div class="col-md-9">
        <input 
		@if($type =='time')
		onfocus="this.showPicker()"			
		@endif
		 name="{{ $name }}"  type="{{ $type }}" id="{{ $id }}" class="form-control border-primary {{ $class }}" placeholder="{{ $placeholder }}" value="{{ $model ?  $model->{$name} : old($name)  }}"  
		
		@if($isRequired)
		required
		@endif 
		>
		
		
        @error($name)
        <span class="text-danger">{{$message}}</span>
        @enderror
		@if($hint)
        <span class="text-gray">{{$hint}}</span>
		@endif 
    </div>
</div>
