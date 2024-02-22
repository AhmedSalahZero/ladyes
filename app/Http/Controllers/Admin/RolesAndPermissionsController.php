<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Models\Admin;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsController extends Controller
{
	use Globals;
	public function __construct()
	{
		$this->middleware('permission:'.getPermissionName('view') , ['only'=>['index']]) ;
		$this->middleware('permission:'.getPermissionName('create') , ['only'=>['create','store']]) ;
		$this->middleware('permission:'.getPermissionName('update') , ['only'=>['edit','update']]) ;
		$this->middleware('permission:'.getPermissionName('delete') , ['only'=>['destroy']]) ;
	}
	
    public function index()
    {
        $roles = Role::get();
        return view('admin.roles_and_permissions.index',
		[
			'models'=>$roles,
			'pageTitle'=>__('Roles & Permissions'),
			'createRoute'=>route('roles.permissions.create'),
			'editRouteName'=>'roles.permissions.edit',
			'deleteRouteName'=>'roles.permissions.delete',
		]
	);
    }
    public function create()
    {
        return view('admin.roles_and_permissions.crud',$this->getViewUrl());
    }
	public function getViewUrl($model = null ):array {
		$breadCrumbs = [
			'dashboard'=>[
				'title'=>__('Dashboard') ,
				'route'=>route('dashboard.index'),
			],
			'roles-and-permissions'=>[
				'title'=>__('Roles & Permissions') ,
				'route'=>route('roles.permissions.index'),
			],
			'create-role-and-permission'=>[
				'title'=>__('create :page',['page'=>__('Role & Permission')]),
				'route'=>'#'
			]
		];
		return [
			'breadCrumbs'=>$breadCrumbs,
			'pageTitle'=>__('Roles & Permissions'),
			'route'=>$model ? route('roles.permissions.update',[$model->id ]): route('roles.permissions.store') ,
			'model'=>$model ,
			'indexRoute'=>route('roles.permissions.index')
		];
	}
    public function store(StoreRoleRequest $request )
    {
        $role = Role::create(['name' => $request->get('name')]);
		$permissions = array_keys((array)$request->permissions) ;
		$permissions = $permissions ? $permissions : [];
        $role->syncPermissions($permissions);
		
		Notification::storeNewAdminNotification(
			__('New Creation',[],'en'),
			__('New Creation',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Created New',[],'en') . __('Role & Permission',[],'ar') .' [ ' . $request->get('name') . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Created New',[],'ar') . __('Role & Permission',[],'ar') .' [ ' . $request->get('name') . ' ]' ,
		);
		
		return $this->getWebRedirectRoute($request,route('roles.permissions.index'),route('roles.permissions.create'));
    }
    public function edit(Role $role)
    {
        $role->load('permissions');
        return view('admin.roles_and_permissions.crud',$this->getViewUrl($role));
    }
    public function update(Request $request ,Role $role)
    {
        $role->update(['name' => $request->get('name')]);
		$permissions = array_keys((array)$request->permissions);
		$permissions = $permissions ?$permissions : [];
        $role->syncPermissions($permissions);
		Notification::storeNewAdminNotification(
			__('New Update',[],'en'),
			__('New Update',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Updated',[],'en') . __('Role & Permission',[],'en') .' [ ' . $request->get('name') . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Updated',[],'ar') . __('Role & Permission',[],'ar') .' [ ' . $request->get('name') . ' ]' ,
		);
		
		return $this->getWebRedirectRoute($request,route('roles.permissions.index'),route('roles.permissions.edit',['role'=>$role->id]));

    }
	public function delete(Request $request , Role $role )
    {
		
		$admins = Admin::get();
		foreach($admins as $admin){
			if($admin->hasRole($role,$role->guard_name)){
				return redirect()->back()->with('fail',__('This Role Can Not Be Deleted .. Its Attached For Admins'));
			}
		}
		$role->delete();
		
		Notification::storeNewAdminNotification(
			__('New Deletion',[],'en'),
			__('New Deletion',[],'ar'),
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'en') . __('Role & Permission',[],'en') .' [ ' . $role->name . ' ]' ,
			$request->user('admin')->getName() .' '.__('Has Deleted',[],'ar') . __('Role & Permission',[],'ar') .' [ ' . $role->name . ' ]' ,
		);
		
		return $this->getWebDeleteRedirectRoute();
    }
}
