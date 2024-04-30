<?php 
namespace App\Reports;

use App\Enum\ReportInterval;

class ReportService    
{
	/**
	 * * هناخد تاريخ معين ونجيب بداية ونهاية الشهر او الاسبوع او اليوم
	 */
	public function getStartDateAndEndDatePerInterval():array 
	{
		switch($this->report_type){
			case ReportInterval::DAILY : 
				return [
					'start_date'=>$this->date->format('Y-m-d') ,
					'end_date'=>$this->date->format('Y-m-d')
				];
			case ReportInterval::WEEKLY : 
				return [
					'start_date'=> $this->date->startOfWeek()->format('Y-m-d') ,
					'end_date'=>$this->date->endOfWeek()->format('Y-m-d')
				];
			case ReportInterval::MONTHLY:
				return [
					'start_date'=>$this->date->startOfMonth()->format('Y-m-d'),
					'end_date'=>$this->date->endOfMonth()->format('Y-m-d'),
				];
		}
	}
	
}
