 <div class="row @isset($class) {{ $class }} @endisset">

     <div class="col-md-6">
         <x-form.input :id="'name'" :label="__('Name')" :type="'text'" :name="'name'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Name')])"></x-form.input>
     </div>

     <div class="col-md-6">
         <x-form.input :id="'email'" :label="__('Email')" :type="'email'" :name="'email'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Email')])"></x-form.input>
     </div>

     <div class="col-md-6">
         <x-form.select :is-required="true" :is-select2="true" :options="$countriesFormattedForSelect" :add-new="false" :label="__('Country Name')" :all="false" name="country_id" id="country_id" :selected-value="isset($model) ? $model->country_id: old('country_id') "></x-form.select>
     </div>

     <div class="col-md-6">
         <x-form.input :id="'phone'" :label="__('Phone')" :type="'text'" :name="'phone'" :is-required="true" :model="$model??null" :placeholder="__('Please Enter :attribute',['attribute'=>__('Phone')])"></x-form.input>
     </div>

     @if(isset($class))
     <div class="col-md-6">
         <x-form.checkbox :id="'can_receive_travel_info'" :label="__('Can Receive Travel Info')" :is-required="true" :name="'can_receive_travel_info'" :is-checked="true"> </x-form.checkbox>
     </div>
     @endif
 </div>
