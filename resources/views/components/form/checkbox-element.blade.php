@props([
	'name',
	'isRequired',
	'isChecked',
	'id'=>$id ?? $name 
])
 <input
 {{ $attributes->merge(['class'=>'switch']) }} 
 
 @if($isRequired)
 required
 @endif 
   type="checkbox" id="{{ $id }}"  value="1"  name="{{ $name }}"   {{$isChecked  ? 'checked' : ''}} />
