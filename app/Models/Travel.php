<?php

namespace App\Models;

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

    protected $table = 'travels';
	protected $dates = [
		'expected_arrival_date',
	];
	
	protected static function boot()
    {
        parent::boot();
        static::saving(function(Travel $travel) {
            $travel->is_secure = $travel->client->getHasSecureTravel();
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
        $this->save();

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

        $request->boolean('is_secure') ? $this->storeSecureCode() : null ;
        $request->has('coupon_code') ? $travel->applyCoupon(Coupon::findByCode($request->get('coupon_code'))->id) : 0;

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
        $this->status = TravelStatus::ON_THE_WAY;
        $this->started_at = now();
		dispatch(new SendCurrentStatusMessageToEmergencyContractsJob($this));
		$fromLatitude = $this->getFromLongitude();
		$fromLongitude = $this->getFromLatitude();
		$toLatitude = $this->getToLatitude();
		$toLongitude = $this->getToLongitude();

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

    public function isCancelled(): bool
    {
        return $this->getStatus() == TravelStatus::CANCELLED;
    }

    public function getOperationalFees()
    {
        $statedAt = $this->getStartedAt();
        $city = $this->getCity() ;
        $priceModel = $city;
        $rushHour = $city->isInRushHourAt($statedAt);
        if ($rushHour) {
            $priceModel = $rushHour ;
        }

        return $priceModel->getOperatingFeesPrice();
    }

    /**
     * * هي رسوم بيدفعها العميل لو اختار طريق الدفع كاش
     */
    public function calculateCashFees()
    {
        $country = $this->getCountry() ;
        $isCashPayment = $this->isCashPayment();
        if (!$isCashPayment || !$country) {
            return 0 ;
        }

        return $country->getCashFees();
    }

    public function calculateTaxAmount(float $mainPriceWithoutDiscountAndTaxesAndCashFees = null)
    {
        $mainPriceWithoutDiscountAndTaxesAndCashFees = is_null($mainPriceWithoutDiscountAndTaxesAndCashFees) ? $this->calculateClientActualPriceWithoutDiscount() : $mainPriceWithoutDiscountAndTaxesAndCashFees;
        $country = $this->getCountry() ;
        if (!$country) {
            return 0 ;
        }
        $taxPercentage = $country->getTaxesPercentage() / 100 ;

        return $taxPercentage * $mainPriceWithoutDiscountAndTaxesAndCashFees ;
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
    public function calculateClientActualPriceWithoutDiscount()
    {
        /**
         * @var City $city
         */

        $statedAt = $this->getStartedAt();
        $city = $this->getCity() ;
        $priceModel = $city;
        $carSizePrice = $this->driver->carSize->getPrice($city->getCountryId(), getApiLang()) ;
        $rushHour = $city->isInRushHourAt($statedAt);
        if ($rushHour) {
            $priceModel = $rushHour ;
        }
        $kmPrice = $priceModel->getKmPrice();
        $minutePrice = $priceModel->getMinutePrice();
        $operationFees = $this->getOperationalFees();
        $numberOfMinutes = $this->getNumberOfMinutes();
        $numberOfKms = $this->getNumberOfKms();
        return $carSizePrice + ($kmPrice * $numberOfKms) + ($minutePrice * $numberOfMinutes) + $operationFees ;
    }

    /**
     * * هو نفس الحسبة السابقة مضاف اليها الغرامات ومنقوص منها الخصم مضاف اليها الضريبة .. وهو اجمالي ما سوف يتم دفعه للعميل
     */
    public function calculateClientTotalActualPrice(float $couponAmount = null, float $taxesAmount = null, float $cashFees = null)
    {
        /**
         * * لو ما مررنهاش هنحسبها
         */
        $couponAmount = is_null($couponAmount) ? $this->getCouponDiscountAmount() : $couponAmount ;
        $taxesAmount = is_null($taxesAmount) ? $this->calculateTaxesAmount() : $taxesAmount ;
        $cashFees = is_null($cashFees) ? $this->calculateCashFees() : $cashFees ;
        return $this->calculateClientActualPriceWithoutDiscount() - $couponAmount + $taxesAmount + $cashFees  ;
    }

    /**
     * * النسبة اللي الابلكيشن هياخدها
     * * التطبيق هياخد
     * * سعر الرحلة الرئيسي - رسوم التشغيل لان السائق ملهوش دعوه بيها لانها فلوس سيرفرات .. الكل مضروب في نسبة الاستطقاع
     */
    public function calculateApplicationShare()
    {
        $operationFees = $this->getOperationalFees();

        return ($this->calculateClientActualPriceWithoutDiscount() - $operationFees) * ($this->driver->getDeductionPercentage() / 100) ;
    }

    /**
     * * النسبة اللي السائق هياخدها
     * * السائق هياخد
     * * سعر الرحلة الرئيسي - رسوم التشغيل - رسوم التطبيق
     */
    public function calculateDriverShare()
    {
        $operationFees = $this->getOperationalFees();

        return ($this->calculateClientActualPriceWithoutDiscount() - $operationFees) - $this->calculateApplicationShare()  ;
    }

    public function getOperationFeesPrice()
    {
        $statedAt = $this->getStartedAt();
        $city = $this->getCity() ;
        $priceModel = $city;
        $rushHour = $city->isInRushHourAt($statedAt);
        if ($rushHour) {
            $priceModel = $rushHour ;
        }

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

    public function getPaymentCouponDiscountAmountFormatted()
    {
        $operationalFees = $this->getPaymentCouponDiscountAmount();
        $currentName = $this->getCurrencyNameFormatted();

        return number_format($operationalFees) . ' ' . __($currentName);
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
     * * قيمة الغرامة لو الغى الرحلة مثلا
     */
    public function getFineAmount()
    {
        //TODO:لسه ما اتعملتش الريليشن دي عايزها نضيف واحدة زي الباي منت
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
        $endedAt = $this->getEndedAt();
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
        $cancellationFeesAmount = $this->getCountry()->getCancellationFeesForClient() ;

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
        $country = $this->getCountry();
        if (!$isFirstTravel || !$country) {
            return  0 ;
        }

        return $country->getBonusAfterFirstSuccessTravel();
    }
}
