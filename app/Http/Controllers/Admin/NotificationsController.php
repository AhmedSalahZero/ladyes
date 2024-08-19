<?php

namespace App\Http\Controllers\Admin;

use App\Enum\AppNotificationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppNotificationRequest;
use App\Jobs\SendAppNotificationsJob;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    use Globals;
	
    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['viewAdminNotifications']]) ;
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['viewAppNotifications']]) ;
    }
	
	
	/**
	 * * الاشعارات التلقائيه الخاصة بالادمن بانل وليكن مثلا تم انشاء عميل او سائق او تم تحديثه او حذفه الخ
	 */
    public function viewAdminNotifications()
    {
        $models = Notification::onlyAdminsNotifications()->defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
        return view('admin.notifications.view-admin', [
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
                'route' => route('app.notifications.index'),
            ],
			'dd'=>[
				'title'=>'e',
				'route'=>'#'
			]
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Notifications'),
            'model' => $model,
            'indexRoute' => route('admin.notifications.index'),
			'createRoute'=>route('app.notifications.create')
			
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
	/**
	 * * الاشعارات اللي بيتم ارسالها للعملاء و السائقين او لكلايهما في التطبيق ( الموبايل ابلكيشن)
	 */
	public function viewAppNotifications()
    {
        $models = Notification::onlyAppNotifications()->defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);
        return view('admin.notifications.view-app', [
            'models' => $models,
			'pageTitle'=>__('App Notifications'),
			'createRoute'=>route('app.notifications.create')
        ]);
    }
	public function createAppNotifications(Request $request){
		$clientsFormatted = Client::onlyIsVerified()->get()->formattedForSelect(true,'getId','getName');
		$driversFormatted = Driver::onlyIsVerified()->get()->formattedForSelect(true,'getId','getName');
		return view('admin.notifications.create',array_merge(
			$this->getViewUrl(),
			[
				'clientsFormatted'=>$clientsFormatted,
				'driversFormatted'=>$driversFormatted,
				'indexRoute' => route('admin.notifications.index'),
				'notificationTypesFormatted'=>AppNotificationType::allFormattedForSelect2(),
				'route'=>'#'
			]
		));
	}
	public function storeAppNotifications(StoreAppNotificationRequest $request){
		$titleEn = $request->get('title_en');
		$titleAr = $request->get('title_ar');
		$messageAr = $request->get('message_ar');
		$messageEn = $request->get('message_en');
		$type = $request->get('type',AppNotificationType::DEFAULT);
		foreach($request->get('client_ids',[]) as $clientId){
			$client = Client::find($clientId);
				dispatch(new SendAppNotificationsJob($client,$titleEn,$titleAr,$messageEn,$messageAr,$type ));
		}
		foreach($request->get('driver_ids',[]) as $driverId){
			$driver = Driver::find($driverId);
			dispatch(new SendAppNotificationsJob($driver,$titleEn,$titleAr,$messageEn,$messageAr,$type ));
		}
        return $this->getWebRedirectRoute($request, route('app.notifications.create'), route('app.notifications.create'));
	}
	
}
