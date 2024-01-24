@props([
'id',
'label',
'name',
'isRequired'=>$required??true ,
'placeholder'=>$placeholder ?? null,
'class'=>$class ?? '',
'isChecked'
])

<div class="form-group row">

    <label class="col-md-3 label-control" for="{{ $id }}">

        {{ $label }}

        @if($isRequired)
        <span class="required">*</span>
        @endif
    </label>
    <div class="col-md-9">
        <x-form.checkbox-element :id="$id" :is-required="false" :name="$name" :is-checked="$isChecked"> </x-form.checkbox-element>
        @error($name)
        <span class="text-danger">{{$message}}</span>
        @enderror
    </div>
</div>
