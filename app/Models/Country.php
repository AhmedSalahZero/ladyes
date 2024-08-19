<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasLatitudeAndLatitude;
use App\Traits\Models\HasTransNames;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
    // implements HasMedia
{
    use
        //  InteractsWithMedia,
        IsBaseModel;
    use HasDefaultOrderScope ;
    use HasTransNames ;
    use HasFactory;
    use HasLatitudeAndLatitude ;

	protected $guarded = [
		'id'
	];
	public function getName(string $lang = null)
	{
		$lang = $lang ?: app()->getLocale();
		return $this['name_'.$lang] ;
	}
    public function cities()
    {
        return $this->hasMany(City::class, 'country_id', 'id');
    }

    public function getPhoneCode()
    {
        return $this->phonecode ;
    }

    public function getIso3()
    {
        return $this->iso3;
    }

    public function getIso2()
    {
        return $this->iso2;
    }

    public function getNumericCode()
    {
        return $this->numericCode();
    }

    public function getCapital()
    {
        return $this->capital ;
    }

    public function getCurrency()
    {
        // for example [EGP]
        return $this->currency ;
    }
	public function getCurrencyFormatted(string $lang = null)
	{
		$lang = is_null($lang) ? getApiLang() : $lang ;
		return __($this->getCurrency(),[],$lang);
	}

    public function getCurrencyName()
    {
        // for example [Egyptian pound]
        return $this->currency_name;
    }

    public function getCurrencySymbol($lang)
    {
        // 
		/**
		 *  for example [ج.م] for arabic
		 * for example [EGP] for english
		 */
		if($lang == 'ar'){
			return $this->currency_symbol ;
		}
		return $this->currency ;
    }

    public function getNationality()
    {
        // for example [Egyptian]
        return $this->nationality;
    }
  
	public static function findByCode(?string $code):?self
	{
		return self::where('phonecode',$code)->first();
	}
	public static function findByIso2(?string $iso2):?self
	{
		return self::where('iso2',$iso2)->first();
	}
	public function travels()
	{
		return $this->hasMany(Travel::class,'country_id','id');
	}
	
	/**
	 * * الرسوم اللي هيتم تطبيقها علي السائق في حالة قام بالغاء الرحلة
	 */
	// public function getCancellationFeesForDriver()
	// {
	// 	return $this->cancellation_fees_for_driver?:0 ;
	// }
	/**
	 * * الرسوم اللي هيتم تطبيقها علي العميل في حالة قام بالغاء الرحلة
	 */
	public function getTaxesPercentage()
	{
		return $this->taxes_percentage ?: 0 ;
	}
	
	
	
}
