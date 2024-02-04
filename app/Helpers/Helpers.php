<?php

use App\Helpers\HAuth;
use App\Helpers\HDate;
use App\Helpers\HStr;
use App\Settings\SiteSetting;

	function getDefaultImage()
	{
		return asset('custom/images/default-img.png');
	}

function getPermissions():array
{
	$permissions[] = ['name'=>'view home','title'=>__('View :page',['page'=>__('Home')])];
	$permissions[] = ['name'=>'view notifications','title'=>__('View :page',['page'=>__('Notifications')])];
	$normalPermissions = ['admins','roles-and-permissions','car-makes','car-models','drivers','clients','cities'
	,'travel-conditions','cancellation-reasons','emergency-contacts','promotions','helps','information','coupons'

];
	foreach($normalPermissions as $permissionName){
		foreach(['view','create','update','delete'] as $permissionType){
			$title = HStr::camelizeWithSpace(str_replace('and','&',$permissionName)) ;
			$permissionTypeAsTitle = ucfirst($permissionType);
			$permissions[] = ['name'=>$permissionType .' ' . $permissionName , 'title'=>__($permissionTypeAsTitle .' :page' , ['page'=>__($title)]) ] ;
		}
	}
	$permissions[] = ['name'=>'view' .' ' . 'countries' , 'title'=>__('View' .' :page' , ['page'=>__('Countries')]) ] ;
	$permissions[] = ['name'=>'create' .' ' . 'settings' , 'title'=>__('View' .' :page' , ['page'=>__('Settings')]) ] ;
	$permissions[] = ['name'=>'create' .' ' . 'app-guidelines' , 'title'=>__('View' .' :page' , ['page'=>__('App Guidelines')]) ] ;
    return $permissions;
}

