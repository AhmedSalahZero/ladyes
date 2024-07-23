 <th class="th-global-class  text-center">#</th>
 <th class="th-global-class  text-center">{{__('User Type')}}</th>
 <th class="th-global-class  text-center">{{__('Name')}}</th>
 <th class="th-global-class  text-center">{{__('Subject')}}</th>
 <th class="th-global-class  text-center">{{__('Message')}}</th>
 <th class="th-global-class  text-center">{{__('')}}</th>
 @if($user->can(getPermissionName('update')) || $user->can(getPermissionName('delete')) )
 <th class="th-global-class  text-center">{{__('Actions')}}</th>
 @endif
