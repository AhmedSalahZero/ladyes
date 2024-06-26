 <tr data-id="{{ $model->id }}" class="deleteable-row">
                                                <td class="text-center">{{$loop->iteration}}</td>
                                                <td class="text-center">{{$model->getFullName()}}</td>
                                                <td class="text-center">
                                                    <img class="default-img-in-td" src="{{ $model->getFirstMedia('image') ? $model->getFirstMedia('image')->getFullUrl() : getDefaultImage() }}">
                                                </td>
                                                <td class="text-center">{{$model->getPhone()}}</td>
                                                <td class="text-center">{{$model->getEmail()}}</td>
                                                <td class="text-center">{{$model->getAvgRateFormatted()}}</td>
                                                <td class="text-center">{{ number_format($model->getTotalWalletBalance()) }} {{ $model->getCountry() ? $model->getCountry()->getCurrencyFormatted($lang) : __('N/A') }}</td>

                                                @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
                                                <td class="">
                                                    @if($user->can(getPermissionName('delete')))
                                                    <a href="#" data-toggle="modal" data-target="#delete-modal-{{ $model->id }}" class="delete-modal-trigger-js btn btn-danger btn-sm">
                                                        <i class="la la-trash"></i></a>
                                                    <x-modals.delete :deleteRoute="$deleteRouteName" :model-id="$model->id"></x-modals.delete>
                                                    @endif



                                                    <div class="modal fade inner-modal" id="ban-account-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                            <div class="modal-content">
                                                                <form action="{{ route('client.toggle.is.banned') }}" method="post">
                                                                    @method('put')
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $model->id }}">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">
                                                                            @if($model->isBanned())
                                                                            {{ __('Do You Want To Unban :name Account',['name'=>$model->getFullName($lang)]) }}
                                                                            @else
                                                                            {{ __('Do You Want To Ban :name Account',['name'=>$model->getFullName($lang)]) }}
                                                                            @endif
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <textarea name="comment" class="form-control" rows="8" type="text" required placeholder="{{ __('Reason In Details') }}"></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss-modal="inner-modal">{{ __('Close') }}</button>
                                                                        @if($model->isBanned())
                                                                        <button type="submit" class="btn btn-success">{{ __('Unban') }}</button>
                                                                        @else
                                                                        <button type="submit" class="btn btn-danger">{{ __('Ban') }}</button>
                                                                        @endif
                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if($user->can(getPermissionName('update')))
                                                    <a href="{{route($editRouteName,$model->id)}}" class="block-page ml-2 btn btn-primary btn-sm"><i class="la la-pencil"></i></a>

                                                    <div class="dropdown-grid-css">

                                                        <div class="dropdown  send-message">

                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Send Message') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#send-whatsapp-message-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Whatsapp') }} </a>
                                                                <a data-toggle="modal" data-target="#send-sms-message-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Sms') }} </a>
                                                                <a data-toggle="modal" data-target="#send-email-message-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Email') }} </a>
                                                            </div>
                                                            <div class="modal fade" id="send-whatsapp-message-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.whatsapp.message') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">
                                                                            <input type="hidden" name="phone" value="{{ $model->getPhone() }}">
                                                                            <input type="hidden" name="country_code" value="{{ $model->getCountryIso2() }}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ __('Send Whatsapp Message To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <textarea name="message" class="form-control" rows="8" type="text" required placeholder="{{ __('Message Text') }}"></textarea>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal fade" id="send-sms-message-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.sms.message') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">
                                                                            <input type="hidden" name="phone" value="{{ $model->getPhone() }}">
                                                                            <input type="hidden" name="country_code" value="{{ $model->getCountryIso2() }}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ __('Send Sms Message To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <textarea name="message" class="form-control" rows="8" type="text" required placeholder="{{ __('Message Text') }}"></textarea>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="modal fade" id="send-email-message-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.email.message') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="email" value="{{ $model->getEmail() }}">
                                                                            <input type="hidden" name="name" value="{{ $model->getFullName() }}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ __('Send Email Message To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <input class="form-control" required type="text" name="subject" placeholder="{{ __('Message Subject') }}">
                                                                                </div>
                                                                                <div class="from-group">
                                                                                    <textarea name="message" class="form-control" rows="8" type="text" required placeholder="{{ __('Message Text') }}"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="dropdown ml-2 address">
                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Addresses') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#view-addresses-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View :page',['page'=>__('Addresses ')]) }} </a>

                                                            </div>
                                                            <div class="modal fade" id="view-addresses-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">{{ __('View Addresses For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Address Category') }}</th>
                                                                                        <th>{{ __('Address Description') }}</th>
                                                                                        <th>{{ __('Latitude') }}</th>
                                                                                        <th>{{ __('Longitude') }}</th>
                                                                                        <th>{{ __('Date') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->addresses as $index=>$address)
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ $address->getCategory() }}</td>
                                                                                        <td>{{ $address->getDescription() }}</td>
                                                                                        <td>{{ $address->getLatitude() }}</td>
                                                                                        <td>{{ $address->getLongitude() }}</td>
                                                                                        <td>{{ formatForView($address->created_at) }}</td>

                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="dropdown ml-2 address">
                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Rating') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#view-received-rating-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View :page',['page'=>__('Received Rating')]) }} </a>
                                                                <a data-toggle="modal" data-target="#view-sent-rating-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View :page',['page'=>__('Sent Rating')]) }} </a>


                                                            </div>

                                                            <div class="modal fade" id="view-received-rating-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">{{ __('View Received Rating For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Driver Name') }}</th>
                                                                                        <th>{{ __('Rate') }}</th>
                                                                                        <th>{{ __('Date') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->getReceivedRatings() as $index=>$rate)
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ getModelNameByNamespaceAndId($rate->author_type,$rate->author_id) }}</td>
                                                                                        <td>{{ $rate->rating }}</td>
                                                                                        <td>{{ formatForView($rate->created_at) }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>



                                                            <div class="modal fade" id="view-sent-rating-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">{{ __('View Sent Rating For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Driver Name') }}</th>
                                                                                        <th>{{ __('Rate') }}</th>
                                                                                        <th>{{ __('Date') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->getSentRatings() as $index=>$rate)
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ getModelNameByNamespaceAndId($rate->reviewrateable_type,$rate->reviewrateable_id) }}</td>
                                                                                        <td>{{ $rate->rating }}</td>
                                                                                        <td>{{ formatForView($rate->created_at) }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>



                                                        <div class="dropdown ml-2 emergency-contacts">

                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Emergency Contacts') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#view-emergency-contacts-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('View :page',['page'=>__('Contacts')]) }} </a>
                                                                <a data-toggle="modal" data-target="#add-emergency-contact-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Add :page',['page'=>__('Contacts')]) }} </a>
                                                            </div>
                                                            <div class="modal fade" id="view-emergency-contacts-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">{{ __('View Emergency Contacts For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Emergency Call Name') }}</th>
                                                                                        <th>{{ __('Phone') }}</th>
                                                                                        <th>{{ __('Email') }}</th>
                                                                                        <th>{{ __('Can Receive Travel Info') }}</th>
                                                                                        <th>{{ __('Date') }}</th>
                                                                                        <th>{{ __('Actions') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->emergencyContacts as $index=>$emergencyContact)
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ $emergencyContact->getName() }}</td>
                                                                                        <td>{{ $emergencyContact->getPhone() }}</td>
                                                                                        <td>{{ $emergencyContact->getEmail() }}</td>
                                                                                        <td class="form-group pb-1">
                                                                                            <x-form.checkbox-element data-toggle-route="{{ $toggleCanReceiveTravelInfos }}" data-model-id="{{ $model->id }}" data-model-type="{{ $modelType }}" data-id="{{ $emergencyContact->id }}" class="switch-trigger-js" :is-required="false" :name="'can_receive_travel_info'" :is-checked="$emergencyContact->pivot->can_receive_travel_info"> </x-form.checkbox-element>
                                                                                        </td>
                                                                                        <td>{{ formatForView($emergencyContact->pivot->created_at) }}</td>
                                                                                        <td class="d-flex align-items-center justify-content-sm-center">
                                                                                            @if($user->can(getPermissionName('update')))
                                                                                            <a href="{{route('emergency-contacts.edit',['emergency_contact'=>$emergencyContact->id])}}" class="btn btn-primary btn-sm mr-2">
                                                                                                <i class="la la-pencil"></i>
                                                                                            </a>
                                                                                            @endif
                                                                                            @if($user->can(getPermissionName('delete')))
                                                                                            <a href="#" data-toggle="modal" data-target="#detach-{{ $model->id }}emergency-contacts-{{ $emergencyContact->id }}" class="delete-modal-trigger-js btn btn-danger btn-sm">
                                                                                                <i class="la la-trash"></i></a>
                                                                                            <x-modals.delete :model-full-id="'detach-'.$model->id.'emergency-contacts-'.$emergencyContact->id" :full-delete-route="route('detach.modal.emergency-contacts',['model_id'=>$model->id ,'model_type'=>$modelType, 'emergency_contact_id'=>$emergencyContact->id ])" :model-id="$model->id"></x-modals.delete>
                                                                                            @endif
                                                                                        </td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>



                                                            <div class="modal fade" id="add-emergency-contact-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('emergency-contacts.attach') }}" method="post">
                                                                            @csrf

                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ __('Attach Emergency Contact To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-6" data-model-id="{{$model->id}}">
                                                                                        <x-form.checkbox :class="'toggle-emergency-call-form'" :id="'from_existing_contact'.$model->id" :label="__('From Existing Contacts')" :is-required="false" :name="'from_existing_contact'" :is-checked="false"> </x-form.checkbox>
                                                                                    </div>

                                                                                    <div class="col-md-6 js-toggle-emergency-call-off-{{ $model->id }}" style="display:none">
                                                                                        <x-form.select :is-required="true" :is-select2="true" :options="$emergencyContactsFormatted" :add-new="false" :label="__('Name')" :all="false" name="emergency_contact_id" id="emergency_contact_id{{ $model->id }}" :selected-value="old('emergency_contact_id') "></x-form.select>
                                                                                    </div>
                                                                                </div>

                                                                                @include('admin.emergency-contacts.form',['model'=>null,'class'=>'js-toggle-emergency-call-on-'.$model->id])

                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary js-save-by-ajax">{{ __('Attach :item',['item'=>__('Emergency Contacts')]) }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                        </div>

                                                        @include('components.common.user-transactions')
                                                        @include('components.common.travels')

                                                        <div class="dropdown ml-2 inventation-codes">

                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Ban') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#ban-account-popup{{ $model->id }}" class="dropdown-item" href="#">
                                                                    @if($model->isBanned())
                                                                    {{ __('Unban') }}
                                                                    @else
                                                                    {{ __('Ban') }}
                                                                    @endif
                                                                </a>
                                                                <a data-toggle="modal" data-target="#view-ban-history-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Ban History') }} </a>

                                                            </div>
                                                            <div class="modal fade" id="view-ban-history-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        @csrf

                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">{{ __('Ban History For :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <table class="table datatable datatable-js">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th class="text-center">#</th>
                                                                                        <th>{{ __('Ban Date') }}</th>
                                                                                        <th>{{ __('Reason') }}</th>
                                                                                        <th>{{ __('Banned By') }}</th>
                                                                                        <th>{{ __('Removing Date') }}</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($model->banHistories as $index=>$ban)
                                                                                    @if($ban)
                                                                                    @endif
                                                                                    <tr>
                                                                                        <td>{{ $index+1 }}</td>
                                                                                        <td>{{ formatForView($ban->created_at) }}</td>
                                                                                        <td>{{ $ban->comment }}</td>
                                                                                        <td>{{ $ban->created_by_type::getNameById($ban->created_by_id) }}</td>
                                                                                        <td>{{ formatForView($ban->deleted_at) }}</td>
                                                                                    </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>





                                                        </div>



                                                        <div @if($model->getIsVerified())
                                                            style="display:none"
                                                            @endif

                                                            data-model-id="{{ $model->id }}" class="dropdown send-verification-code-message-js">

                                                            <button type="button" class="dropdown-toggle btn btn-outline-primary bg-primary " data-toggle="dropdown"> {{ __('Send Verification Code') }} </button>
                                                            <div class="dropdown-menu">
                                                                <a data-toggle="modal" data-target="#send-whatsapp-verification-code-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Whatsapp') }} </a>
                                                                <a data-toggle="modal" data-target="#send-sms-verification-code-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Sms') }} </a>
                                                                <a data-toggle="modal" data-target="#send-email-verification-code-popup{{ $model->id }}" class="dropdown-item" href="#"> {{ __('Through Email') }} </a>
                                                            </div>
                                                            <div class="modal fade" id="send-whatsapp-verification-code-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.verification.code.through.whatsapp') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">

                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ __('Send Whatsapp Verification Code To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal fade" id="send-sms-verification-code-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg  modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.verification.code.through.sms') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">

                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ __('Send Sms Verification Code To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="modal fade" id="send-email-verification-code-popup{{ $model->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg modal-dialog modal-lg-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('send.verification.code.through.email') }}" method="post">
                                                                            @csrf
                                                                            <input type="hidden" name="model_id" value="{{ $model->id }}">
                                                                            <input type="hidden" name="model_type" value="{{ $modelType }}">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ __('Send Verification Message By Email To :name',['name'=>$model->getFullName($lang)]) }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                                                                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
                                                                            </div>

                                                                        </form>
                                                                    </div>
                                                                </div>

                                                            </div>


                                                        </div>
                                                    </div>






                                                    @endif
                                                </td>
                                                @endif
                                            </tr>
