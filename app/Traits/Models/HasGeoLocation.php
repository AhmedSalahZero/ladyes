<?php 
namespace App\Traits\Models;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Builder;

trait HasGeoLocation 
{
	use SpatialTrait;
	protected $spatialFields = [
        'location',
    ];

	public function scopeWithDistancesInKm(Builder $query , $latitude , $longitude )
    {
        $query->selectRaw('*, st_distance_sphere(location, POINT(? , ?)) / 1000 as distance_in_km',[$longitude,$latitude]);
    }

	public function scopeOnlyDistanceLessThanOrEqual(Builder $builder ,$longitude , $latitude , $distanceInKm = null,$column = 'distance_in_km' )
    {
        $builder->whereRaw('st_distance_sphere(location, POINT(? , ?)) / 1000 < driving_range' , [$longitude , $latitude] );
		// to use specific values instead of column name 
        // $builder->whereRaw('st_distance_sphere(location, POINT(? , ?)) / 1000 < ?' , [$longitude , $latitude,$distanceInKm] );
    }
	
    public function scopeOrderByDistance($builder , $column = 'distance_in_km' ,  $direction = 'asc')
    {
        $builder->orderBy($column , $direction);
    }
	public static function getWithinRange($latitude , $longitude )
	{
	
		return self::withDistancesInKm( $latitude , $longitude )
		->onlyDistanceLessThanOrEqual($latitude , $longitude)
        ->orderByDistance()
        ->get();
		
	}
	public function getLocation()
	{
		return $this->location ;
	}
	public function getLatitude()
	{
		return $this->getLocation()->getLat() ;
	}
	public function getLongitude()
	{
		return $this->getLocation()->getLng() ;
	}
	public function getDistanceInKm()
	{
		$distance = $this->distance_in_km ?: 0 ;
		return round($distance,2);
	}	
}