function getSidebars($user):array
{
	// dd($user->can('view home'));
	return [
		$pageName = 'home'=>createSidebarItem($pageName, __('Home') ,  '#' ,$user->can('view home'),'la la-home' ),
		$pageName = 'admins'=> createSidebarItem( $pageName , __('Admins') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-user-secret',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Admins')]) , route('admins.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem( $pageName , __('Create :page' , ['page'=>__('Admin')]) , route('admins.create') , $user->can('create ' .$pageName)  ),
			]
			),

			$pageName = 'roles-and-permissions'=> createSidebarItem( $pageName, __('Roles & Permissions') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-key',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Roles & Permissions')]) , route('roles.permissions.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('Create :page' , ['page'=>__('Role & Permission')]) , route('roles.permissions.create') , $user->can('create ' .$pageName)  ),
			]),

			$pageName = 'travel-conditions'=> createSidebarItem( $pageName , __('Travel Conditions') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-question',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Travel Conditions')]) , route('travel-conditions.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem( $pageName , __('Create :page' , ['page'=>__('Travel Condition')]) , route('travel-conditions.create') , $user->can('create ' .$pageName)  ),
			]
			),
			
			

			$pageName = 'emergency-contacts'=> createSidebarItem( $pageName , __('Emergency Contacts') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-mobile-phone',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Emergency Contacts')]) , route('emergency-contacts.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem( $pageName , __('Create :page' , ['page'=>__('Emergency Contact')]) , route('emergency-contacts.create') , $user->can('create ' .$pageName)  ),
			]
			),


			$pageName = 'cancellation-reasons'=> createSidebarItem( $pageName , __('Cancellation Reasons') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-remove',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Cancellation Reasons')]) , route('cancellation-reasons.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem( $pageName , __('Create :page' , ['page'=>__('Cancellation Reason')]) , route('cancellation-reasons.create') , $user->can('create ' .$pageName)  ),
			]
			),

			$pageName = 'car-makes'=> createSidebarItem( $pageName, __('Car Makes') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-bus',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Car Makes')]) , route('car-makes.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('Create :page' , ['page'=>__('Car Make')]) , route('car-makes.create') , $user->can('create ' .$pageName)  ),
			]),

			$pageName = 'car-models'=> createSidebarItem( $pageName, __('Car Models') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-ambulance',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Car Models')]) , route('car-models.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('Create :page' , ['page'=>__('Car Model')]) , route('car-models.create') , $user->can('create ' .$pageName)  ),
			]),
			$pageName = 'countries'=> createSidebarItem( $pageName, __('Countries') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-flag',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Countries')]) , route('countries.index') , $user->can('view ' .$pageName)  ),
			])
			,
			$pageName = 'cities'=> createSidebarItem( $pageName, __('Cities') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-building',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Cities')]) , route('cities.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('Create :page' , ['page'=>__('City')]) , route('cities.create') , $user->can('create ' .$pageName)  ),
			]),

			$pageName = 'drivers'=> createSidebarItem( $pageName, __('Drivers') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-car',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Drivers')]) , route('drivers.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('Create :page' , ['page'=>__('Driver')]) , route('drivers.create') , $user->can('create ' .$pageName)  ),
			]),
			$pageName = 'clients'=> createSidebarItem( $pageName, __('Clients') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-user',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Clients')]) , route('clients.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('Create :page' , ['page'=>__('Client')]) , route('clients.create') , $user->can('create ' .$pageName)  ),
			]),
			$pageName = 'coupons'=> createSidebarItem( $pageName, __('Coupons') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-book',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Coupons')]) , route('coupons.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('Create :page' , ['page'=>__('Coupon')]) , route('coupons.create') , $user->can('create ' .$pageName)  ),
			]),
			$pageName = 'promotions'=> createSidebarItem( $pageName, __('Promotions') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-archive',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Promotions')]) , route('promotions.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('Create :page' , ['page'=>__('Promotion')]) , route('promotions.create') , $user->can('create ' .$pageName)  ),
			]),$pageName = 'settings'=> createSidebarItem( $pageName, __('Settings') ,  '#' ,$user->can('create '.$pageName) || $user->can('create '.$pageName) ,'la la-cogs',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Settings')]) , route('settings.create') , $user->can('create ' .$pageName)  ),
			]),
			$pageName = 'notifications'=> createSidebarItem( $pageName, __('Notifications') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-bell',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Notifications')]) , route('notifications.index') , $user->can('view ' .$pageName)  ),
			])
			,
			
			
			$pageName = 'app-guidelines'=> createSidebarItem( $pageName, __('App Guidelines') ,  '#' ,$user->can('create '.$pageName) || $user->can('create '.$pageName) ,'la la-cogs',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('App Guidelines')]) , route('app-guidelines.create') , $user->can('create ' .$pageName)  ),
			])
			
			,
			
			$pageName = 'information'=> createSidebarItem( $pageName , __('Information') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-question',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Information')]) , route('information.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem( $pageName , __('Create :page' , ['page'=>__('Information')]) , route('information.create') , $user->can('create ' .$pageName)  ),
			]
			)
			,
			
			$pageName = 'helps'=> createSidebarItem( $pageName , __('Helps') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-question',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Helps')]) , route('helps.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem( $pageName , __('Create :page' , ['page'=>__('Help')]) , route('helps.create') , $user->can('create ' .$pageName)  ),
			]
			),




	];

}

function convertStringToClass(string $str): string
{
    $reg = " /^[\d]+|[!\"#$%&'\(\)\*\+,\.\/:;<=>\?\@\~\{\|\}\^ ]/ ";

    return preg_replace($reg, '-', $str);
}
function getPageNameFromUrl():string
{
	return Request()->segment(1);
}
function getPermissionName($permissionName)
{
	return $permissionName .' '. getPageNameFromUrl() ;
}
function getCurrentUser()
{
	return Auth()->guard(HAuth::getActiveGuard())->user();
}
function createSidebarItem($id , string $title,string $route,bool $show,string $icon  = null , array $subItems = [])
{
	return [
			'title'=>$title ,
			'icon'=>$icon,
			'show'=>$show,
			'is_active'=>getPageNameFromUrl() == $id ,
			'route'=>$route ,
			'subitems'=>$subItems
	];
}
function getSetting($settingName){

	return App(SiteSetting::class)->{$settingName};
}
function formatForView(?string $date ,bool $onlyDate = false )
{
	return HDate::formatForView($date,$onlyDate);
}
