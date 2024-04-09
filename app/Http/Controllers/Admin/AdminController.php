<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Models\Admin;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    use Globals;

    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index']]) ;
        $this->middleware('permission:' . getPermissionName('create'), ['only' => ['create', 'store']]) ;

        $this->middleware('permission:' . getPermissionName('update'), ['only' => ['edit', 'update']]) ;
        $this->middleware('permission:' . getPermissionName('delete'), ['only' => ['destroy']]) ;
    }

    public function profile()
    {
        return view('admin.profile.profile');
    }

    public function updateProfile(Request $request)
    {
        $admin = $request->user('admin');
        if ($request->name) {
            $admin->name = $request->name;
        }
        if ($request->email) {
            $admin->email = $request->email;
        }
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        return redirect()->back()->with('success', __('msg.updated_success'));
    }

    public function index()
    {
        $models = Admin::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.admins.index', [
            'models' => $models,
            'pageTitle' => __('Admins'),
            'createRoute' => route('admins.create'),
            'editRouteName' => 'admins.edit',
            'deleteRouteName' => 'admins.destroy',
            'toggleIsActiveRoute' => route('admins.toggle.is.active')
        ]);
    }

    public function create()
    {
        return view('admin.admins.crud', $this->getViewUrl());
    }

    public function getViewUrl($model = null): array
    {
        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'admins' => [
                'title' => __('Admins'),
                'route' => route('admins.index'),
            ],
            'create-admin' => [
                'title' => __('Create Admin'),
                'route' => '#'
            ]
        ];
        $rules = Role::all();

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Admins'),
            'route' => $model ? route('admins.update', ['admin' => $model->id]) : route('admins.store'),
            'model' => $model,
            'rolesFormattedForSelect' => $rules->formattedForSelect(false, 'name', 'name'),
            'indexRoute' => route('admins.index')
        ];
    }

    public function store(StoreAdminRequest $request)
    {
        $admin = new Admin();
        $admin->syncFromRequest($request);
        Notification::storeNewAdminNotification(
            __('New Creation', [], 'en'),
            __('New Creation', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'en'). ' ' . __('Admin', [], 'en') . ' [ ' . $admin->getName() . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'ar'). ' ' . __('Admin', [], 'ar') . ' [ ' . $admin->getName() . ' ]',
        );
        foreach (getPermissions() as $permission) {
            $permission = Permission::findByName($permission['name'], 'admin');
            $admin->givePermissionTo($permission);
        }

        return $this->getWebRedirectRoute($request, route('admins.index'), route('admins.create'));
    }

    public function edit(Admin $admin)
    {
        return view(
            'admin.admins.crud',
            $this->getViewUrl($admin),
        );
    }

    public function update(StoreAdminRequest $request, Admin $admin)
    {
        $admin->syncFromRequest($request);

        Notification::storeNewAdminNotification(
            __('New Update', [], 'en'),
            __('New Update', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Updated', [], 'en') . __('Admin', [], 'en') . ' [ ' . $admin->getName() . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Updated', [], 'ar') . __('Admin', [], 'ar') . ' [ ' . $admin->getName() . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('admins.index'), route('admins.edit', ['admin' => $admin->id]));
    }

    public function destroy(Request $request, Admin $admin)
    {
        $admin->delete();

        Notification::storeNewAdminNotification(
            __('New Deletion', [], 'en'),
            __('New Deletion', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'en'). ' ' . __('Admin', [], 'en') . ' [ ' . $admin->getName() . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'ar'). ' ' . __('Admin', [], 'ar') . ' [ ' . $admin->getName() . ' ]',
        );
		return $this->getWebDeleteRedirectRoute();
    }

    public function toggleIsActive(Request $request)
    {
        $admin = Admin::find($request->id);
        if ($admin) {
            $admin->toggleIsActive();

            Notification::storeNewAdminNotification(
                __('New Update', [], 'en'),
                __('New Update', [], 'ar'),
                $request->user('admin')->getName() . ' ' . __('Has Updated', [], 'en') . __('Admin', [], 'en') . ' [ ' . $admin->getName() . ' ]',
                $request->user('admin')->getName() . ' ' . __('Has Updated', [], 'ar') . __('Admin', [], 'ar') . ' [ ' . $admin->getName() . ' ]',
            );
        }

        return response()->json([
            'status' => true,
            'id' => $request->id,
        ]);
    }


}
