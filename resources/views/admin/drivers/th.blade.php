<th class="th-global-class  text-center">#</th>
                                            <th class="th-global-class  text-center">{{__('Name')}}</th>
                                            <th class="th-global-class  text-center">{{__('Image')}}</th>
                                            <th class="th-global-class  text-center">{{__('Phone')}}</th>
                                            <th class="th-global-class  text-center">{{__('Email')}}</th>
                                            @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
                                            <th class="th-global-class  text-center">{{__('Actions')}}</th>
                                            @endif
