<?php

namespace App\Models;

use App\Enum\TravelStatus;
use App\Helpers\HHelpers;
use App\Http\Requests\StoreEmergencyContactRequest;
use App\Models\Client;
use App\Services\PhoneNumberService;
use App\Services\SMS\SmsService;
use App\Services\Whatsapp\WhatsappService;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasCountry;
use App\Traits\Models\HasEmail;
use App\Traits\Models\HasPhone;
use App\Traits\Models\HasSingleName;
use App\Traits\Scope\HasDefaultOrderScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
* * جهات اتصال الطوارئ بمعني لو انت باعت ابنك مثلا مع سواق فا انت عايز بمجرد ما الرحله
** تبدا انه يوصلك لينك او ويب فيو بحيث تتابع مسار الرحله من علي الخريطه بحيث تكون شايف كل حاجه قدامك
** سواء للعميل او حتى للسواق بمعني ان العميل والسواق هيكون ليهم جهات اتصال طوارئ
 */
class EmergencyContact extends Model
{
    use  IsBaseModel,HasDefaultOrderScope,HasFactory,HasPhone,HasEmail,HasSingleName,HasCountry;

	
    public function syncFromRequest($request)
    {
        if ($request->has('name')) {
            $this->name = $request->name;
        }
        if ($request->has('phone')) {
            $this->phone = $request->phone;
        }
		if ($request->has('email')) {
            $this->email = $request->email;
        }
		if ($request->has('country_id')) {
            $this->country_id = $request->country_id;
        }

        $this->save();
		return $this ;
    }
	/**
	 * * to get drivers 
	 */
	public function drivers()
	{
		return $this->belongsToMany(Driver::class , 'model_emergency_contact','emergency_contact_id','model_id')
		->where('model_type','Driver')
		->withTimestamps()
		;
	}
	/**
	 * * to get clients 
	 */
	public function clients()
	{
		return $this->belongsToMany(Client::class , 'model_emergency_contact','emergency_contact_id','model_id')
		->where('model_type','Client')
		->withTimestamps()
		;
	}
	
	public function country():BelongsTo
	{
		return $this->belongsTo(Country::class,'country_id');
	}
	public static function sync( $driverOrClient,int $emergencyContactId,bool $canReceiveTravelInfo,bool $addEmergencyContactFromExisting):self
	{
        $emergencyContact = EmergencyContact::find($emergencyContactId);
        /**
         * * add emergency contact from existing emergency contact id
         */
        if ($addEmergencyContactFromExisting) {
            $driverOrClient->emergencyContacts()->syncWithoutDetaching([
                $emergencyContactId => [
                    'model_type' => HHelpers::getClassNameWithoutNameSpace($driverOrClient),
                    'can_receive_travel_info' => $canReceiveTravelInfo
                ]
            ]);
        } else {
            /**
             * * add new emergency contact add assign it to the driver or client
             */
            $emergencyContactRequest = new StoreEmergencyContactRequest() ;
            $validator = Validator::make(Request()->all(), $emergencyContactRequest->rules(), $emergencyContactRequest->messages());
            if ($validator->fails()) {
                return response()->json([
                    'status' => Response::HTTP_BAD_REQUEST,
                    'message' => $validator->errors()->first()
                ], Response::HTTP_BAD_REQUEST);
            }
            $emergencyContact = new EmergencyContact();
            $emergencyContact = $emergencyContact->syncFromRequest(Request());
            $driverOrClient->emergencyContacts()->syncWithoutDetaching([
                $emergencyContact->id => [
                    'model_type' => HHelpers::getClassNameWithoutNameSpace($driverOrClient),
                    'can_receive_travel_info' => $canReceiveTravelInfo
                ]
            ]);
        }
		
		return $emergencyContact ;
	}
	public function canReceiveTravelInfo():bool 
	{
		return is_null($this->pivot) ? throw new \Exception('Custom Exception .. Pivot Not Loaded') : $this->pivot->can_receive_travel_info;
	}
	/**
	 * * هنا هننشئ رسالة جهه اتصال الطوارئ بناء علي حاله الرحلة الحاليه هل هي طلعت ولا انتهت ولا اتكنسلت
	 */
	public function generateMessage(int $travelId , ?Carbon $expectedArrivalDate , string $travelStatus, string $fullName):?string 
	{
		$message = null;
		if($travelStatus === TravelStatus::ON_THE_WAY && $expectedArrivalDate && $expectedArrivalDate->lessThan(now()->addMinutes(Travel::TRAVEL_ARRIVAL_LATE_MINUTE)) ){
			/**
			 * * هنا المفروض نبعت لينك للماب مباشر بحيث جهه الاتصال تعرف مكان الرحلة فين حاليا علي الخريطة
			 */
			$travel = Travel::getFromTravelId($travelId);
			return __('Hi :name , Travel Number #:travelId Still In The Road || From :fromAddress To :toAddress' ,['name'=>$fullName ,'travelId'=>$travelId,'fromAddress'=> $travel->getFromAddress() , 'toAddress'=>$travel->getToAddress() ]);
		}
		elseif($travelStatus === TravelStatus::ON_THE_WAY){
			return __('Hi :name , Travel Number #:travelId Started Now' ,['name'=>$fullName ,'travelId'=>$travelId]);
		}
		elseif($travelStatus === TravelStatus::CANCELLED){
			return __('Hi :name , Travel Number #:travelId Has Been Cancelled' ,['name'=>$fullName ,'travelId'=>$travelId]);
		}
		elseif($travelStatus === TravelStatus::COMPLETED){
			return __('Hi :name , Travel Number #:travelId Completed' ,['name'=>$fullName ,'travelId'=>$travelId]);
		}
		return $message ; 
	}
	/**
	 * * هنا هنبعت رسالة ان الرحلة مثلا طلعت او اتكنسلت او او انتهت .. الخ
	 */
	public function sendNewStatusMessage(int $travelId , ?Carbon $expectedArrivalDate ,string $travelStatus,string $countryIso2,string $phone, string $fullName = null, bool $viaSms = true, bool $viaWhatsapp = true): array
    {
		
        $message = $this->generateMessage($travelId,$expectedArrivalDate,$travelStatus, $fullName);
		if(!$message){
			return [];
		}

        $phoneFormatted = App(PhoneNumberService::class)->formatNumber($phone, $countryIso2);
        if ($viaSms) {
            $responseArray = (new SmsService())->send($phone, $countryIso2, $message);
            if ($responseArray['status'] && $responseArray['status']) {
                return [
                    'status' => true,
                    'message' => __('Verification Code Has Been Sent To Your Phone Number',[],getApiLang())
                ];
            }
        }
        if ($viaWhatsapp) {
            $responseArray = App(WhatsappService::class)->sendMessage($message, $phoneFormatted);
            if ($responseArray['status']) {
                return [
                    'status' => true,
                    'message' => __('Verification Code Has Been Sent To Your Whatsapp',[],getApiLang())
                ];
            }
        }
        return [
            'status' => false,
            'message' => isset($responseArray['message']) ? __('Fail To Send Verification Code',[],getApiLang()) . ' ' . $responseArray['message'] : __('Fail To Send Verification Code',[],getApiLang())
        ];
    }
	
}
