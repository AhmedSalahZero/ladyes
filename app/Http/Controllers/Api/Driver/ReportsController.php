<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apis\ShowProfitReportRequest;
use App\Http\Requests\Apis\ShowTravelPerHourReportRequest;
use App\Http\Resources\ProfitReportResource;
use App\Http\Resources\TravelPerHourReportResource;
use App\Models\Travel;
use App\Reports\ProfitReport;
use App\Reports\TravelsPerHoursReport;
use App\Traits\Api\HasApiResponse;
use Carbon\Carbon;

class ReportsController extends Controller
{
	use HasApiResponse;

	
	/**
	 * * عباره عن الارباح الشهرية او اليوميه او الاسبوعيه
	 */
	public function getProfitReport(ShowProfitReportRequest $request)
	{
		$date = Carbon::make($request->get('date' , now()->format('Y-m-d')));
		$reportIntervalType= $request->get('report_type');
		$profitReport = new ProfitReport($date,$reportIntervalType);
		$data = $profitReport->getProfitReport();
		return [
			'status'=>200,
			'message'=>__('Data Received Successfully'),
			'data'=>(new ProfitReportResource($data))
		];
		// return $this->apiResponse(__('Data Received Successfully',(new ProfitReportResource($data))->toArray($request)));
	}
	
	/**
	 * * في كل ساعه في الاسبوع جالي كام رحلة ؟
	 */
	public function getTravelPerHours(ShowTravelPerHourReportRequest $request)
	{
		$date = Carbon::make(now()->format('Y-m-d'));
		$travelPerHourReport = new TravelsPerHoursReport($date);
		$data = $travelPerHourReport->getReport();
		return [
			'status'=>200,
			'message'=>__('Data Received Successfully'),
			'data'=>(new TravelPerHourReportResource($data))
		];
		// return $this->apiResponse(__('Data Received Successfully',(new ProfitReportResource($data))->toArray($request)));
	}
}
