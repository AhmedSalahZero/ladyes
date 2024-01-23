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
	$normalPermissions = ['admins','roles-and-permissions','car-makes','car-models','drivers','cities','areas'];
	foreach($normalPermissions as $permissionName){
		foreach(['view','create','update','delete'] as $permissionType){
			$title = HStr::camelizeWithSpace(str_replace('and','&',$permissionName)) ;	
			$permissionTypeAsTitle = ucfirst($permissionType);
			$permissions[] = ['name'=>$permissionType .' ' . $permissionName , 'title'=>__($permissionTypeAsTitle .' :page' , ['page'=>__($title)]) ] ;
		}
	}
	$permissions[] = ['name'=>'view' .' ' . 'countries' , 'title'=>__('View' .' :page' , ['page'=>__('Countries')]) ] ;
	$permissions[] = ['name'=>'create' .' ' . 'settings' , 'title'=>__('View' .' :page' , ['page'=>__('Settings')]) ] ;
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
			$pageName = 'areas'=> createSidebarItem( $pageName, __('Areas') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-bank', 
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Areas')]) , route('areas.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('Create :page' , ['page'=>__('Area')]) , route('areas.create') , $user->can('create ' .$pageName)  ),
			]),
			$pageName = 'drivers'=> createSidebarItem( $pageName, __('Drivers') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-car', 
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Drivers')]) , route('drivers.index') , $user->can('view ' .$pageName)  ),
				createSidebarItem($pageName, __('Create :page' , ['page'=>__('Driver')]) , route('drivers.create') , $user->can('create ' .$pageName)  ),
			]),
			$pageName = 'settings'=> createSidebarItem( $pageName, __('Settings') ,  '#' ,$user->can('create '.$pageName) || $user->can('create '.$pageName) ,'la la-cogs', 
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Settings')]) , route('settings.create') , $user->can('create ' .$pageName)  ),
				// createSidebarItem($pageName, __('Create :page' , ['page'=>__('Driver')]) , route('drivers.create') , $user->can('create ' .$pageName)  ),
			]),
			$pageName = 'notifications'=> createSidebarItem( $pageName, __('Notifications') ,  '#' ,$user->can('view '.$pageName) || $user->can('create '.$pageName) ,'la la-bell', 
			[
				createSidebarItem($pageName, __('View :page' , ['page' => __('Notifications')]) , route('notifications.index') , $user->can('view ' .$pageName)  ),
			])
			
			
		
		
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
