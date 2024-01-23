@props([
'selectedValue'=>'',
'label'=>'',
'all'=>false ,
'options'=>[],
'addNew'=>false ,
'isRequired'=>false ,
'isSelect2'=>true,
'addNewText'=>'',
'disabled'=>false ,
'addWithPopup'=>false,
'addNewWithFormPopupClass'=>'',
'addNewFormPath'=>'',
'addModelName'=>'',
'addModalTitle'=>'',
'appendNewOptionToSelectSelector'=>'',
'multiple'=>$multiple ??false ,
'pleaseSelect'=>$pleaseSelect ?? false ,
'addNewModal'=>false,
'addNewModalModalName'=>'',
'addNewModalModalType'=>'',
'addNewModalModalTitle'=>'',
'previousSelectMustBeSelected'=>false ,
'previousSelectSelector'=>'' ,
'previousSelectTitle'=>'',
'previousSelectNameInDB'=>'',
])
<div class="form-group row">
@if($label)
<div class="col-md-3 " >
<label class=" label-control "> {{$label}}


    @if($isRequired)
   <span class="required">*</span>
    @endif
    @if($addNewModal && isset($company->id))
    <i
	
	@if($previousSelectMustBeSelected)
	data-previous-must-be-opened="1"
	data-previous-select-selector="{{ $previousSelectSelector }}"
	data-previous-select-title="{{ $previousSelectTitle }}"
	@endif 
	 title="{{ __('Add New') }}" data-company-id="{{ $company->id ?? 0 }}" data-modal-name="{{ $addNewModalModalName }}" data-modal-type="{{ $addNewModalModalType }}" data-modal-title="{{ $addNewModalModalTitle }}" class="fa fa-plus cursor-pointer block ml-auto trigger-add-new-modal"></i>
    @endif
</label>
</div>
<div class="col-md-9">
@if($disabled)
@php
$isSelect2 = false ;
@endphp
@endif

@php
$basicClasses = $isSelect2 ? "form-control  select select2-select btn-white" :"form-control  select ";
@endphp

<select 
@if($isRequired)
required
@endif 
 data-dir="{{ app()->getLocale() }}" data-style="btn border-primary text-light" title="{{ __('Please Select') }}" @if($addNewModalModalName) data-modal-name="{{ $addNewModalModalName }}" data-modal-type="{{ $addNewModalModalType }}" @endif  @if($disabled) disabled @endif {{ $attributes->merge(['class'=>$basicClasses]) }} data-live-search="true" data-add-new="{{ $addNew ? 1 : 0 }}" data-all="{{ $all ? 1 :0 }}" @if($multiple) multiple @endif>

    @if($pleaseSelect)
    <option selected>{{ __('Please Select') }}</option>
    @endif
    @if($all)

    <option value="">{{ __('All') }}</option>
    @endif
    @foreach($options as $value=>$option)
    <option title="{{ $option['title']  }}" @foreach($option as $optionName=>$val)
        {{ $optionName .'='.$val }}
        {{-- {{ logger($val == $selectedValue) }} --}}
        @if($optionName == 'value' && $val == $selectedValue )
        selected
        @endif



        @endforeach


        >

        {{ $option['title'] }}</option>
    @endforeach
</select>

	  	@error($attributes->get('name'))
	        <span class="text-danger">{{$errors->first($attributes->get('name'))}}</span>
		@enderror
</div>
@endif

</div>



{{ $slot }}

@push('js')

@endpush
