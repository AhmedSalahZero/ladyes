<?php 
namespace App\Reports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfitReport  extends ReportService 
{
	protected Carbon $date ;
	protected string $report_type;
	public function __construct(Carbon $date , string $reportType){
		$this->date = $date ;
		$this->report_type = $reportType;
	}
	public function getProfitReport():array 
	{
		$startAndEndDateArr  = $this->getStartDateAndEndDatePerInterval() ;
		$startDate = $startAndEndDateArr['start_date']??null ;
		$endDate = $startAndEndDateArr['end_date']??null ;
		if(!$startDate || !$endDate){
			return [] ;
		}
		$driverId = Request()->user()->id ;
		$totalProfitForThisInterval = DB::table('transactions')
		->whereRaw('(created_at >= ? AND created_at <= ?)',[
			$startDate ." 00:00:00", 
			$endDate ." 23:59:59"
		])
		->where('is_profit',1)
		->where('model_id',$driverId)
		->where('model_type','Driver')
		->sum('amount')
		;
		
		$totalProfit = DB::table('transactions')
		->where('is_profit',1)
		->where('model_id',$driverId)
		->where('model_type','Driver')
		->sum('amount');
		
		$totalNumberOfTravelsForThisInterval = DB::table('travels')
		->where('driver_id',$driverId)
		->whereRaw('(started_at >= ? AND started_at <= ?)',[
			$startDate ." 00:00:00", 
			$endDate ." 23:59:59"
		])->count();
		
		
		$totalNumberOfTravels = DB::table('travels')
		->where('driver_id',$driverId)
		->count();
		
		$noKmsForThisInterval = DB::table('travels')
		->where('driver_id',$driverId)
		->whereRaw('(started_at >= ? AND started_at <= ?)',[
			$startDate ." 00:00:00", 
			$endDate ." 23:59:59"
		])->sum('no_km');
		
		$TotalNoKms = DB::table('travels')
		->where('driver_id',$driverId)
		->sum('no_km');
		
		
		
				
		$totalConnectionsForThisInterval = DB::table('driver_connections')
		->where('driver_id',$driverId)
		->whereRaw('(started_at >= ? AND started_at <= ?)',[
			$startDate ." 00:00:00", 
			$endDate ." 23:59:59"
		])->selectRaw(" sum(TIMESTAMPDIFF(MINUTE,  started_at , ended_at )) as diff_in_minutes")->first();
		$totalConnectionsForThisInterval = $totalConnectionsForThisInterval->diff_in_minutes;
		
		
		$totalConnections = DB::table('driver_connections')
		->where('driver_id',$driverId)
		->selectRaw(" sum(TIMESTAMPDIFF(MINUTE,  started_at , ended_at )) as diff_in_minutes")->first();
		$totalConnections = $totalConnections->diff_in_minutes;
		
		$totalConnectionsPercentage = $totalConnections  ? $totalConnectionsForThisInterval /$totalConnections * 100 :0;
		$result  = [
			'profit'=>[
				'for_date'=>$totalProfitForThisInterval,
				'total'=>$totalProfit ,
				'percentage'=>$totalProfit ? $totalProfitForThisInterval / $totalProfit * 100 : 0 
			],
			'no_travels'=>[
				'for_date'=>$totalNumberOfTravelsForThisInterval ,
				'total'=>$totalNumberOfTravels ,
				'percentage'=>$totalNumberOfTravels ? $totalNumberOfTravelsForThisInterval/$totalNumberOfTravels*100 : 0,
			],
			'no_kms'=>[
				'for_date'=>$noKmsForThisInterval ,
				'total'=>$TotalNoKms,
				'percentage' =>$TotalNoKms ? $noKmsForThisInterval / $TotalNoKms *100 : 0 
			],
			'connections'=>[
				'for_date'=>intdiv($totalConnectionsForThisInterval, 60).':'. ($totalConnectionsForThisInterval % 60) ,
				'total'=>intdiv($totalConnections, 60).':'. ($totalConnections % 60) ,
				'percentage'=>intdiv($totalConnectionsPercentage, 60).':'. ($totalConnectionsPercentage % 60)  
			]
			] ;		
		return $result;
	}

}
