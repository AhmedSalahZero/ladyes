<?php 
namespace App\Traits\Models;


trait HasRating
{
	public  function getMaxRatingPoint()
	{
		return "5.0" ;
	}
	public function getAvgRate()
	{
		$avgRate = $this->averageRating(true) ;
		return is_null($avgRate) ?__('N/A') : $avgRate ; 
	}
	/**
	 * * كل التقيمات سواء كان هو اللي مقيم او متقيم
	 */
	public function getRatings()
	{
		return $this->getAllRatings($this->id,'desc');
	}
	
		/**
	 * * التقيمات اللي هو تلقائها
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
	
	
	
}
