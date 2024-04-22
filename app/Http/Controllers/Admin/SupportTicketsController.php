<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\SupportTicket;
use App\Traits\Controllers\Globals;

class SupportTicketsController extends Controller
{
    use Globals;

    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index']]) ;
        $this->middleware('permission:' . getPermissionName('update'), ['only' => ['edit','update']]) ;
    }

    public function index()
    {
        $models = SupportTicket::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.support-tickets.index', [
            'models' => $models,
            'pageTitle' => __('Support Tickets'),
            // 'createRoute'=>route('car-makes.create'),
            'editRouteName'=>'support-tickets.edit',
            // 'deleteRouteName'=>'car-makes.destroy'
        ]);
    }

    public function getViewUrl($model = null): array
    {
        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'countries' => [
                'title' => __('Support Tickets'),
                'route' => route('support-tickets.index'),
            ],
    
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Support Tickets'),
            'model' => $model,
            'indexRoute' => route('support-tickets.index'),
			'countriesFormatted'=>Country::get()->formattedForSelect(true,'getId','getName'),
            'route' => $model ? route('support-tickets.update', ['car_size' => $model->id]) : route('support-tickets.store'),
        ];
    }
	// public function edit(SupportTicket $supportTicket)
    // {
    //     return view('admin.support-tickets.crud',$this->getViewUrl($supportTicket),
	// );
    // }
	// public function update(StoreSupportTicketRequest $request, SupportTicket $supportTicket)
    // {
    //     $supportTicket->storeBasicForm($request);
		
	// 	foreach($request->get('country_ids',[]) as $countryId){
	// 		$supportTicket->countryPrices()->syncWithoutDetaching([
	// 			$countryId => [
	// 				'model_type' => HHelpers::getClassNameWithoutNameSpace($supportTicket),
	// 			]
	// 		]);
	// 	}
		

    //     Notification::storeNewAdminNotification(
    //         __('New Update', [], 'en'),
    //         __('New Update', [], 'ar'),
    //         $request->user('admin')->getName() . ' ' . __('Has Update', [], 'en') . __('Car Size', [], 'en') . ' [ ' . $supportTicket->getName('en') . ' ]',
    //         $request->user('admin')->getName() . ' ' . __('Has Update', [], 'ar') . __('Car Size', [], 'ar') . ' [ ' . $supportTicket->getName('ar') . ' ]',
    //     );

    //     return $this->getWebRedirectRoute($request, route('support-tickets.index'), route('support-tickets.edit', [ 'car_size'=> $supportTicket->id]));
    // }
	
	
	
}
