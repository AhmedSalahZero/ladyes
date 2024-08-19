<?php 
namespace App\Traits\Models;


trait HasMedals 
{
	public function scopeOnlyHasRushHourMedal($q){
        return $q->where('has_rush_hour_medal',1);
    }
	public function HasRushHourMedal()
	{
		return (bool) $this->has_rush_hour_medal ;
	}
	public function HasRushHourMedalFormatted()
	{
		return $this->HasRushHourMedal() ? __('Yes',[],getApiLang()) : __('No',[],getApiLang());
	}	
	
	
	
	public function scopeOnlyHasCompleted50TravelMedal($q){
        return $q->where('has_completed_50_travel_medal',1);
    }
	public function HasCompleted50TravelsMedal()
	{
		return (bool) $this->has_completed_50_travel_medal ;
	}
	public function HasCompleted50TravelsMedalFormatted()
	{
		return $this->HasCompleted50TravelsMedal() ? __('Yes',[],getApiLang()) : __('No',[],getApiLang());
	}	
	
	
	
	public function scopeOnlyHasOneYearUsageMedal($q){
        return $q->where('has_one_year_usage_medal',1);
    }
	public function HasOneYearUsageMedal()
	{
		return $this->created_at->diffInYears() > 0 ;
		// return (bool) $this->has_one_year_usage_medal ;
	}
	public function HasOneYearUsageMedalFormatted()
	{
		return $this->HasOneYearUsageMedal() ? __('Yes',[],getApiLang()) : __('No',[],getApiLang());
	}	
	
	
	public function scopeOnlyHasExcellentMedal($q){
        return $q->where('has_excellent_medal',1);
    }
	public function HasExcellentMedal()
	{
		return (bool) $this->has_excellent_medal ;
	}
	public function HasExcellentMedalFormatted()
	{
		return $this->HasExcellentMedal() ? __('Yes',[],getApiLang()) : __('No',[],getApiLang());
	}	
	
	
}
