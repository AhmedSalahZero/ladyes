<?php

namespace App\Models;
use App\Helpers\HDate;
use App\Helpers\HHelpers;
use App\Settings\SiteSetting;
use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasArea;
use App\Traits\Models\HasBasicStoreRequest;
use App\Traits\Models\HasCanReceiveOrders;
use App\Traits\Models\HasCity;
use App\Traits\Models\HasCountry;
use App\Traits\Models\HasInvitationCode;
use App\Traits\Models\HasIsListingToOrdersNow;
use App\Traits\Models\HasIsVerified;
use App\Traits\Models\HasMake;
use App\Traits\Models\HasModel;
use App\Traits\Models\HasTrafficTickets;
use App\Traits\Scope\HasDefaultOrderScope;
use Cog\Contracts\Ban\Ban as BanContract;
use Cog\Contracts\Ban\Bannable as BannableInterface;
use Cog\Laravel\Ban\Traits\Bannable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Request;
use Laravolt\Avatar\Avatar;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Driver extends Model implements HasMedia,BannableInterface
{
    use HasMake,HasFactory,IsBaseModel,HasDefaultOrderScope,
	HasCountry,HasCity,HasIsVerified,InteractsWithMedia,HasBasicStoreRequest,
	HasModel,HasTrafficTickets, Bannable,HasArea,HasCanReceiveOrders,
	HasInvitationCode,HasIsListingToOrdersNow
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
		return $this->first_name .' ' . $this->last_name;
	}
	public function getName()
	{
		return $this->getFullName();
	}
	public function getPhone()
	{
		return $this->phone ;
	}
	public function getCountryIso2()
	{
		return $this->getCountyIso2() ;
	}
	public function getEmail()
	{
		return $this->email ;
	}
	
	public function size():BelongsTo
	{
		return $this->belongsTo(CarSize::class ,'size_id','id');
	}
	public function getDeductionPercentage()
	{
		if($this->deduction_percentage == -1 || is_null($this->deduction_percentage) ){
			return App(SiteSetting::class)->deduction_percentage;
		}
		return $this->deduction_percentage;
	}
	public function getSizeId():int 
	{
		$size = $this->size;
		return $size ? $size->id : 0 ;
	}
	public function getSizeName($lang):?string
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
		return HDate::formatForView($this->getBirthDate(),true) ;
	}
	public function syncFromRequest(Request $request){
		$driver = $this->storeBasicForm($request);
		// 1- generate invitation_code
		$driver = $this->storeInventionCodeIfNotExist();
		$driver = $this->storeVerificationCodeIfNotExist();
		// 2- if not confirmed then send verification code 
		$driver->sendVerificationCodeMessage();
	}
	public function storeInventionCodeIfNotExist()
	{
		if($this->invitation_code){
			return $this ;
		}
		$inventionCodeLength = getSetting('invitation_code_length');
		$this->invitation_code = HHelpers::generateUniqueCodeForModel('Driver','invitation_code',$inventionCodeLength);
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
		return $this->belongsToMany(Driver::class , 'driver_invitation','sender_id','receiver_id')
		->withPivot(['code','created_at'])
		;
	}
	// المستلمة
	public function receivedInvitationCodes()
	{
		return $this->belongsToMany(Driver::class , 'driver_invitation','receiver_id','sender_id')
		->withPivot(['code','created_at'])
		;
	}
	public static function getNameById($id){
		$model = self::find($id);
		return $model ? $model->getFullName() : __('N/A');
	}
	public static function findByIdOrEmailOrPhone($idOrEmailOrPhone){
		return static::where(function(Builder $builder) use ($idOrEmailOrPhone){
			$builder->where('id',$idOrEmailOrPhone)->orWhere('email',$idOrEmailOrPhone)->orWhere('phone',$idOrEmailOrPhone);
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
	public  function getMaxRatingPoint()
	{
		return 5 ;
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
	
	
}
