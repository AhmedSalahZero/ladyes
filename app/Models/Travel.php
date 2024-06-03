<?php

namespace App\Models;

use App\Enum\AppNotificationType;
use App\Enum\DeductionType;
use App\Enum\PaymentStatus;
use App\Enum\PaymentType;
use App\Enum\TravelStatus;
use App\Exceptions\TravelEndTimeNotFoundException;
use App\Exceptions\TravelStartTimeNotFoundException;
use App\Helpers\HDate;
use App\Helpers\HHelpers;
use App\Helpers\HStr;
use App\Http\Resources\TravelResource;
use App\Jobs\SendCurrentStatusMessageToEmergencyContractsJob;
use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use App\Services\PhoneNumberService;
use App\Services\Whatsapp\WhatsappService;
use App\Traits\Models\HasBasicStoreRequest;
use App\Traits\Models\HasCity;
use App\Traits\Models\HasCountry;
use App\Traits\Models\HasCreatedAt;
use App\Traits\Models\HasStartedAtAndEndedAt;
use App\Traits\Models\HasStopPointLatitudesAndLongitudes;
use App\Traits\Scope\HasDefaultOrderScope;
use App\Traits\Scope\TravelScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class Travel extends Model
{
    use HasFactory;
    use HasBasicStoreRequest;
    use HasDefaultOrderScope;
	use TravelScope ;
    use HasCreatedAt;
    use HasStartedAtAndEndedAt;
    use HasCity;
    use HasCountry;
	use HasStopPointLatitudesAndLongitudes;

    protected $table = 'travels';
	protected $dates = [
		'expected_arrival_date',
	];
	protected $casts = [
		'stop_point_latitudes'=>'array',
		'stop_point_longitudes'=>'array'
	];
	protected static function boot()
    {
        parent::boot();
        static::saving(function(Travel $travel) {
            $travel->is_secure = $travel->client->getHasSecureTravel();
			if($travel->is_secure){
				$travel->storeSecureCode();
			}
        }); 
    }
	
	/**
	 * * عدد الدقايق اللي لو الرحلة اتاخرت عنها هنبعت رسالة لجه اتصال الطوارئ
	 */
	const TRAVEL_ARRIVAL_LATE_MINUTE = 10 ;
    public function getId()
    {
        return $this->id ;
    }
	public static function getFromTravelId(int $travelId):?self
	{
		return self::where('id',$travelId)->first();
	}
	public function getDriverPhoneNumber():string 
	{
		$driver  = $this->driver ;
		return  $driver ? $driver->getPhone() : __('N/A',[],getApiLang());
	}
	public function getDriverCarIdNumber():string 
	{
		$driver  = $this->driver ;
		return  $driver ? $driver->getCarIdNumber() : __('N/A',[],getApiLang());
	}
    public function client(): ?BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function getClientName(): string
    {
        $client = $this->client ;

        return  $client ? $client->getName() : __('N/A');
    }

    public function driver(): ?BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    public function getDriverName(): string
    {
        $driver = $this->driver ;

        return  $driver ? $driver->getName() : __('N/A');
    }

    public function getDriverId(): ?Int
    {
        $driver = $this->driver ;

        return  $driver ? $driver->id : 0;
    }

    /**
     * * دا الكوبون اللي بيتم انشائة تلقائي بحيث لما الرحلة تنتهي بيظهر للعميل كهديه بحيث يستخدمة في الرحلات القادمة
     * * وبيكون غير محدود بوقت ويمكن استخدامة لمرة واحدة فقط
     */
    public function giftCoupon(): ?BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'gift_coupon_id', 'id');
    }

    public function getGiftCouponCode()
    {
		if($this->giftCoupon){
			return $this->giftCoupon->code ;
		}
		return $this->generateGiftCoupon()->getCode() ;
    }

    public function generateGiftCoupon(): Coupon
    {
        $giftCoupon = Coupon::generateGiftCouponForTravel($this->id, $this->calculateGiftCouponDiscountAmount());
        $this->gift_coupon_id = $giftCoupon->id ;
        $this->save();
		return $giftCoupon ;
    }

    /**
     * * دا عباره عن نسبة الخصم اللي هياخدها الكوبون اللي بيتم انشائه لما الرحلة تنتهي
     */
    public function calculateGiftCouponDiscountAmount(): ?float
    {
        $percentage = getSetting('coupon_discount_percentage') / 100;
        return $percentage * $this->calculateClientActualPriceWithoutDiscount();
    }

    /**
     * * دا الكوبون اللي تم تطبيقه علي الرحلة الحالية
     */
    public function coupon(): ?BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

	 /**
     * * دا الكوبون اللي تم تطبيقه علي الرحلة الحالية
     */
    public function promotion(): ?BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', 'id');
    }
	
    public function applyCoupon(int $couponId): float
    {
        /**
         * @var Coupon $coupon;
         */
        $coupon = Coupon::find($couponId);
        $this->coupon_id = $couponId ;
        $this->save();

        return  $coupon->getDiscountAmount();
    }

    /**
     * * هل الرحلة أمنة  ؟ لان لو الرحلة امنة بنتقوم بانشاء كود خاص بيها
     * *  لو امنة بننشئ كود بحيث السواق يقوله للعميل علشان يتاكد من هويته
     * * قصدي هوية السائق
     */

    public function getIsSecure()
    {
        return (bool) $this->is_secure ;
    }

    public function getIsSecureFormatted()
    {
        $isSecure = $this->getIsSecure();

        return $isSecure ? __('Yes') : __('No');
    }

    /**
     ** is_secure رمز الرحلة الامنة ويتم انشائه تلقائي في حالة لو تم تفعيل خيار الرحلة الامنه
     **/
    public function getSecureCode()
    {
        return $this->secure_code ;
    }

    /**
     * * انشاء رمز الرحلة الامنة
     */
    public function generateSecureCode()
    {
        return HStr::generateRandomString(5);
    }

    public function storeSecureCode(): self
    {
        $this->secure_code = $this->generateSecureCode() ;
        // $this->save();

        return $this ;
    }

    public function syncFromRequest(Request $request): self
    {
        $travel = $this->storeBasicForm(
            $request,
            ['_token', 'save', '_method', 'coupon_code']
        );

        // $country = Country::find($request->get('country_id'));
        /**
         * @var Country $country
         */

        // $request->boolean('is_secure') ? $this->storeSecureCode() : null ;
        $request->has('coupon_code') ? $travel->applyCoupon(Coupon::findByCode($request->get('coupon_code'))->id) : 0;
		$travel->applyPromotion();
        /**
         * * دا الكوبون اللي تم تطبيقه علي الرحلة الحالية
         */

        return $travel;
    }

    /**
     * * هنا هنحدد ان الرحلة انتهت وخلي بالك ان عميله الدفع منفصلة عن تحديد الرحلة كمنتهيه
     */
    public function markAsCompleted(Request $request)
    {
        $this->status = TravelStatus::COMPLETED;
        $this->ended_at = now();
        $this->save();
		dispatch(new SendCurrentStatusMessageToEmergencyContractsJob($this));
		$this->driver ? $this->driver->handleCompletedTravelsMedal() : null;
		Notification::storeNewAdminNotification(
			__('Ride Completed',[],'en'),
			__('Ride Completed',[],'ar'),
			__('Ride Number :travelId Has Been Completed At :dateTimeFormatted' , ['travelId'=>$this->getId(),'dateTimeFormatted'=>HDate::formatForView(now()->format('Y-m-d'))] , 'en'),
			__('Ride Number :travelId Has Been Completed At :dateTimeFormatted' , ['travelId'=>$this->getId(),'dateTimeFormatted'=>HDate::formatForView(now()->format('Y-m-d'))] , 'ar'),
		);
        return $this;
    }
	/**
	 * * هنا الرسالة اللي هنبعتها للعميل بعدن نهاية الرحلة بحيث يقيم الرحلة ونبعتله كود خصم يشاركة مع زملائه
	 */
	public function getRatingAndLinksForClientMessage():string 
	{
		$basicMessage = getSetting('travel_end_message_'.getApiLang()) ;
		$googleLink = getSetting('app_link_on_google_play');
		$couponCode = $this->getGiftCouponCode();
		$message =$basicMessage . '  ' . __('Coupon Code') . ':' . $couponCode . '  '  . __('App Link') . ':' . $googleLink  ;
		return  $message;
	}
	public function sendTravelCompletedMessageForClient():void
	{
		if(!$this->client){
			return ;
		}
		$phone = $this->client->getPhone();
		$countryIso2 = $this->client->getCountyIso2();
		$phoneFormatted = App(PhoneNumberService::class)->formatNumber($phone, $countryIso2);
		$message = $this->getRatingAndLinksForClientMessage();
		App(WhatsappService::class)->sendMessage($message, $phoneFormatted);
	}
    /**
     * * هنا هنحدد ان الرحلة بدات
     */
    public function markAsStarted(Request $request)
    {
		$now = now() ;
        $this->status = TravelStatus::ON_THE_WAY;
        $this->started_at = $now;
		dispatch(new SendCurrentStatusMessageToEmergencyContractsJob($this));
		$fromLatitude = $this->getFromLongitude();
		$fromLongitude = $this->getFromLatitude();
		$toLatitude = $this->getToLatitude();
		$toLongitude = $this->getToLongitude();
		$inRushHor = $this->isInRushHour(); 
		if($inRushHor){
			$this->driver->addNewRushHorLog($now);
		}
		$googleDistanceMatrixService = new GoogleDistanceMatrixService();
		$result = $googleDistanceMatrixService->getExpectedArrivalTimeBetweenTwoPoints($fromLatitude,$fromLongitude,$toLatitude,$toLongitude);   
		if(isset($result['duration_in_seconds']) && $result['duration_in_seconds'] > 0){
			$minutes = $result['duration_in_seconds'] / 60 ;
			$this->expected_arrival_date = now()->addMinutes($minutes);
			$this->no_km = $result['distance_in_meter'] / 1000 ;
		}else{
			$minutes = 100 / 60 ;
			$this->expected_arrival_date = now()->addMinutes($minutes);
		}
		
		Notification::storeNewAdminNotification(
			__('Ride Started',[],'en'),
			__('Ride Started',[],'ar'),
			__('Ride Number :travelId Has Been Started At :dateTimeFormatted' , ['travelId'=>$this->getId(),'dateTimeFormatted'=>HDate::formatForView(now()->format('Y-m-d'))] , 'en'),
			__('Ride Number :travelId Has Been Started At :dateTimeFormatted' , ['travelId'=>$this->getId(),'dateTimeFormatted'=>HDate::formatForView(now()->format('Y-m-d'))] , 'ar'),
		);
		
        $this->save();

        return $this;
    }

    /**
     * * هنا هنبعت رسالة لجهات اتصال الطوارئ الرحلة بدات ولا انتهت ولا اتكنسلت
     */
    public function sendCurrentStatusMessageToEmergencyContracts(): self
    {
        $client = $this->client ;
        $driver = $this->driver ;
	
        if ($client) {
	
            $client->emergencyContacts()->each(function (EmergencyContact $emergencyContact) use ($client) {
                $canReceiveTravelInfo = $emergencyContact->canReceiveTravelInfo();
                if ($canReceiveTravelInfo) {
                    $emergencyContact->sendNewStatusMessage($this->getId(),$this->getExpectedArrivalDate(), $this->getStatus(), $this->getCountryIso2(), $client->getPhone(), $emergencyContact->getName());
                }
            });
        }

        if ($driver) {
            $driver->emergencyContacts()->each(function (EmergencyContact $emergencyContact) use ($driver) {
                $canReceiveTravelInfo = $emergencyContact->canReceiveTravelInfo();
                if ($canReceiveTravelInfo) {
                    $emergencyContact->sendNewStatusMessage($this->getId(),$this->getExpectedArrivalDate(), $this->getStatus(), $this->getCountryIso2(), $driver->getPhone(), $emergencyContact->getName());
                }
            });
        }

        return $this ;
    }

	/**
	 * * كل خمس دقايق هنبعتلة الاشعارات الاتيه ( العميل)
	** اذا احتجت لشئ اضغط علي زرار  الطوارئ
	** اشعار باقي علي وصل رحتلك عشر دقايق مثلا 
	** تكلفه الرحلة حتى الان 
	*/
    public function SendTravelNotificationsToClients(): self
    {
        $client = $this->client ;
	
		$driver = $this->driver ; 
		/**
		 * * اول رساله هنبعتها لو احتاجت اي شئ اضغط علي زرار الطزارئ
		 */
		$client
		->sendAppNotification(
			__('Be Aware',[],'en'),
			__('Be Aware',[],'ar'),
			__('If You Need AnyThing Press Emergency Button',[],'en'),
			__('If You Need AnyThing Press Emergency Button',[],'ar'),
			AppNotificationType::INFO
	);
		
		
	
		
		$currentDriverLatitude = $driver->getLatitude();
		$currentDriverLongitude = $driver->getLongitude();
		$travelEndPointLatitude = $this->getToLatitude();
		$travelEndPointLongitude = $this->getToLongitude();
		
		$googleDistanceMatrixService = new GoogleDistanceMatrixService ;
		$expectedArrivalTimeAndDistanceArr = $googleDistanceMatrixService->getExpectedArrivalTimeBetweenTwoPoints($currentDriverLatitude ,$currentDriverLongitude ,$travelEndPointLatitude,$travelEndPointLongitude);
		$expectedArrivalDurationInSeconds = $expectedArrivalTimeAndDistanceArr['duration_in_seconds'];
		$client->sendAppNotification(
			__('Expected Time Remaining',[],'en') ,
			__('Expected Time Remaining',[],'ar') ,
			__('Estimated Arrival Time :time',['time'=>$expectedArrivalDurationInSeconds ? now()->addSeconds($expectedArrivalDurationInSeconds)->format('g:i A') : '-' ]),
			__('Estimated Arrival Time :time',['time'=>$expectedArrivalDurationInSeconds ? now()->addSeconds($expectedArrivalDurationInSeconds)->format('g:i A') : '-']),
			AppNotificationType::INFO
		);
		
		/**
		 * * بما ان الرحلة لسه في الطريق فا هو هيحسب التكلفه بناء علي موقع السواق الحالي وبفرض 
		 * * ان تاريخ نهايتها هو دلوقت
		 */
		$totalPrice = $this->getTravelPriceDetails()['total_price'];
		
		$client->sendAppNotification(
			__('Total Price Until Now',[],'en') ,
			__('Total Price Until Now',[],'ar') ,
			__('Total Price Until Now') . ' ' . $totalPrice . ' ' . $this->getCurrencyNameFormatted('en'),
			__('Total Price Until Now') . ' ' . $totalPrice . ' ' . $this->getCurrencyNameFormatted('ar'),
			AppNotificationType::INFO
		);
		
        return $this ;
    }
	
    public function getPaymentMethod(): string
    {
        return $this->payment_method ;
    }

    public function updatePaymentMethod(string $paymentMethod)
    {
        $this->payment_method = $paymentMethod;
        $this->save();

        return $this ;
    }

    public function storePayment(Request $request)
    {
        /**
         * @var City $city ;
         */

        /**
         * * store new payment
         * @var Payment $payment
         */

        (new Payment())->storeForTravel($this);

        return $this;
    }

    /**
     * * حالة الرحلة هل تم الغائها ام اكتملت ام هل لم تبدا بعد هي الان علي الطريق الخ
     */
    public function getStatus(): string
    {
        /**
         * * visit TravelStatus::class for all possible values
         */
        return $this->status ?:TravelStatus::NOT_STARTED_YET;
    }

    public function getStatusFormatted()
    {
        $travelStatus = TravelStatus::all();

        return $travelStatus[$this->getStatus()] ?? __('N/A');
    }

    public function isCompleted()
    {
        return $this->getStatus() == TravelStatus::COMPLETED;
    }
	public function isOnTheWay()
    {
        return $this->getStatus() == TravelStatus::ON_THE_WAY;
    }
    public function isCancelled(): bool
    {
        return $this->getStatus() == TravelStatus::CANCELLED;
    }

    public function getOperationalFees(?string $startedAt=null,?City $city = null )
    {
        $startedAt = is_null($startedAt) ? $this->getStartedAt() : $startedAt;
        $city = is_null($city) ? $this->getCity() : $city ;
        $priceModel  = $this->getPriceModel($startedAt,$city );
        return $priceModel->getOperatingFeesPrice();
    }

    /**
     * * هي رسوم بيدفعها العميل لو اختار طريق الدفع كاش
     */
    public function calculateCashFees()
    {
        $priceModel = $this->getPriceModel();
		if(!$priceModel){
			return 0;
		}

        return $priceModel->getCashFees();
    }

    public function calculateTaxAmount(float $mainPriceWithoutDiscountAndTaxesAndCashFees = null,  $couponAmount = 0,$cashFees = 0 , $promotionAmount= 0 , $totalFines = 0 )
    {
        $mainPriceWithoutDiscountAndTaxesAndCashFees = is_null($mainPriceWithoutDiscountAndTaxesAndCashFees) ? $this->calculateClientActualPriceWithoutDiscount() : $mainPriceWithoutDiscountAndTaxesAndCashFees;
        $country = $this->getCountry() ;
        if (!$country) {
            return 0 ;
        }
        $taxPercentage = $country->getTaxesPercentage() / 100 ;
		
        return $taxPercentage * ($mainPriceWithoutDiscountAndTaxesAndCashFees - $promotionAmount - $couponAmount + $cashFees + $totalFines) ;
    }

    public function calculateTaxesAmount()
    {
        $country = $this->getCountry() ;
        if (!$country) {
            return 0 ;
        }

        return $country->getTaxesPercentage() / 100 ;
    }

    /**
     * * تحتوي علي بيانات الدفع وليكن مثلا نوع عمليه الدفع كاش مثلا او من المحفظة والمقدار و والغرمات وقيمة الكوبون ان وجد الخ
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'travel_id', 'id');
    }

    /**
     * * لحساب تكلفة الرحلة الاساسية الفعليه اي بعد انتهاء الرحلة ومعرفتنا بعدد الدقائق التي استغرقتها وكذلك عدد الكيلوا متر
     * * وهنا هو عباره عن
     * *       اجرة المدينه الاساسية + عدد الكيلو متر الفعليه التي تم قطعها * سعر الكيلوا للمدينه + عدد الدقائق المقطوعه الفعليه *سعر الدقيقة للمدينة
     * ! ملحوظه :- لحساب الخصم الخاص بالكوبون اللي بننشئة بعد اكتمال الرحلة بنستخدم هذه الفانكشن
     * ! لانة بيكون نسبة وليكن مثلا عشره في المية مضروبة في هذا الرقم .. هذه النسبه موجوده في الاعدادات
     * * لاحظ ايضا هذا السعر لا يشمل رسوم التشغيل
     * @see $mainFare الاجرة الاساسية
     */
    public function calculateClientActualPriceWithoutDiscount(?string $startedAt = null , ?City $city = null , ?Driver $driver = null,$numberOfMinutes = null,$numberOfKms=null)
    {
        /**
         * @var City $city
         */

		$driver = is_null($driver) ?  $this->driver : $driver  ; 
        $startedAt = is_null($startedAt ) ?   $this->getStartedAt() : $startedAt ;
        $city = is_null($city) ? $this->getCity()  : $city  ;
        $priceModel = $this->getPriceModel($startedAt , $city);
        $carSizePrice = $driver->carSize->getPrice($city->getCountryId(), getApiLang()) ;
        $kmPrice = $priceModel->getKmPrice();
        $minutePrice = $priceModel->getMinutePrice();
        $operationFees = $this->getOperationalFees($startedAt,$city);
        $numberOfMinutes = is_null($numberOfMinutes) ? $this->getNumberOfMinutes() : $numberOfMinutes;
        $numberOfKms = is_null($numberOfKms) ? $this->getNumberOfKms() : $numberOfKms;
        return $carSizePrice + $operationFees  + ($kmPrice * $numberOfKms) + ($minutePrice * $numberOfMinutes)  ;
    }
	/**
	 * * حساب القيمة الفعليه للعرض الترويجي
	 */
	public function calculatePromotionAmount($mainPriceWithoutDiscountAndTaxesAndCashFees,$couponAmount,$cashFees,$totalFines)
	{
		$promotionPercentage = $this->getPromotionPercentage() / 100;
		return ($mainPriceWithoutDiscountAndTaxesAndCashFees + $cashFees - $couponAmount  + $totalFines) * $promotionPercentage;
	}

    /**
     * * هو نفس الحسبة السابقة مضاف اليها الغرامات ومنقوص منها الخصم مضاف اليها الضريبة .. وهو اجمالي ما سوف يتم دفعه للعميل
     */
    public function calculateClientTotalActualPrice($mainPriceWithoutDiscountAndTaxesAndCashFees,float $couponAmount = null, float $promotionAmount = null, float $taxesAmount = null, float $cashFees = null,$totalFines=0)
    {
        /**
         * * لو ما مررنهاش هنحسبها
         */
        // $promotionAmount = is_null($promotionAmount) ? $this->getPromotionAmount() : $promotionAmount ;
        $couponAmount = is_null($couponAmount) ? $this->getCouponDiscountAmount() : $couponAmount ;
        $taxesAmount = is_null($taxesAmount) ? $this->calculateTaxesAmount() : $taxesAmount ;
        $cashFees = is_null($cashFees) ? $this->calculateCashFees() : $cashFees ;
        return $mainPriceWithoutDiscountAndTaxesAndCashFees + $cashFees - $couponAmount - $promotionAmount + $totalFines + $taxesAmount    ;
    }

    /**
     * * النسبة اللي الابلكيشن هياخدها
     * * التطبيق هياخد
     * * سعر الرحلة الرئيسي - رسوم التشغيل لان السائق ملهوش دعوه بيها لانها فلوس سيرفرات .. الكل مضروب في نسبة الاستقطاع
     */
    public function calculateApplicationShare()
    {
		/**
		 * @var Driver $driver ;
		 */
		$driver = $this->driver ;
        $operationFees = $this->getOperationalFees();
		$promotionPercentage = $this->getPromotionPercentage();
		$deductionType = $driver->getDeductionType();
		$appShareBasic = ($this->calculateClientActualPriceWithoutDiscount() - $operationFees)  ;
		$appShareBasic = $appShareBasic - ($appShareBasic * $promotionPercentage / 100 );
		if($deductionType === DeductionType::PERCENTAGE){
			return  $appShareBasic - ($appShareBasic *  ($driver->getDeductionAmount() / 100))  ;
		}
		return $appShareBasic - $driver->getDeductionAmount() ;
    }

    /**
     * * النسبة اللي السائق هياخدها
     * * السائق هياخد
     * * سعر الرحلة الرئيسي - رسوم التشغيل - رسوم التطبيق
     */
    public function calculateDriverShare()
    {
        $operationFees = $this->getOperationalFees();

        return $this->calculateClientActualPriceWithoutDiscount() - $operationFees - $this->calculateApplicationShare()  ;
    }
	public function isInRushHour()
	{
		$statedAt = $this->getStartedAt();
        $city = $this->getCity() ;
        return  $city->isInRushHourAt($statedAt);
	}
	public function getPriceModel(?string $statedAt = null , ?City $city = null  )
	{
		$statedAt = is_null($statedAt) ? $this->getStartedAt() : $statedAt;
        $city = is_null($city) ?  $this->getCity() : $city ;
		if(!$statedAt){
			return $city ; 
		}
        $priceModel = $city;
        $rushHour = $city->isInRushHourAt($statedAt);
        if ($rushHour) {
            $priceModel = $rushHour ;
        }
		return $priceModel ;
	}
    public function getOperationFeesPrice()
    {
        
		$priceModel  = $this->getPriceModel();
        return  $priceModel->getOperatingFeesPrice();
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * * في حاله لو الرحله اكتملت هنجيب السعر الاساسي .. اي بدون خصومات او رسوم تشغيل .. هنجيب من المدفوعه
     */
    public function getPaymentPrice()
    {
        return $this->payment->getPrice() ?: 0 ;
    }

    public function getPaymentPriceFormatted()
    {
        $price = $this->getPaymentPrice();
        $currentName = $this->getCurrencyNameFormatted();

        return number_format($price) . ' ' . __($currentName);
    }

    /**
     * * في حاله لو الرحله اكتملت هنجيب رسوم التشغيل .. هنجيب من المدفوعه
     */
    public function getPaymentOperationalFees()
    {
        return $this->payment->getOperationalFees() ?: 0 ;
    }

    public function getPaymentOperationalFeesFormatted()
    {
        $operationalFees = $this->getPaymentOperationalFees();
        $currentName = $this->getCurrencyNameFormatted();

        return number_format($operationalFees) . ' ' . __($currentName);
    }

    /**
     * * في حاله لو الرحله اكتملت هنجيب قيمة الكوبون .. هنجيب من المدفوعه
     */
    public function getPaymentCouponDiscountAmount()
    {
        return $this->payment->getCouponDiscountAmount() ?: 0 ;
    }   
	
	/**
     * * في حاله لو الرحله اكتملت هنجيب قيمة العرض الترويجي .. هنجيب من المدفوعه
     */
    public function getPaymentPromotionPercentage()
    {
        return $this->payment->getPromotionPercentage() ?: 0 ;
    }

    public function getPaymentCouponDiscountAmountFormatted()
    {
        $couponDiscount = $this->getPaymentCouponDiscountAmount();
        $currentName = $this->getCurrencyNameFormatted();

        return number_format($couponDiscount) . ' ' . __($currentName);
    }
	public function getPaymentPromotionDiscountPercentageFormatted()
    {
        $paymentPromotionPercentage = $this->getPaymentPromotionPercentage();
		return $paymentPromotionPercentage . ' %'; 
    }
    public function getPaymentTotalPriceWithoutOperationFees()
    {
        return $this->getClientActualTotalPrice() + $this->getPaymentCouponDiscountAmount();
    }

    public function getPaymentTotalPriceWithoutOperationFeesFormatted()
    {
        $paymentTotalPriceWithoutOperationFees = $this->getPaymentTotalPriceWithoutOperationFees() ;
        $currentName = $this->getCurrencyNameFormatted();

        return number_format($paymentTotalPriceWithoutOperationFees) . ' ' . __($currentName);
    }

    public function getPaymentTotalPriceWithOperationFees()
    {
        return $this->getPaymentTotalPriceWithoutOperationFees() + $this->getPaymentOperationalFees();
    }

    public function getPaymentTotalPriceWithOperationFeesFormatted()
    {
        $paymentTotalPriceWithoutOperation = $this->getPaymentTotalPriceWithOperationFees() ;
        $currentName = $this->getCurrencyNameFormatted();

        return number_format($paymentTotalPriceWithoutOperation) . ' ' . __($currentName);
    }

    public function getCurrencyName()
    {
        $country = $this->getCountry() ;

        return $country ? $country->getCurrency() : '';
    }

    public function getCurrencyNameFormatted($lang = null)
    {
        $lang = $lang ? $lang : getApiLang();
        $country = $this->getCountry() ;

        return  $country ? __($country->getCurrency(), [], $lang) : '';
    }

    /**
     * * دي قيمة الكوبون الحاليه (اللي هنستخدمها عند تسجيل الرحله .. اما اللي تحتها هي القيمة القديمة اللي تم تسجيلها في الرحلة علشان لو
     * * لو حصل تغير في سعر الرحلة
     * )
     */
    public function getCouponDiscountAmount()
    {
        return  $this->coupon ? $this->coupon->getDiscountAmount() : 0 ;
    }

    /**
     * * دي القيمه الفعليه اللي تم استخدامها في الرحلة
     */
    public function getCouponDiscountAmountFormatted()
    {
        return $this->payment->getCouponDiscountAmountFormatted() . ' ' . $this->getCurrencyNameFormatted() ;
    }
	
	
	   /**
     * * دي قيمة العرض الترويجي الحالي (اللي هنستخدمها عند تسجيل الرحله .. اما اللي تحتها هي القيمة القديمة اللي تم تسجيلها في الرحلة علشان لو
     * * لو حصل تغير في سعر الرحلة
     * )
     */
    public function getPromotionPercentage()
    {
        return  $this->promotion ? $this->promotion->getPromotionPercentage() : 0 ;
    }

    /**
     * * دي القيمه الفعليه اللي تم استخدامها في الرحلة
     */
    public function getPromotionPercentageFormatted()
    {
        return $this->payment->getPromotionPercentageFormatted() . ' ' . $this->getCurrencyNameFormatted() ;
    }
	
	
	
    /**
     * * قيمة الغرامة لو الغى الرحلة مثلا
     */
    public function getFineAmount()
    {
        return $this->fine ? $this->fine->getAmount() : 0   ;
    }

    public function getFineAmountFormatted()
    {
        return $this->fine ? $this->fine->getAmountFormatted() . ' ' . $this->getCurrencyNameFormatted() : 0 ;
    }

    public function getFinePaymentMethod()
    {
        return $this->fine ? $this->fine->getTypeFormatted() : null;
    }

    public function getFinePaymentMethodFormatted()
    {
        $paymentMethod = PaymentType::all();

        return $this->fine ? $paymentMethod[$this->fine->getTypeFormatted()] : null;
    }

    public function getClientActualTotalPrice()
    {
		if(!$this->payment){
			return 0;
		}
        $mainPrice = $this->payment->getTotalPrice();
        $couponDiscount = $this->getCouponDiscountAmount() ;
        $fine = $this->getFineAmount();

        return $mainPrice - $couponDiscount - $fine ;
    }

    public function getClientActualTotalPriceFormatted()
    {
        $totalPrice = $this->getClientActualTotalPrice();
        $country = $this->getCountry() ;
        $currency = $country ? $country->getCurrency() : '';

        return number_format($totalPrice) . ' ' . __($currency);
    }
    public function getPaymentMethodFormatted()
    {
        return $this->payment->getTypeFormatted();
    }

    public function isCashPayment()
    {
        return $this->getPaymentMethod() === PaymentType::CASH;
    }

    public function getPaymentStatus()
    {
        return $this->payment->getStatus();
    }
	public function getPaymentStatusFormatted()
    {
        return PaymentStatus::all()[$this->getPaymentStatus()];
    }
    public function getCountry(): ?Country
    {
        $city = $this->city ;
        if ($city) {
            return $city->getCountry() ;
        }

        return  $this->country ;
    }

    public function getCountryIso2(): ?string
    {
        /**
         * @var Country $country ;
         */
        $iso2 = null ;
        $country = $this->getCountry();

        if ($country) {
            $iso2 = $country->getIso2();
        }

        return $iso2 ;
    }

    /**
     * * عدد الكيلوا مترات المقطوعه خلال كامل الرحلة
     */
    public function getNumberOfKms()
    {
		/**
		 * * في حالة لو الرحلة لسه في الطريق هحسب عدد الكيلومترز من اول المكان اللي الرحلة
		 * * طلعت منه لحد المكان الحالي للسواق 
		 */
		if($this->isOnTheWay()){
			$currentDriverLatitude = $this->driver->getLatitude();
			$currentDriverLongitude = $this->driver->getLongitude();
			$travelEndPointLatitude = $this->getToLatitude();
			$travelEndPointLongitude = $this->getToLongitude();
			$googleDistanceMatrixService = new GoogleDistanceMatrixService ;
			$expectedArrivalTimeAndDistanceArr = $googleDistanceMatrixService->getExpectedArrivalTimeBetweenTwoPoints($currentDriverLatitude ,$currentDriverLongitude ,$travelEndPointLatitude,$travelEndPointLongitude);
			$expectedArrivalDistanceInKm = $expectedArrivalTimeAndDistanceArr['distance_in_meter'] / 1000;
			return $expectedArrivalDistanceInKm ;
		}
        return $this->no_km ;
    } 
	/**
	 * * التاريخ المتوقع لوصول الرحلة
	 */
	public function getExpectedArrivalDate()
    {
        return $this->expected_arrival_date ;
    }

    /**
     * * عدد الدقائق المقطوعة المقطوعه خلال كامل الرحلة
     * * هو عباره عن الفرق بين الوقت اللي الرحلة انتهت عنده والوقت اللي الرحلة بدات عنده بالدقائق
     */
    public function getNumberOfMinutes()
    {
        $startedAt = $this->getStartedAt();
        $endedAt = $this->isOnTheWay() ? now() :  $this->getEndedAt()   ;
		/**
		 * * افترضنا ان التاريخ بتاع النهاية هو الان علشان لو بحسب التكلفه ولسه الرحلة ما انتهاش
		 * * علشان في اشعار بيتطلب انك كل عشر دقايق تطلع للعميل السعر لحد الان .. يعني بفرض ان تاريخ النهاية هو الان
		 */
        if (!$startedAt) {
            throw new TravelStartTimeNotFoundException(__('Travel Start Time Not Found', [], getApiLang()));
        }
        if (!$endedAt) {
            throw new TravelEndTimeNotFoundException(__('Travel End Time Not Found', [], getApiLang()));
        }

        return Carbon::make($startedAt)->diffInMinutes($endedAt);
    }

    /**
     * * هنا بنحسب نسبة الغرامة اللي المفروض تتطبق علي السائق او الكابتن
     * * في حال بدأت الرحلة وقام العميل باللغاء الرحلة يتم احتساب مبلغ ثابت لكل دقيقه ويتم التحكم في المبلغ هذا من لوحة التحكم الادارية والتي يكون حقل به مبلغ اللغاء العميل
     * * ويتم احتساب هذا المبلغ بناء على كل دقيقه تمت في هذة الرحلة
     * * كمثال في حال العميل طلب رحله وبعد 15 دقيقه رغب ان يلغي الرحلة
     * *يتم احتساب المبلغ مقابل 15 دقيقه ويتم اخذ نسبة التطبيق من المبلغ والباقي يذهب في محفظة الكابتن
     */
    public function calculateCancellationFees()
    {
        $cancellationFeesAmount = $this->getPriceModel()->getCancellationFeesForClient() ;
        return $this->getNumberOfMinutes() * $cancellationFeesAmount   ;
    }

    /**
     * * هل تم دفع تمن الرحلة ولا لسه
     */
    public function isPaid(): bool
    {
        $payment = $this->payment ;

        return $payment && $payment->getStatus() === PaymentStatus::SUCCESS ;
    }

    public function getClientId()
    {
        return $this->client_id ;
    }

    /**
     * * لو السائق هو من الغى فلا توجد غرامة
     * * لو العميل الغي بعد بداية الرحلة بينزل عليه غرامة
     */
    public function applyCancellationFine(Request $request)
    {
        $user = $request->user() ;

        $currencyNameEn = $this->getCurrencyNameFormatted('en');
        $currencyNameAr = $this->getCurrencyNameFormatted('ar');
        /**
         * * هنحسب قيمة الغرامة
         */
        $fineFeesAmount = $this->calculateCancellationFees();

        $hasBalanceInHisWallet = $user->getTotalWalletBalance() >= $fineFeesAmount;
        /**
         * @var Fine $fine
         */
        /**
         * * هنسجل الغرامة علي العميل
         */
        $fine = (new Fine())->storeForTravel($this, $fineFeesAmount);

        /**
         * *   هنقوم بتسديد الغرامة واعطاء السائق نصيبة و التطبيق نصيبة ودا في حالة لو العميل كان
         * * معاه مبلغ كافي في محفظتة
         */
        $fine->settlementTravelFee($fineFeesAmount);
    }

    /**
     * * تحديد ما اذا كانت الرحلة قد بدات بالفعل
     */
    public function hasStarted(): bool
    {
        return $this->started_at !== null  ;
    }

    /**
     * * تحديد ما اذا كانت الرحلة قد بدات بالفعل
     */
    public function hasEnded(): bool
    {
        return $this->ended_at !== null  ;
    }

    /**
     * * في حاله الغاء الرحلة .. مين اللي لغاءها العميل ولا السائق
     */
    public function getCancelledBy()
    {
        return $this->cancelled_by ;
    }
	
	public function getCancelledByFormatted():string 
	{
		$cancelledBy = $this->getCancelledBy() ;
		return $cancelledBy ? __($cancelledBy) : __('N/A');
	}

    public function isCancelledByClient()
    {
        return $this->getCancelledBy() === 'Client';
    }

    public function isCancelledByDriver()
    {
        return $this->getCancelledBy() === 'Driver';
    }
	public function cancellationReason()
	{
		return $this->belongsTo(CancellationReason::class,'cancellation_reason_id','id');
	}
	public function getCancellationReasonFormatted()
	{
		return $this->cancellationReason ? $this->cancellationReason->getName() : __('N/A'); 
	}
    public function markAsCancelled(Request $request)
    {
        $this->status = TravelStatus::CANCELLED;
        $this->ended_at = now();
        $this->cancelled_by = HHelpers::getClassNameWithoutNameSpace($request->user()) ;
		if($request->has('cancellation_reason_id')){
			$this->cancellation_reason_id = $request->get('cancellation_reason_id');
		}
        $this->save();
		
        if ($this->hasStarted() && $this->isCancelledByClient()) {
            $this->applyCancellationFine($request);
        }
		dispatch(new SendCurrentStatusMessageToEmergencyContractsJob($this));
		Notification::storeNewAdminNotification(
			__('Ride Cancelled',[],'en'),
			__('Ride Cancelled',[],'ar'),
			__('Ride Number :travelId Has Been Cancelled At :dateTimeFormatted' , ['travelId'=>$this->getId(),'dateTimeFormatted'=>HDate::formatForView(now()->format('Y-m-d'))] , 'en'),
			__('Ride Number :travelId Has Been Cancelled At :dateTimeFormatted' , ['travelId'=>$this->getId(),'dateTimeFormatted'=>HDate::formatForView(now()->format('Y-m-d'))] , 'ar'),
		);
		
        return $this ;
    }

    /**
     * * هي هنا
     * * many to many
     * * لان ممكن نفترض ان الرحلة الواحدة هيتطبق عليها غرمتين واحدة علي السائق وواحدة علي العميل بس فعليا
     * * ولحد الان هي بتطتبق علي العميل بس وبالتالي هيكون فيه ريليشن كمان تحتها بالاسم المفروض علشان تجيب اول واحدة
     */
    public function fines()
    {
        return $this->hasMany(Fine::class, 'travel_id', 'id');
    }

    public function fine()
    {
        return $this->hasOne(Fine::class, 'travel_id', 'id');
    }

    /**
     * * عميلة اعادة الاموال الخاصة بالرحلة دي ان وجدت
     */
    public function refund()
    {
        return $this->hasOne(Refund::class, 'travel_id', 'id');
    }

    public function getResource()
    {
        return new TravelResource($this);
    }

    public function getFromLongitude()
    {
        return $this->from_longitude;
    }

    public function getFromLatitude()
    {
        return $this->from_latitude;
    }

    public function getToLongitude()
    {
        return $this->to_longitude;
    }

    public function getToLatitude()
    {
        return $this->to_latitude;
    }

    public function getFromAddress()
    {
        return $this->from_address ;
    }

    public function getToAddress()
    {
        return $this->to_address ;
    }

    public function calculateFirstTravelBonus(bool $isFirstTravel = null): float
    {
        $isFirstTravel = is_null($isFirstTravel) ? $this->client->isFirstTravel() : $isFirstTravel ;
        $priceModel = $this->getPriceModel();
        if (!$isFirstTravel || !$priceModel) {
            return  0 ;
        }

        return $priceModel->getBonusAfterFirstSuccessTravel();
    }
	public function applyPromotion()
	{
		$promotion = Promotion::onlyAvailable()->first();
		if($promotion){
			$this->promotion_id = $promotion->id;
			$this->save();
		}
		return $this;
	}
	
	public function getTravelPriceDetails():array 
	{
		return [
			'price' => $mainPriceWithoutDiscountAndTaxesAndCashFees = $this->hasStarted() ?  $this->calculateClientActualPriceWithoutDiscount() : 0,
			'total_fines'=>$totalFines = $this->client->getTotalAmountOfUnpaid(),
			'promotion_percentage' =>  $this->getPromotionPercentage(),
			'coupon_amount' => $couponAmount = $this->getCouponDiscountAmount(),
			'cash_fees'=>$cashFees = $this->calculateCashFees(),
			'tax_amount'=>$taxAmount = $this->calculateTaxAmount($mainPriceWithoutDiscountAndTaxesAndCashFees,$couponAmount,$cashFees,$promotionAmount = $this->calculatePromotionAmount($mainPriceWithoutDiscountAndTaxesAndCashFees,$couponAmount,$cashFees,$totalFines),$totalFines),
			'total_price' =>   $this->calculateClientTotalActualPrice($mainPriceWithoutDiscountAndTaxesAndCashFees,$couponAmount,$promotionAmount   ,$taxAmount,$cashFees,$totalFines)  ,
		];
	}
}
