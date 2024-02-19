<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmergencyContactRequest;
use App\Models\Country;
use App\Models\Driver;
use App\Models\EmergencyContact;
use App\Models\Notification;
use App\Traits\Controllers\Globals;
use Illuminate\Http\Request;


class EmergencyContactsController extends Controller
{
    use Globals;

    public function __construct()
    {
        $this->middleware('permission:' . getPermissionName('view'), ['only' => ['index']]) ;
        $this->middleware('permission:' . getPermissionName('create'), ['only' => ['create', 'store']]) ;
        $this->middleware('permission:' . getPermissionName('update'), ['only' => ['edit', 'update']]) ;
        $this->middleware('permission:' . getPermissionName('delete'), ['only' => ['destroy']]) ;
    }

    public function index()
    {
        $models = EmergencyContact::defaultOrdered()->paginate(static::DEFAULT_PAGINATION_LENGTH_FOR_ADMIN);

        return view('admin.emergency-contacts.index', [
            'models' => $models,
            'pageTitle' => __('Emergency Contacts'),
            'createRoute' => route('emergency-contacts.create'),
            'editRouteName' => 'emergency-contacts.edit',
            'deleteRouteName' => 'emergency-contacts.destroy'
        ]);
    }

    public function create()
    {
        return view('admin.emergency-contacts.crud', $this->getViewUrl());
    }

    public function getViewUrl($model = null): array
    {
        $breadCrumbs = [
            'dashboard' => [
                'title' => __('Dashboard'),
                'route' => route('dashboard.index'),
            ],
            'emergency-contacts' => [
                'title' => __('Emergency Contacts'),
                'route' => route('emergency-contacts.index'),
            ],
            'create-emergency-contact' => [
                'title' => __('Create :page', ['page' => __('Emergency Contact')]),
                'route' => '#'
            ]
        ];

        return [
            'breadCrumbs' => $breadCrumbs,
            'pageTitle' => __('Emergency Contacts'),
            'route' => $model ? route('emergency-contacts.update', ['emergency_contact' => $model->id]) : route('emergency-contacts.store'),
            'model' => $model,
            'indexRoute' => route('emergency-contacts.index'),
            'countriesFormattedForSelect' => Country::get()->formattedForSelect(true, 'getId', 'getName')
        ];
    }

