<?php

namespace App\Models;

use App\Helpers\HDate;
use App\Helpers\HHelpers;
use App\Interfaces\IHaveAppNotification;
use App\Notifications\Admins\ClientNotification;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasBasicStoreRequest;
use App\Traits\Models\HasCanPayByCash;
use App\Traits\Models\HasCountry;
use App\Traits\Models\HasCreatedAt;
use App\Traits\Models\HasEmail;
use App\Traits\Models\HasEmergencyContacts;
use App\Traits\Models\HasGeoLocation;
use App\Traits\Models\HasIsVerified;
use App\Traits\Models\HasLoginByPhone;
use App\Traits\Models\HasMake;
use App\Traits\Models\HasModel;
use App\Traits\Models\HasPhone;
use App\Traits\Models\HasRating;
use App\Traits\Models\HasWallet;
use App\Traits\Scope\HasDefaultOrderScope;
use Codebyray\ReviewRateable\Contracts\ReviewRateable;
use Codebyray\ReviewRateable\Traits\ReviewRateable as ReviewRateableTrait;
use Cog\Contracts\Ban\Ban as BanContract;
use Cog\Contracts\Ban\Bannable as BannableInterface;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravolt\Avatar\Avatar;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Client extends Model implements HasMedia, BannableInterface,IHaveAppNotification,ReviewRateable 
{
	use HasWallet ;
    use ReviewRateableTrait;
    use HasMake;
    use HasGeoLocation;
    use Notifiable;
    use HasRating;
    use HasFactory;
    use IsBaseModel;
    use HasDefaultOrderScope;
    use HasCountry;
    use HasIsVerified;
    use InteractsWithMedia;
    use HasBasicStoreRequest;
    use HasModel;
	use HasApiTokens;
	use HasLoginByPhone;
    use Bannable;
    use HasCanPayByCash;
    use HasPhone;
    use HasEmail;
    use HasRating;
    use HasEmergencyContacts;
    use HasCreatedAt;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
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

    public function getBirthDate()
    {
        return $this->birth_date ?: null ;
    }

    public function getBirthDateFormatted()
    {
        return HDate::formatForView($this->getBirthDate(), true) ;
    }

    public function syncFromRequest(Request $request)
    {
        $client = $this->storeBasicForm($request);
		if($request->get('country_iso2')){
			$client->country_id = Country::findByIso2($request->get('country_iso2'))->id;
			$client->save();
		}
        // 1- generate invitation_code
        // // $client = $this->storeInventionCodeIfNotExist();
        // $client = $this->storeVerificationCodeIfNotExist();
        // 2- if not confirmed then send verification code
		// $client->sendVerificationCodeMessage()
        return $client;
    }

    public function getImage()
    {
        // not that : it will return svg for avatar not url for
        $image = $this->getFirstMedia('image') ;

        return $image ? $image->getFullUrl() : (new Avatar())->create($this->getFullName())->setFontFamily('/custom/fonts/Cairo-Medium.ttf')->toSvg();
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

    // public function getInvitationCode()
    // {
    // 	return $this->invitation_code ;
    // }
  

    public function banHistories(): MorphMany
    {
        return $this->morphMany(app(BanContract::class), 'bannable')->withoutGlobalScopes();
    }

    public function travels()
    {
        return $this->hasMany(Travel::class, 'client_id', 'id');
    }

    /**
     * * العناوين المحفوظة من قبل المستخدم .. او بمعني اخر العناوين المفضلة بالنسبة له
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'client_id', 'id');
    }

    /**
     * * اضافة عنوان جديد للعميل
     *
     */
    public function assignNewAddress(array $address): self
    {
        $this->addresses()->create($address);

        return $this ;
    }

    public function updateAddress(int $addressId, array $newAddressAr): self
    {
        $address = $this->addresses->where('id', $addressId)->first();
        if ($address) {
            $address->update($newAddressAr);
        }

        return $this ;
    }

    public function deleteAddress(int $addressId): self
    {
        $this->addresses()->where('addresses.id', $addressId)->delete();

        return $this ;
    }
	public function getCountry():?Country
	{
		return $this->country ?: null ;
	}
	public function country()
	{
		return $this->belongsTo(Country::class ,'country_id','id');
	}
	public function sendNotificationToAdminAfterLogin():bool
	{
		return false ;
	}
	/**
	 * * هي الاشعارات اللي بتتبعت للعميل في الموبايل ابلكيشن
	 */
	public function sendAppNotification(string $titleEn,string $titleAr,string $messageEn,string $messageAr,string $type)
	{
		$this->notify(new ClientNotification($titleEn,$titleAr,$messageEn,$messageAr,formatForView(now()),$type));
	}
	public function payments():?HasOneThrough
	{
		return $this->hasMany(Payment::class,'model_id','id')->where('model_type',HHelpers::getClassNameWithoutNameSpace($this));
	}
	public function transactions()
	{
		return $this->hasMany(Transaction::class , 'model_id','id')->where('model_type',HHelpers::getClassNameWithoutNameSpace($this));
	}
	
}
