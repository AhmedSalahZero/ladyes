<?php

namespace App\Http\Controllers\Admin;

use App\Enum\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Models\Client;
use App\Models\Country;
use App\Models\EmergencyContact;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    use Globals;

    public function __construct()
    {
        foreach (['view' => ['index'], 'create' => ['create', 'store'], 'update' => ['edit', 'update'], 'delete' => ['destroy']] as $name => $method) {
            $this->middleware('permission:' . getPermissionName($name), ['only' => $method]) ;
        }
    }

    public function index()
    {
        $models = Client::with([])->defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.clients.index', [
            'models' => $models,
			'modelType'=>'Client',
            'pageTitle' => __('Clients'),
            'createRoute' => route('clients.create'),
            'editRouteName' => 'clients.edit',
            'deleteRouteName' => 'clients.destroy',
            'toggleIsVerifiedRoute' => route('client.toggle.is.verified'),
            'countriesFormattedForSelect' => Country::get()->formattedForSelect(true, 'getId', 'getName'),
            'emergencyContactsFormatted' => EmergencyContact::get()->formattedForSelect(true, 'getId', 'getName'),
            'toggleCanReceiveTravelInfos' => route('emergency-contacts.toggle.can.receive.travel.infos'),
			'paymentMethodsFormattedForSelect'=>PaymentType::allFormattedForSelect2(),
        ]);
    }

    public function create()
    {
        return view('admin.clients.crud', $this->getViewUrl());
    }

    public function getViewUrl($model = null): array
    {
        $selectedCountryId = $model ? $model->getCountryId() : 0 ;
        $selectedMakeId = $model ? $model->getMakeId() : 0 ;

        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'clients' => [
                'title' => __('Clients'),
                'route' => route('clients.index'),
            ],
            'create-client' => [
                'title' => __('Create :page', ['page' => __('Client')]),
                'route' => '#'
            ]
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Clients'),
            'route' => $model ? route('clients.update', ['client' => $model->id]) : route('clients.store'),
            'model' => $model,
            'indexRoute' => route('clients.index'),
            'countriesFormattedForSelect' => Country::get()->formattedForSelect(true, 'getId', 'getName'),
		
        ];
    }

    public function store(StoreClientRequest $request)
    {
        $model = new Client();
        $model->syncFromRequest($request);
        Notification::storeNewAdminNotification(
            __('New Creation', [], 'en'),
            __('New Creation', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'en'). ' ' . __('Client', [], 'en') . ' [ ' . $model->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'ar'). ' ' . __('Client', [], 'ar') . ' [ ' . $model->getName('ar') . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('clients.index'), route('clients.create'));
    }

    public function edit(Client $client)
    {
        return view(
            'admin.clients.crud',
            $this->getViewUrl($client),
        );
    }

    public function update(StoreClientRequest $request, Client $client)
    {
        $client->syncFromRequest($request);

        Notification::storeNewAdminNotification(
            __('New Update', [], 'en'),
            __('New Update', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'en') . __('Client', [], 'en') . ' [ ' . $client->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Update', [], 'ar') . __('Client', [], 'ar') . ' [ ' . $client->getName('ar') . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('clients.index'), route('clients.edit', ['client' => $client->id]));
    }

    public function destroy(Request $request, Client $client)
    {
        $client->delete();
        Notification::storeNewAdminNotification(
            __('New Deletion', [], 'en'),
            __('New Deletion', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'en').' ' . __('Client', [], 'en') . ' [ ' . $client->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'ar').' ' . __('Client', [], 'ar') . ' [ ' . $client->getName('ar') . ' ]',
        );

        return $this->getWebDeleteRedirectRoute();
    }

    public function toggleIsBanned(Request $request)
    {
        $client = Client::find($request->id);
        if ($client) {
            $banMessageEn = $client->isBanned() ? __('Has Unbanned', [], 'en') : __('Has Banned', [], 'en') ;
            $banMessageAr = $client->isBanned() ? __('Has Unbanned', [], 'ar') : __('Has Banned', [], 'ar') ;

            $client->isBanned() ? $client->unban() : $client->ban([
                'comment' => $request->get('comment')
            ]) ;

            Notification::storeNewAdminNotification(
                __('New Update', [], 'en'),
                __('New Update', [], 'ar'),
                $request->user('admin')->getName() . ' ' . $banMessageEn . __('Client', [], 'en') . ' [ ' . $client->getName('en') . ' ]',
                $request->user('admin')->getName() . ' ' . $banMessageAr . __('Client', [], 'ar') . ' [ ' . $client->getName('ar') . ' ]',
            );
        }

        return redirect()->back()->with('success', __('The Action Has Been Done'));
    }

    public function toggleIsVerified(Request $request)
    {
        $client = Client::find($request->id);
        if ($client) {
            $verifiedMessageEn = $client->getIsVerified() ? __('Has UnVerified', [], 'en') : __('Has Verified', [], 'en') ;
            $verifiedMessageAr = $client->getIsVerified() ? __('Has UnVerified', [], 'ar') : __('Has Verified', [], 'ar') ;

            $client->toggleIsVerified();

            Notification::storeNewAdminNotification(
                __('New Update', [], 'en'),
                __('New Update', [], 'ar'),
                $request->user('admin')->getName() . ' ' . $verifiedMessageEn . __('Client', [], 'en') . ' [ ' . $client->getName('en') . ' ]',
                $request->user('admin')->getName() . ' ' . $verifiedMessageAr . __('Client', [], 'ar') . ' [ ' . $client->getName('ar') . ' ]',
            );
        }

        // return redirect()->back()->with('success',__('The Action Has Been Done'));
        return response()->json([
            'status' => true,
            'id' => $request->id,
        ]);
    }
}
