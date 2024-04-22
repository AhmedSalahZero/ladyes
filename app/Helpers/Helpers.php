<?php

use App\Helpers\HAuth;
use App\Helpers\HDate;
use App\Helpers\HHelpers;
use App\Helpers\HStr;
use App\Settings\SiteSetting;

	function getDefaultImage()
	{
		return asset('custom/images/default-img.png');
	}

function getPermissions():array
{
	$permissions[] = ['name'=>'view home','title'=>__('View :page',['page'=>__('Home')])];
	$permissions[] = ['name'=>'view admin-notifications','title'=>__('View :page',['page'=>__('Admin Notifications')])];
	$permissions[] = ['name'=>'view app-notifications','title'=>__('View :page',['page'=>__('App Notifications')])];
	$permissions[] = ['name'=>'create app-notifications','title'=>__('Create :page',['page'=>__('App Notifications')])];
	$normalPermissions = ['admins','roles-and-permissions','car-makes','car-models','drivers','clients','cities'
	,'travel-conditions','cancellation-reasons','emergency-contacts','promotions','helps','information','coupons','fines','bonuses','deposits','withdrawals','payments'

];
	foreach($normalPermissions as $permissionName){
		foreach(['view','create','update','delete'] as $permissionType){
			$title = HStr::camelizeWithSpace(str_replace('and','&',$permissionName)) ;
			$permissionTypeAsTitle = ucfirst($permissionType);
			$permissions[] = ['name'=>$permissionType .' ' . $permissionName , 'title'=>__($permissionTypeAsTitle .' :page' , ['page'=>__($title)]) ] ;
		}
	}
	$permissions[] = ['name'=>'view' .' ' . 'transactions' , 'title'=>__('View' .' :page' , ['page'=>__('Transactions')]) ] ;
	$permissions[] = ['name'=>'view' .' ' . 'travels' , 'title'=>__('View' .' :page' , ['page'=>__('Travels')]) ] ;
	$permissions[] = ['name'=>'view' .' ' . 'countries' , 'title'=>__('View' .' :page' , ['page'=>__('Countries')]) ] ;
	$permissions[] = ['name'=>'update' .' ' . 'countries' , 'title'=>__('View' .' :page' , ['page'=>__('Countries')]) ] ;
	$permissions[] = ['name'=>'view' .' ' . 'support-tickets' , 'title'=>__('View' .' :page' , ['page'=>__('Support Tickets')]) ] ;
	$permissions[] = ['name'=>'view' .' ' . 'car-sizes' , 'title'=>__('View' .' :page' , ['page'=>__('Car Sizes')]) ] ;
	$permissions[] = ['name'=>'update' .' ' . 'car-sizes' , 'title'=>__('Update' .' :page' , ['page'=>__('Car Sizes')]) ] ;
	$permissions[] = ['name'=>'create' .' ' . 'settings' , 'title'=>__('View' .' :page' , ['page'=>__('Settings')]) ] ;
	$permissions[] = ['name'=>'create' .' ' . 'app-guidelines' , 'title'=>__('View' .' :page' , ['page'=>__('App Guidelines')]) ] ;
	$permissions[] = ['name'=>'create' .' ' . 'app-text' , 'title'=>__('View' .' :page' , ['page'=>__('App Text')]) ] ;
    return $permissions;
}

function getSidebars($user):array
{
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
			$pageName = 'car-sizes'=> createSidebarItem( $pageName, __('Car Sizes') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-flag',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Car Sizes')]) , route('car-sizes.index') , $user->can('view ' .$pageName)  ),
			]),
			$pageName = 'support-tickets'=> createSidebarItem( $pageName, __('Support Tickets') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-ticket',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Support Tickets')]) , route('support-tickets.index') , $user->can('view ' .$pageName)  ),
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
			])
			,
			$pageName = 'transactions'=> createSidebarItem( $pageName, __('Transactions') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-money',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Transactions')]) , route('transactions.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('View :page' , ['page' => __('Fines')]) , route('fines.index') , $user->can('view fines')  ),
				createSidebarItem($pageName, __('View :page' , ['page' => __('Bonuses')]) , route('bonuses.index') , $user->can('view bonuses')  ),
				createSidebarItem($pageName, __('View :page' , ['page' => __('Deposits')]) , route('deposits.index') , $user->can('view deposits')  ),
				createSidebarItem($pageName, __('View :page' , ['page' => __('Withdrawals')]) , route('withdrawals.index') , $user->can('view withdrawals')  ),
				createSidebarItem($pageName, __('View :page' , ['page' => __('Payments')]) , route('payments.index') , $user->can('view payments')  ),
				// createSidebarItem($pageName, __('Create :page' , ['page'=>__('Transactions')]) , route('transactions.create') , $user->can('create ' .$pageName)  ),
			]),$pageName = 'travels'=> createSidebarItem( $pageName, __('Travels') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-car',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Travels')]) , route('travels.index') , $user->can('view ' .$pageName)  ),
				// createSidebarItem($pageName, __('Create :page' , ['page'=>__('Transactions')]) , route('transactions.create') , $user->can('create ' .$pageName)  ),
			])
			,$pageName = 'settings'=> createSidebarItem( $pageName, __('Settings') ,  '#' ,$user->can('create '.$pageName) || $user->can('create '.$pageName) ,'la la-cogs',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Settings')]) , route('settings.create') , $user->can('create ' .$pageName)  ),
			]),
			$pageName = 'admin-notifications'=> createSidebarItem( $pageName, __('Notifications') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-bell',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Admin Notifications')]) , route('admin.notifications.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('View :page' , ['page' => __('App Notifications')]) , route('app.notifications.index') , $user->can('view app-notifications')  ),
			])
			,
			
			
			$pageName = 'app-guidelines'=> createSidebarItem( $pageName, __('App Guidelines') ,  '#' ,$user->can('create '.$pageName) || $user->can('create '.$pageName) ,'la la-cogs',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('App Guidelines')]) , route('app-guidelines.create') , $user->can('create ' .$pageName)  ),
			])
			
			,
			$pageName = 'app-text'=> createSidebarItem( $pageName, __('App Text') ,  '#' ,$user->can('create '.$pageName) || $user->can('create '.$pageName) ,'la la-pencil',
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('App Text')]) , route('app-text.create') , $user->can('create ' .$pageName)  ),
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
function getApiLang($lang = null)
{
	if(Request()->is('api/*')){
		return Request()->getPreferredLanguage() ?: 'ar';
	}
	
	return $lang ? $lang : app()->getLocale();
}
function getModelByNamespaceAndId(string $fullClass , int $id){
	$model  =($fullClass)::find($id) ;
	return $model;
}
function getModelNameByNamespaceAndId(string $fullClass , int $id){
	$model  =($fullClass)::find($id) ;
	return $model ? $model->getName() : __('N/A');
}
function getTypeWithoutNamespace($obj):string{
	return HHelpers::getClassNameWithoutNameSpace($obj);
}
