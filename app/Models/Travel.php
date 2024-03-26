<?php

namespace App\Models;

use App\Enum\AppNotificationType;
use App\Enum\PaymentStatus;
use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Enum\TravelStatus;
use App\Exceptions\TravelEndTimeNotFoundException;
use App\Exceptions\TravelStartTimeNotFoundException;
use App\Helpers\HHelpers;
use App\Helpers\HStr;
use App\Models\Driver;
use App\Traits\Models\HasBasicStoreRequest;
use App\Traits\Models\HasCity;
use App\Traits\Models\HasCountry;
use App\Traits\Models\HasCreatedAt;
use App\Traits\Models\HasStartedAtAndEndedAt;
use App\Traits\Scope\HasDefaultOrderScope;
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
    use HasCreatedAt;
    use HasStartedAtAndEndedAt;
    use HasCity;
    use HasCountry;

    protected $table = 'travels';

    public function getId()
    {
        return $this->id ;
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
        return $this->giftCoupon->code ;
    }

    public function generateGiftCoupon(): self
    {
        $giftCoupon = Coupon::generateGiftCouponForTravel($this->id, $this->calculateGiftCouponDiscountAmount());
        $this->gift_coupon_id = $giftCoupon->id ;
        $this->save();

        return $this ;
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

        return $this;
    }

    /**
     * * هنا هنحدد ان الرحلة بدات
     */
    public function markAsStarted(Request $request)
    {
        $this->status = TravelStatus::ON_THE_WAY;
        $this->started_at = now();
        $this->save();

        return $this;
    }

    public function storePayment(Request $request)
    {
        /**
         * @var City $city ;
         */
        $country = $this->city->getCountry() ;
        $currencyName = $country->getCurrency();
        $client = $this->client;
        $this->generateGiftCoupon();
        $couponAmount = $this->getCouponDiscountAmount();
        $paymentType = $request->get('payment_type');
        /**
         * * store new payment
         * @var Payment $payment
         */

        $payment = $this->payment()->create([
            'status' => PaymentStatus::PENDING,
            'currency_name' => $currencyName,
            'type' => $paymentType,
            'price' => $this->calculateClientActualPriceWithoutDiscount(),
            'coupon_amount' => $couponAmount,
            'total_price' => $totalPrice = $this->calculateClientTotalActualPriceWithoutDiscount($couponAmount),
            'model_id' => $this->id,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($this)
        ]);
        /**
         * * اولا هنضيف ايداع بسعر الرحلة لمحفظتة
         */
        $payment->transaction()->create([
            'type' => TransactionType::DEPOSIT,
            'type_id' => $payment->id,
            'model_id' => $client->id,
            'amount' => $totalPrice,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($client),
            'note_en' => __('Amount Has Been Been Added To Your Wallet :amount :currency', ['amount' => number_format($totalPrice), 'currency' => $currencyName, 'paymentMethod' => $paymentType], 'en'),
            'note_ar' => __('Amount Has Been Been Added To Your Wallet :amount :currency', ['amount' => number_format($totalPrice), 'currency' => __($currencyName, [], 'ar'), 'paymentMethod' => __($paymentType, [], 'ar')], 'ar')
        ]);

        /**
         * *بعدين هنشيل المبلغ دا من محفظته كرسوم للرحلة
         */
        $payment->transaction()->create([
            'type' => TransactionType::PAYMENT,
            'type_id' => $payment->id,
            'model_id' => $client->id,
            'amount' => $totalPrice * -1,
            'model_type' => HHelpers::getClassNameWithoutNameSpace($client),
            'note_en' => __('Amount Has Been Been Subtracted From Your Wallet :amount :currency', ['amount' => number_format($totalPrice), 'currency' => $currencyName], 'en'),
            'note_ar' => __('Amount Has Been Been Subtracted From Your Wallet :amount :currency For Travel # :travelId', ['amount' => number_format($totalPrice), 'currency' => __($currencyName, [], 'ar'), 'travelId' => $this->getId()], 'ar')
        ]);

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
        return $this->status ;
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
     * * هو نفس الحسبة السابقة مضاف اليها الغرامات ومنقوص منها الخصم .. وهو اجمالي ما سوف يتم دفعه للعميل
     */
    public function calculateClientTotalActualPriceWithoutDiscount(float $couponAmount = null)
    {
        /**
         * * لو ما مررنهاش هنحسبها
         */
        $couponAmount = is_null($couponAmount) ? $this->getCouponDiscountAmount() : $couponAmount ;

        return $this->calculateClientActualPriceWithoutDiscount() + $couponAmount ;
    }

    /**
     * * النسبة اللي الابلكيشن هياخدها
     * * التطبيق هياخد
     * * سعر الرحلة الرئيسي - رسوم التشغيل لان السائق ملهوش دعوه بيها لانها فلوس سيرفرات .. الكل مضروب في نسبة الاستطقاع
     */
    public function calculateApplicationShare()
    {
        $statedAt = $this->getStartedAt();
        $city = $this->getCity() ;
        $priceModel = $city;
        $rushHour = $city->isInRushHourAt($statedAt);
        if ($rushHour) {
            $priceModel = $rushHour ;
        }
        $operationFees = $priceModel->getOperatingFeesPrice();

        return ($this->calculateClientActualPriceWithoutDiscount() - $operationFees) * ($this->driver->getDeductionPercentage() / 100) ;
    }

    /**
     * * النسبة اللي السائق هياخدها
     * * السائق هياخد
     * * سعر الرحلة الرئيسي - رسوم التشغيل - رسوم التطبيق
     */
    public function calculateDriverShare()
    {
        $statedAt = $this->getStartedAt();
        $city = $this->getCity() ;
        $priceModel = $city;
        $rushHour = $city->isInRushHourAt($statedAt);
        if ($rushHour) {
            $priceModel = $rushHour ;
        }
        $operationFees = $priceModel->getOperatingFeesPrice();

        return ($this->calculateClientActualPriceWithoutDiscount() - $operationFees) - $this->calculateApplicationShare()  ;
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
        return $this->getPaymentPriceFormatted() + $this->getPaymentCouponDiscountAmount();
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

    public function getCurrencyNameFormatted()
    {
        $country = $this->getCountry() ;

        return  $country ? __($country->getCurrency(),[],getApiLang()) : '';
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
        $mainPrice = $this->getMain();
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

    // public function country()
    // {
    //     return $this->belongsTo(Country::class, 'country_id', 'id');
    // }

    public function getPaymentMethod()
    {
        return $this->payment->getTypeFormatted();
    }

    public function getPaymentStatus()
    {
        return $this->payment->getStatus();
    }

    public function getCountry(): ?Country
    {
        $city = $this->city ;
        if ($city) {
            return $city->getCountry() ;
        }

        return  $this->country ;
    }

    /**
     * * عدد الكيلوا مترات المقطوعه خلال كامل الرحلة
     */
    public function getNumberOfKms()
    {
        return $this->no_km ;
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
	public function calculateCancellationFees(bool $isDriver)
	{
		$cancellationFeesAmount = $isDriver ? $this->getCountry()->getCancellationFeesForDriver() : $this->getCountry()->getCancellationFeesForClient();
		return $isDriver  ?  $cancellationFeesAmount : $this->getNumberOfMinutes() *  $cancellationFeesAmount   ;
	}
	/**
	 * * تطبيق غرامة علي العميل في حالة الغاء الرحلة وتسديدها في حالة لو كان العميل لديه اموال في محفظته 
	 */
	public function applyCancellationFine(Request $request)
	{
		$user = $request->user() ;
		$isDriver = $user instanceof Driver ;
		
		$currency = $this->getCurrencyNameFormatted();
		$amount = $this->calculateCancellationFees($isDriver);
		$hasBalanceInHisWallet =  $user->getTotalWalletBalance() >= $amount;
		$this->fine()->create([
			'travel_id'=>$this->id ,
			'model_type'=>$isDriver ? 'Driver' : 'Client',
			'model_id'=>$user->id ,
			'amount'=> $amount ,
			'is_paid'=>$hasBalanceInHisWallet ,
			'note_en'=>$noteEn = __('You Have :amount :currency Fine In Your Wallet For Cancellation Travel #:travelId',['amount'=>$amount , 'currency'=>$currency , 'travelId'=>$this->id ],'en' ),
			'note_ar'=>$noteAr = __('You Have :amount :currency Fine In Your Wallet For Cancellation Travel #:travelId',['amount'=>$amount , 'currency'=>$currency , 'travelId'=>$this->id ],'ar' )
		]);
		
		$user->sendAppNotification(__('Fine',[],'en') , __('Fine',[],'ar') ,$noteEn , $noteAr , AppNotificationType::FINE);
		/**
		 * *  هنضيفلة قيمة بالسالب في محفظته
		 */
			Transaction::create(
				[
					'type' => TransactionType::FINE,
					'type_id' => null ,
					'model_id' => $user->id,
					'amount' => $amount * -1,
					'model_type' => HHelpers::getClassNameWithoutNameSpace($user),
					'note_en' => __('Amount Has Been Been Added To Your Wallet :amount :currency', ['amount' => number_format($amount), 'currency' => $currency, 'paymentMethod' => 'Wallet'], 'en'),
					'note_ar' => __('Amount Has Been Been Added To Your Wallet :amount :currency', ['amount' => number_format($amount), 'currency' => __($currency, [], 'ar'), 'paymentMethod' => __('Wallet', [], 'ar')], 'ar')
				]
			);
		if($hasBalanceInHisWallet){
			/**
		 * *  هنسدد الغرامة
		 */
		Transaction::create(
			[
				'type' => TransactionType::FINE,
				'type_id' => null ,
				'model_id' => $user->id,
				'amount' => $amount,
				'model_type' => HHelpers::getClassNameWithoutNameSpace($user),
				'note_en' => __('The Fine Amount :amount :currency Has Been Successfully Paid', ['amount' => number_format($amount), 'currency' => $currency], 'en'),
				'note_ar' => __('The Fine Amount :amount :currency Has Been Successfully Paid', ['amount' => number_format($amount), 'currency' => __($currency, [], 'ar')], 'ar')
			]
		);
		}
	}
	/**
	 * * تحديد ما اذا كانت الرحلة قد بدات بالفعل
	 */
	public function hasStarted():bool 
	{
		return $this->started_at !== null  ; 
	}
	public function markAsCancelled(Request $request)
	{
		$this->status = TravelStatus::CANCELLED;
		$this->ended_at = now();
		$this->cancelled_by  = HHelpers::getClassNameWithoutNameSpace($request->user()) ;
		$this->save();
		if($this->hasStarted()){
			$this->applyCancellationFine($request);
		}
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
		return $this->hasMany(Fine::class,'travel_id','id');
	}
	public function fine()
	{
		return $this->hasOne(Fine::class,'travel_id','id');
	}
	/**
	 * * في حاله الغاء الرحلة .. مين اللي لغاءها العميل ولا السائق
	 */
	public function getCancelledBy()
	{
		return $this->cancelled_by ;
	}
}
