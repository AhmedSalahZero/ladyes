<?php

namespace App\Http\Controllers\Admin;

use App\Enum\TravelStatus;
use App\Helpers\HHelpers;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Driver;
use App\Models\Transaction;
use App\Models\Travel;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getDashboard(){
		$adminsCount = DB::table('admins')->count() ;
		$clientCount = DB::table('clients')->count() ;
		$driverCount = DB::table('drivers')->count() ;
		$travelsCount = DB::table('travels')->count() ;
		$completedTravelsCount = DB::table('travels')->where('status',TravelStatus::COMPLETED)->count() ;
		$completedTravelsPercentage = $travelsCount ? $completedTravelsCount/$travelsCount *100 : 0; 

		$cancelledTravelsCount = DB::table('travels')->where('status',TravelStatus::CANCELLED)->count() ;
		$cancelledTravelsPercentage = $travelsCount ? $cancelledTravelsCount/$travelsCount *100 : 0; 

		$onTheWayTravelsCount = DB::table('travels')->where('status',TravelStatus::ON_THE_WAY)->count() ;
		$onTheWayTravelsPercentage = $travelsCount ? $onTheWayTravelsCount/$travelsCount *100 : 0; 
		
		$notStartedYetTravelsCount = DB::table('travels')->where('status',TravelStatus::NOT_STARTED_YET)->count() ;
		$notStartedYetTravelsPercentage = $travelsCount ? $notStartedYetTravelsCount/$travelsCount *100 : 0; 
		
		
		$totalUsers = $driverCount + $clientCount ;
		$clientPercentage =  $totalUsers ? $clientCount / $totalUsers * 100 : 0 ;
		$driverPercentage =  $totalUsers ? $driverCount / $totalUsers * 100 : 0 ;
		$totalTransactions  = DB::table('transactions')->selectRaw('sum(abs(amount)) as total')->first()->total;
		$latestTravels = Travel::orderByRaw('id desc')->take(10)->get();
		$latestTransactions = Transaction::orderByRaw('id desc')->take(10)->get();
		$latestClients = Client::orderByRaw('id desc')->take(10)->get();
		$latestDrivers = Driver::orderByRaw('id desc')->take(10)->get();
		
		$numberOfTravelsPerMonthInCurrentYear = DB::table('travels')->groupByRaw('Year(started_at) , Month(started_at)')
		->whereRaw('year(started_at) = '.now()->format('Y'))
		->whereNotNull('started_at')
		->selectRaw('lpad(month(started_at) , 2,0 ) as month ,count(*) as count')
		->orderByRaw('month(started_at) asc')
		->get()->toArray();
		
		$numberOfTravelsPerMonthInCurrentYear = HHelpers::removeLevel($numberOfTravelsPerMonthInCurrentYear);
		
		
		
		$numberOfTravelsPerCityInCurrentYear = DB::table('travels')->groupByRaw('city_id')
		->join('cities','cities.id','=','travels.city_id')
		->whereRaw('year(started_at) = '.now()->format('Y'))
		->whereNotNull('started_at')
		->selectRaw('count(*) as count,cities.name_'.app()->getLocale())
		->orderByRaw('count desc')
		->get()->take(12)->toArray();
		$numberOfTravelsPerCityInCurrentYear = HHelpers::removeLevel2($numberOfTravelsPerCityInCurrentYear,1);
		
		
		
        return view('admin.dashboard',[
			'clientsCount'=>$clientCount,
			'driversCount'=>$driverCount,
			'clientPercentage'=>$clientPercentage,
			'driverPercentage'=>$driverPercentage,
			'travelsCount'=>$travelsCount,
			'adminsCount'=>$adminsCount,
			'completedTravelsCount'=>$completedTravelsCount,
			'completedTravelsPercentage'=>$completedTravelsPercentage,
			'cancelledTravelsCount'=>$cancelledTravelsCount,
			'cancelledTravelsPercentage'=>$cancelledTravelsPercentage,
			'onTheWayTravelsCount'=>$onTheWayTravelsCount,
			'onTheWayTravelsPercentage'=>$onTheWayTravelsPercentage,
			
			'notStartedYetTravelsCount'=>$notStartedYetTravelsCount,
			'notStartedYetTravelsPercentage'=>$notStartedYetTravelsPercentage,
			'totalTransactions'=>$totalTransactions,
			'latestTravels'=>$latestTravels,
			'latestTransactions'=>$latestTransactions,
			'latestClients'=>$latestClients			,
			'latestDrivers'=>$latestDrivers,
			'numberOfTravelsPerMonthInCurrentYear'=>$numberOfTravelsPerMonthInCurrentYear,
			'numberOfTravelsPerCityInCurrentYear'=>$numberOfTravelsPerCityInCurrentYear
        ]);
    }
}