    public function store(StoreEmergencyContactRequest $request)
    {
        $model = new EmergencyContact();
        $model->syncFromRequest($request);
        Notification::storeNewNotification(
            __('New Creation', [], 'en'),
            __('New Creation', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'en') . __('Emergency Contact', [], 'en') . ' [ ' . $model->getName() . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'ar') . __('Emergency Contact', [], 'ar') . ' [ ' . $model->getName() . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('emergency-contacts.index'), route('emergency-contacts.create'));
    }

    public function edit(EmergencyContact $emergency_contact)
    {
        return view(
            'admin.emergency-contacts.crud',
            $this->getViewUrl($emergency_contact),
        );
    }

    public function update(StoreEmergencyContactRequest $request, EmergencyContact $emergency_contact)
    {
        $emergency_contact->syncFromRequest($request);

        Notification::storeNewNotification(
            __('New Update', [], 'en'),
            __('New Update', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Updated', [], 'en') . __('Emergency Contact', [], 'en') . ' [ ' . $emergency_contact->getName() . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Updated', [], 'ar') . __('Emergency Contact', [], 'ar') . ' [ ' . $emergency_contact->getName() . ' ]',
        );

        return $this->getWebRedirectRoute($request, route('emergency-contacts.index'), route('emergency-contacts.edit', ['emergency_contact' => $emergency_contact->id]));
    }

    public function destroy(Request $request, EmergencyContact $emergency_contact)
    {
        $emergency_contact->delete();

        Notification::storeNewNotification(
            __('New Deletion', [], 'en'),
            __('New Deletion', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'en') . __('Emergency Contact', [], 'en') . ' [ ' . $emergency_contact->getName('en') . ' ]',
            $request->user('admin')->getName() . ' ' . __('Has Deleted', [], 'ar') . __('Emergency Contact', [], 'ar') . ' [ ' . $emergency_contact->getName('ar') . ' ]',
        );

        return $this->getWebDeleteRedirectRoute();
    }

    public function toggleCanReceiveTravelInfo(Request $request)
    {
        $modelType = $request->get('modelType') ; // Driver For Example
        $driverOrClient = ('\App\Models\\' . $modelType)::find($request->get('modelId'));
        $emergencyContactId = $request->get('id') ;
        $emergencyContact = EmergencyContact::find($emergencyContactId);
        if ($driverOrClient && $emergencyContact) {
            $driverOrClient->emergencyContacts()->updateExistingPivot($emergencyContact->id, [
                'can_receive_travel_info' => $request->boolean('is_active')
            ]);

            Notification::storeNewNotification(
                __('New Update', [], 'en'),
                __('New Update', [], 'ar'),
                $request->user('admin')->getName() . ' ' . __('Has Updated', [], 'en') . __('Emergency Contact', [], 'en') . ' [ ' . $emergencyContact->getName('en') . ' ] ' . __('For') . ' ' . $driverOrClient->getFullName(),
                $request->user('admin')->getName() . ' ' . __('Has Updated', [], 'ar') . __('Emergency Contact', [], 'ar') . ' [ ' . $emergencyContact->getName('ar') . ' ] ' . __('For') . ' ' . $driverOrClient->getFullName(),
            );
        }

        return response()->json([
            'status' => true,
            'id' => $request->id,
        ]);
    }

    /**
     * * اضافة في حالة لو مش موجود ولو موجود عدلة
     */
    public function attach(Request $request)
    {
		$modelId = $request->get('model_id');
		$modelType = $request->get('model_type');
		$driverOrClient = ('\App\Models\\' . $modelType)::find($modelId);
        $emergencyContact = EmergencyContact::sync($driverOrClient,$request->get('emergency_contact_id'),$request->boolean('can_receive_travel_info'),$request->has('from_existing_contact'));

        Notification::storeNewNotification(
            __('New Creation', [], 'en'),
            __('New Creation', [], 'ar'),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'en') . __('Emergency Contact', [], 'en') . ' [ ' . $emergencyContact->getName('en') . ' ] ' . __('To') . ' ' . $driverOrClient->getFullName(),
            $request->user('admin')->getName() . ' ' . __('Has Created New', [], 'ar') . __('Emergency Contact', [], 'ar') . ' [ ' . $emergencyContact->getName('ar') . ' ] ' . __('To') . ' ' . $driverOrClient->getFullName(),
        );

        return response()->json([
            'status' => true,
            'message' => __('msg.created_success'),
            'reloadCurrentPage' => true
        ]);
    }

    public function detach(Request $request)
    {
        $modelType = $request->get('model_type') ; // Driver For Example
        $driverOrClient = ('\App\Models\\' . $modelType)::find($request->get('model_id'));

        $emergencyContactId = $request->get('emergency_contact_id') ;
        $emergencyContact = EmergencyContact::find($emergencyContactId);
        if ($driverOrClient && $emergencyContactId) {
            $driverOrClient->emergencyContacts()->detach($emergencyContactId);
            Notification::storeNewNotification(
                __('New Deletion', [], 'en'),
                __('Has Detached', [], 'ar'),
                $request->user('admin')->getName() . ' ' . __('Has Detached', [], 'en') . __('Emergency Contact', [], 'en') . ' [ ' . $emergencyContact->getName('en') . ' ] ' . __('To') . ' ' . $driverOrClient->getFullName(),
                $request->user('admin')->getName() . ' ' . __('Has Detached', [], 'ar') . __('Emergency Contact', [], 'ar') . ' [ ' . $emergencyContact->getName('ar') . ' ] ' . __('To') . ' ' . $driverOrClient->getFullName(),
            );

            return response()->json([
                'status' => true,
                'message' => __('Detached Successfully'),
                'reloadCurrentPage' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => __('Item Not Found'),
            'reloadCurrentPage' => true
        ]);
    }
}
