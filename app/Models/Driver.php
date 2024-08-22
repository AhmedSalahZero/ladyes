<?php

namespace App\Models;

use App\Enum\TravelStatus;
use App\Helpers\HDate;
use App\Helpers\HHelpers;
use App\Http\Resources\DriverResource;
use App\Interfaces\IHaveAppNotification;
use App\Interfaces\IHaveBonus;
use App\Interfaces\IHaveDeposit;
use App\Interfaces\IHaveFine;
use App\Interfaces\IHaveWithdrawal;
use App\Notifications\Admins\DriverNotification;
use App\Notifications\Admins\NewTravelIsAvailableForDriverNotification;
use App\Services\DistanceMatrix\GoogleDistanceMatrixService;
use App\Settings\SiteSetting;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasBasicStoreRequest;
use App\Traits\Models\HasBonus;
use App\Traits\Models\HasCanReceiveOrders;
use App\Traits\Models\HasCarSize;
use App\Traits\Models\HasCity;
use App\Traits\Models\HasCountry;
use App\Traits\Models\HasCreatedAt;
use App\Traits\Models\HasDeduction;
use App\Traits\Models\HasDeposit;
use App\Traits\Models\HasDevice;
use App\Traits\Models\HasDeviceTokens;
use App\Traits\Models\HasEmail;
use App\Traits\Models\HasEmergencyContacts;
use App\Traits\Models\HasFine;
use App\Traits\Models\HasGeoLocation;
use App\Traits\Models\HasInvitationCode;
use App\Traits\Models\HasIsListingToOrdersNow;
use App\Traits\Models\HasIsVerified;
use App\Traits\Models\HasLocation;
use App\Traits\Models\HasLoginByPhone;
use App\Traits\Models\HasMake;
use App\Traits\Models\HasMedals;
use App\Traits\Models\HasModel;
use App\Traits\Models\HasPhone;
use App\Traits\Models\HasRating;
use App\Traits\Models\HasTrafficTickets;
use App\Traits\Models\HasTravelCondition;
use App\Traits\Models\HasWallet;
use App\Traits\Models\HasWithdrawal;
use App\Traits\Scope\HasDefaultOrderScope;
use Codebyray\ReviewRateable\Contracts\ReviewRateable;
use Codebyray\ReviewRateable\Traits\ReviewRateable as ReviewRateableTrait;
use Cog\Contracts\Ban\Ban as BanContract;
use Cog\Contracts\Ban\Bannable as BannableInterface;
use Cog\Laravel\Ban\Traits\Bannable;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Laravolt\Avatar\Avatar;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Driver extends Model implements HasMedia, BannableInterface, IHaveAppNotification, ReviewRateable,IHaveFine,IHaveBonus,IHaveDeposit,IHaveWithdrawal
{
    use HasMake;
	use HasFine ;
	use HasBonus ;
	use HasDeposit ;
	use HasTravelCondition;
    use ReviewRateableTrait;
    use HasWallet;
    use HasDevice;
    use HasFactory;
	use HasMedals ;
	use HasLocation;
    use IsBaseModel;
	use HasDeduction ;
    use HasDefaultOrderScope;
    use HasCountry;
    use HasCity;
    use HasIsVerified;
    use InteractsWithMedia;
    use HasBasicStoreRequest;
    use HasModel;
    use HasTrafficTickets;
    use Bannable;
	use HasWithdrawal;
    use HasCanReceiveOrders;
    use Notifiable;
    use HasInvitationCode;
    use HasIsListingToOrdersNow;
    use HasPhone;
    use HasEmail;
    use HasRating;
    use HasEmergencyContacts;
    use HasApiTokens;
    use HasCreatedAt;
    use HasLoginByPhone;
    use HasGeoLocation;
    use HasCarSize;
	use HasDeviceTokens;
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
        $this->addMediaCollection('id_number_image')->singleFile();
        $this->addMediaCollection('insurance_image')->singleFile();
        $this->addMediaCollection('driver_license_image')->singleFile();
        $this->addMediaCollection('front_image')->singleFile();
        $this->addMediaCollection('back_image')->singleFile();
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getName()
    {
        return $this->getFullName();
    }

    public function getCountryIso2()
    {
        return $this->getCountyIso2() ;
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(CarSize::class, 'size_id', 'id');
    }
	public function carSize(): BelongsTo
    {
          return $this->size();
    }
	
    /**
     *
     * * نطاق الطلابات بالـ كم وبالتالي خارج هذا النطاق لا لا يستطيع السائق رؤية الطلابات
     */
    public function getDrivingRange()
    {
        if (is_null($this->driving_range)) {
            return App(SiteSetting::class)->driving_range;
        }

        return $this->driving_range;
    }

    public static function getDefaultDrivingRangeFormatted()
    {
        return [
            [
                'title' => 10,
                'value' => 10,

            ],
            [
                'title' => 15,
                'value' => 15,

            ],
            [
                'title' => 20,
                'value' => 20,

            ],

        ];
    }

    public function getSizeId(): int
    {
        $size = $this->size;

        return $size ? $size->id : 0 ;
    }

    public function getSizeName($lang): ?string
    {
        $size = $this->size ;

        return $size ? $size->getName($lang) : __('N/A');
    }

    public function getBirthDate()
    {
        return $this->birth_date ?: null ;
    }

    public function getBirthDateFormatted()
    {
        return HDate::formatForView($this->getBirthDate(), true) ;
    }

    public function storeCurrentLocationIfExist(Request $request): self
    {
        /**
         * * هو عباره عن اخر لوكيشن للسائق واللوكيشن دا بيتحدث كل مره السائق بيحدد نفسه انه متصل علشان يستقبل طلابات
         */
        if ($request->has('current_latitude') && $request->has('current_longitude')) {
            $this->location = new Point($request->get('current_latitude'), $request->get('current_longitude'));
            $this->save();
        }

        return $this ;
    }

    public function syncFromRequest(Request $request)
    {
        $driver = $this->storeBasicForm($request);
        $driver = $this->storeCurrentLocationIfExist($request);
        // 1- generate invitation_code
        $driver = $this->storeInventionCodeIfNotExist();
    
        return $driver;
    }

    public function storeInventionCodeIfNotExist()
    {
        if ($this->invitation_code) {
            return $this ;
        }
        $inventionCodeLength = getSetting('invitation_code_length');
        $this->invitation_code = HHelpers::generateUniqueCodeForModel('Driver', 'invitation_code', $inventionCodeLength);
        $this->save();

        return $this ;
    }

    public function getImage()
    {
        // not that : it will return svg for avatar not url for
        $image = $this->getFirstMedia('image') ;

        return $image ? $image->getFullUrl() : (new Avatar())->create($this->getFullName())->setFontFamily('/custom/fonts/Cairo-Medium.ttf')->toSvg();
    }

    // invitation codes

    // المرسلة
    public function sentInvitationCodes()
    {
        return $this->belongsToMany(Driver::class, 'driver_invitation', 'sender_id', 'receiver_id')
        ->withPivot(['code', 'created_at'])
        ;
    }

    // المستلمة
    public function receivedInvitationCodes()
    {
        return $this->belongsToMany(Driver::class, 'driver_invitation', 'receiver_id', 'sender_id')
        ->withPivot(['code', 'created_at'])
        ;
    }

    public static function getNameById($id)
    {
        $model = self::find($id);

        return $model ? $model->getFullName() : __('N/A');
    }

    public static function findByIdOrEmailOrPhone($idOrEmailOrPhone)
    {
        return static::where(function (Builder $builder) use ($idOrEmailOrPhone) {
            $builder->where('id', $idOrEmailOrPhone)->orWhere('email', $idOrEmailOrPhone)->orWhere('phone', $idOrEmailOrPhone);
        })->first();
    }

    public function getInvitationCode()
    {
        return $this->invitation_code ;
    }

    public function getIdNumber()
    {
        return $this->id_number ;
    }

    public function getManufacturingYear()
    {
        return $this->manufacturing_year;
    }

    public function getPlateLetters()
    {
        return $this->plate_letters;
    }

    public function getPlateNumbers()
    {
        return $this->plate_numbers;
    }

    public function getCarColorName()
    {
        return $this->car_color ;
    }

    public function getCarMaxCapacity()
    {
        return $this->car_max_capacity;
    }

    public function getCarIdNumber()
    {
        return $this->car_id_number;
    }

    public function banHistories(): MorphMany
    {
        return $this->morphMany(app(BanContract::class), 'bannable')->withoutGlobalScopes();
    }

    public static function getByInvitationCode(string $invitationCode): ?self
    {
        return self::where('invitation_code', $invitationCode)->first();
    }
	
    public function getCountry(): ?Country
    {
        return $this->city ? $this->city->country : null ;
    }

    public function sendNotificationToAdminAfterLogin(): bool
    {
        return true ;
    }

    /**
     * * هي الاشعارات اللي بتتبعت للعميل في الموبايل ابلكيشن
     */
    public function sendAppNotification(string $titleEn, string $titleAr, string $messageEn, string $messageAr, string $secondaryType,int $modelId = null , string $mainType = 'notification' )
    {
        $this->notify(new DriverNotification($titleEn, $titleAr, $messageEn, $messageAr, formatForView(now()), $secondaryType,$modelId,$mainType));
    }

    /**
     * * this only for drivers
     * * ايجاد السائقين المتاحين لطلب معين
     * * ال latitude , longitude
     * * هنا عباره عن اللوكيشن بتاع العميل
     * * وبالتالي هنجيب السواقين اللي ال
     * * driver range
     * * بتاعهم مناسب للعميل دا
     * * واللي عندهم نوع معين من السيارات وليكن مثلا
     * * family car
     */
    public static function getAvailableForSpecificLocationsAndCarSize($latitude, $longitude, int $carSizeId)
    {
		/**
		 * @var Collection $drivers
		 */
		
		$drivers = self::onlyListingToOrders()
        ->onlyIsVerified()
		->onlyHasLocation()
        ->onlyCanReceiveOrders()
        ->withoutBanned()
        ->onlyWithCarSize($carSizeId)
        ->withDistancesInKm($latitude, $longitude)
        ->onlyDistanceLessThanOrEqual($latitude, $longitude)
        ->orderByDistance()
        ->get() ;
		
		
		return $drivers->filter(function(Driver $driver){
			$currentUser = Request()->user() ;
			if(!$currentUser && env('in_test_mode')){
				$currentUser =  Client::first();
			}
			return $driver->satisfyConditions($currentUser->getTravelConditionIds());
		});
    }
	public function getResource()
	{
		return new DriverResource($this);
	}	
	public function transactions()
	{
		return $this->hasMany(Transaction::class , 'model_id','id')->where('model_type',HHelpers::getClassNameWithoutNameSpace($this));
	}
	public function isClient()
	{
		return false ;
	}
	public function travels()
    {
        return $this->hasMany(Travel::class, 'driver_id', 'id');
    }
	public function cancelledTravels():HasMany
    {
        return $this->hasMany(Travel::class, 'driver_id', 'id')->where('status',TravelStatus::CANCELLED);
    }
	public function onTheWayTravels():HasMany
    {
        return $this->hasMany(Travel::class, 'driver_id', 'id')->where('status',TravelStatus::ON_THE_WAY);
    }
	public function completedTravels():HasMany
    {
        return $this->hasMany(Travel::class, 'driver_id', 'id')->where('status',TravelStatus::COMPLETED);
    }
	/**
	 * * نسبة الرحلات المكتملة لهذا السائق من اجمالي رحلاته
	 */
	public function getCompletedTravelsPercentage():float 
	{
		$totalTravels = $this->travels()->count();
		$completedTravels = $this->travels()->onlyCompleted()->count();
		return $totalTravels ? $completedTravels /  $totalTravels * 100 : 0 ;
	}
	
	public function getCompletedTravelsPercentageFormatted():string 
	{
		return number_format($this->getCompletedTravelsPercentage(),2) . ' %';
	}
	
	/**
	 * * نسبة الرحلات التي تم الغائها لهذا السائق من اجمالي رحلاته
	 */
	public function getCancelledTravelsPercentage():float 
	{
		$totalTravels = $this->travels()->count();
		$cancelledTravels = $this->travels()->onlyCancelled()->count();
		return $totalTravels ? $cancelledTravels /  $totalTravels * 100 : 0 ;
	}
	
	public function getCancelledTravelsPercentageFormatted():string 
	{
		return number_format($this->getCancelledTravelsPercentage(),2) . ' %';
	}
	/**
	 * * عباره عن سجل الاتصال و الانقطاع لهذا السائق 
	 * * بمعني لما السائق بيعمل اتصال علشان يبدا يستقبل طلبات هنسجلها
	 * * ولما يعمل لوج اوت او ينهي الاتصال هنقفلها
	 */
	public function connections():HasMany
	{
		return $this->hasMany(DriverConnection::class,'driver_id','id');
	}
	public function rushHourLogs():HasMany
	{
		return $this->hasMany(DriverRushHour::class,'driver_id','id');
	}
	public function handleConnectionLogs()
	{
	
		if($last = $this->connections->last()){
			if($last->ended_at){
				$this->connections()->create([
					'started_at'=>now()
				]);	
			}else{
				$last->update([
					'ended_at'=>now()
				]);
			}
		}else{
	
			if($this->getIsListingToOrders()){
				$this->connections()->create([
					'started_at'=>now()
				]);	
			}
			
		}
	}
	public function addNewRushHorLog(Carbon $startedAt):self
	{
		 $this->rushHourLogs()->create() ;
		$dayInMonth = $startedAt->daysInMonth;
		$year = $startedAt->format('Y');
		$month = $startedAt->format('m');
		/**
		 * * لو عدد الساعات اللي اشتغلها في الذروة اكبر او تساوي عدد الايام في هذا الشهر .. يبقي هو مستحق لوسام العمل في الذروة
		 */
		$rushHoursInThisMonthAndYearCount = $this->rushHourLogs()->whereYear('created_at','=',$year)->whereMonth('created_at','=',$month)->count();
		if($rushHoursInThisMonthAndYearCount >= $dayInMonth ){
			$this->has_rush_hour_medal = true ;
			$this->save();
		}
		return $this ;
	}	
	/**
	 * * لو السائق دا مكنش معاه وسام انه خلص خمسين رحلة وفي نفس الوقت كام مخلص خمسين رحلة هياخد الوسام دا
	 */
	public function handleCompletedTravelsMedal():self 
	{
		if(!$this->HasCompleted50TravelsMedal() && $this->completedTravels()->count() > 50){
			$this->has_completed_50_travel_medal = true ;
			$this->save();
		}
		return $this ;
	}	
	public function handleExcellentMedal():self 
	{
		$year = now()->format('Y');
		$month = now()->format('m');
		$dayInMonth = now()->daysInMonth;;
		$numberOfRatesInMonthAndYear =DB::table('reviews')->where('reviewrateable_type','App\Models\Driver')
		->where('reviewrateable_id',$this->id)
		->whereYear('created_at',$year)
		->whereMonth('created_at',$month)
		->count();
		if($numberOfRatesInMonthAndYear >= $dayInMonth){
			$this->has_excellent_medal = true ; 
			$this->save();
		}
		return $this ;
	}
	public function sendNewTravelIsAvailable(Travel $travel)
	{
		if($travel->driver){ // في حالة لو سواق وافق
			return ; 
		}
		$googleDistanceMatrixService = new GoogleDistanceMatrixService();
		$result = $this && $this->getLongitude() ? $googleDistanceMatrixService->getExpectedArrivalTimeBetweenTwoPoints($this->getLatitude(),$this->getLongitude(),$travel->getFromLatitude(),$travel->getFromLongitude()) : [];
		$travelInfos = [
	
			'from_address'=>$travel->getFromAddress(),
			'to_address'=>$travel->getToAddress(),
			'expected_arrival_time'=>isset($result['duration_in_seconds']) ? __('Estimated Arrival Time :time',['time'=>now()->addSeconds($result['duration_in_seconds'])->format('g:i A')]) : '-' ,
			'expected_arrival_distance'=>isset($result['distance_in_meter']) ? __(':distance Km Away',['distance'=>round($result['distance_in_meter'] / 1000,1) ]) : '-' ,
			'client_travel_conditions'=>$travel->client->getTravelConditionsTitles(),
			'client'=>[
				'id'=>$travel->client->id,
				'name'=>$travel->client->getName(),
				'avg_rate'=>$travel->client->getAvgRate()
			]
		];
		
		$this->notify(new NewTravelIsAvailableForDriverNotification($travelInfos,$this->id));
		
	}
	
}
