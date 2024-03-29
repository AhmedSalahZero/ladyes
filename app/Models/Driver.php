<?php

namespace App\Models;

use App\Helpers\HDate;
use App\Helpers\HHelpers;
use App\Interfaces\IHaveAppNotification;
use App\Notifications\Admins\DriverNotification;
use App\Settings\SiteSetting;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasBasicStoreRequest;
use App\Traits\Models\HasCanReceiveOrders;
use App\Traits\Models\HasCarSize;
use App\Traits\Models\HasCity;
use App\Traits\Models\HasCountry;
use App\Traits\Models\HasCreatedAt;
use App\Traits\Models\HasEmail;
use App\Traits\Models\HasEmergencyContacts;
use App\Traits\Models\HasGeoLocation;
use App\Traits\Models\HasInvitationCode;
use App\Traits\Models\HasIsListingToOrdersNow;
use App\Traits\Models\HasIsVerified;
use App\Traits\Models\HasLoginByPhone;
use App\Traits\Models\HasMake;
use App\Traits\Models\HasModel;
use App\Traits\Models\HasPhone;
use App\Traits\Models\HasRating;
use App\Traits\Models\HasTrafficTickets;
use App\Traits\Models\HasWallet;
use App\Traits\Scope\HasDefaultOrderScope;
use Codebyray\ReviewRateable\Contracts\ReviewRateable;
use Codebyray\ReviewRateable\Traits\ReviewRateable as ReviewRateableTrait;
use Cog\Contracts\Ban\Ban as BanContract;
use Cog\Contracts\Ban\Bannable as BannableInterface;
use Cog\Laravel\Ban\Traits\Bannable;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravolt\Avatar\Avatar;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Driver extends Model implements HasMedia, BannableInterface, IHaveAppNotification, ReviewRateable
{
    use HasMake;
    use ReviewRateableTrait;
    use HasWallet;
    use HasFactory;
    use IsBaseModel;
    use HasDefaultOrderScope;
    use HasCountry;
    use HasCity;
    use HasIsVerified;
    use InteractsWithMedia;
    use HasBasicStoreRequest;
    use HasModel;
    use HasTrafficTickets;
    use Bannable;
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
    use HasCarSize
    ;

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

    public function getDeductionPercentage()
    {
        if ($this->deduction_percentage == -1 || is_null($this->deduction_percentage)) {
            return App(SiteSetting::class)->deduction_percentage;
        }

        return $this->deduction_percentage;
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
        $driver = $this->storeVerificationCodeIfNotExist();

        // 2- if not confirmed then send verification code
        return $driver->sendVerificationCodeMessage();
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

    public function getVerificationCode()
    {
        return $this->verification_code;
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
    public function sendAppNotification(string $titleEn, string $titleAr, string $messageEn, string $messageAr, string $type)
    {
        $this->notify(new DriverNotification($titleEn, $titleAr, $messageEn, $messageAr, formatForView(now()), $type));
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
        return self::onlyListingToOrders()
        ->onlyIsVerified()
        ->onlyCanReceiveOrders()
        ->withoutBanned()
        ->onlyWithCarSize($carSizeId)
        ->withDistancesInKm($latitude, $longitude)
        ->onlyDistanceLessThanOrEqual($latitude, $longitude)
        ->orderByDistance()
        ->get();
    }
}
