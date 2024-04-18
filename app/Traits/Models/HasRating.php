<?php 
namespace App\Traits\Models;

use App\Helpers\HDate;


trait HasRating
{
	public  function getMaxRatingPoint()
	{
		return "5.0" ;
	}
	/**
	 * * دي لل 
	 * * api 
	 * * 
	 */
	public function getAvgRate():?string 
	{
		$avgRate = $this->averageRating(true) ;
		return is_null($avgRate) ? null : $avgRate ; 
	}
	/**
	 * * دي هتكون للداش بورد
	 */
	public function getAvgRateFormatted()
	{
		$avgRate = $this->averageRating(true) ;
		return is_null($avgRate) ? __('N/A') : $avgRate ; 
	}
	/**
	 * * كل التقيمات سواء كان هو اللي مقيم او متقيم
	 */
	public function getRatings()
	{
		return $this->getAllRatings($this->id,'desc');
	}
	
		/**
	 * * التقيمات اللي هو تلقاها من الاخرين
	 */
	public function getReceivedRatings()
	{
		return $this->getAllRatings($this->id,'desc')->where('reviewrateable_type',get_class($this));
	}
	
		/**
	 * * التقيمات اللي هو قيمها للاخرين
	 */
	public function getSentRatings()
	{
		return $this->getAllRatings($this->id,'desc')->where('author_type',get_class($this));
	}
	public function getRatesForApi():array 
	{
		return  $this->getReceivedRatings()->map(function($rate){
			/**
			 * * اسم الشخص اللي عمله التقيم دا 
			 */
			$user  = getModelByNamespaceAndId($rate->author_type,$rate->author_id) ;
			return [
				'name'=>$user ?  $user->getFullName() : __('N/A' , [] , getApiLang()) ,
				'image'=>$user && $user->getFirstMedia('image') ? $user->getFirstMedia('image')->getFullUrl() : getDefaultImage(),
				'stars'=>$rate->rating ,
				'comment'=>$rate->body ,
				'date'=>HDate::formatForView($rate->created_at,true) 
			];
		})->toArray();
	}
	
	
}
