<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Notification;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index']]) ;
        // $this->middleware('permission:'.getPermissionName('update') , ['only'=>['edit','update']]) ;
        // $this->middleware('permission:'.getPermissionName('delete') , ['only'=>['destroy']]) ;
    }

    public function index()
    {
        $models = Notification::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.notifications.index', [
            'models' => $models,
            'pageTitle' => __('Notifications')
        ]);
    }

    public function getViewUrl($model = null): array
    {
        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'notifications' => [
                'title' => __('Notifications'),
                'route' => route('notifications.index'),
            ],
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Notifications'),
            'model' => $model,
            'indexRoute' => route('notifications.index')
        ];
    }

    public function markAsRead(Admin $admin)
    {
        foreach ($admin->unreadNotifications as $notification) {
            $notification->markAsRead();
        }

        return response()->json([
            'status' => true,
        ]);
    }
}
