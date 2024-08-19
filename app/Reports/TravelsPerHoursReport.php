<?php 
namespace App\Reports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TravelsPerHoursReport   extends ReportService
{
	protected Carbon $date ;
	protected string $report_type = 'weekly';
	public function __construct(Carbon $date){
		$this->date = $date ;
	}
	public function getReport():array 
	{
		$startAndEndDateArr  = $this->getStartDateAndEndDatePerInterval() ;
		$startDate = $startAndEndDateArr['start_date']??null ;
		$endDate = $startAndEndDateArr['end_date']??null ;
		if(!$startDate || !$endDate){
			return [] ;
		}
		$driverId = Request()->user()->id ;
		$resultPerHour = [];
		foreach(range(intval('00:00:00'),intval('23:00:00')) as $time) {
				$hour = date("H", mktime($time)) ;
				$amOrPm = $hour < 12 ? 'AM' : 'PM'; 
				$resultPerHour[$hour.' ' . $amOrPm] =  DB::table('travels')
				->whereRaw('(started_at >= ? AND started_at <= ?)',[
					$startDate ." 00:00:00", 
					$endDate ." 23:59:59"
				])
				->whereRaw('hour(started_at) = ' . $time )
				->where('driver_id',$driverId)
				->count();
		  }
		 return $resultPerHour;
	}
	
}
