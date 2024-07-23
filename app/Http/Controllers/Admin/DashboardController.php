<?php

namespace App\Http\Controllers\Admin;

use App\Enum\TravelStatus;
use App\Helpers\HHelpers;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Driver;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\Travel;
use Carbon\Carbon;
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
		$latestSupportTickets = SupportTicket::orderByRaw('id desc')->take(10)->get(); 
		$numberOfTravelsPerMonthInCurrentYear = DB::table('travels')->groupByRaw('Year(started_at) , Month(started_at)')
		->whereRaw('year(started_at) = '.now()->format('Y'))
		->whereNotNull('started_at')
		->selectRaw('lpad(month(started_at) , 2,0 ) as month ,count(*) as count')
		->orderByRaw('month(started_at) asc')
		->get()->toArray();
		
		
		$noListingToOrdersDrivers = DB::table('drivers')->where('is_listing_to_orders_now',1)->count();
		$noListingToOrdersDriversPercentage = $noListingToOrdersDrivers ? $driverCount / $noListingToOrdersDrivers * 100 : 0;
		
		$numberOfTravelsPerMonthInCurrentYear = HHelpers::removeLevel($numberOfTravelsPerMonthInCurrentYear);
		
		
		
		$numberOfTravelsPerCityInCurrentYear = DB::table('travels')->groupByRaw('city_id')
		->join('cities','cities.id','=','travels.city_id')
		->whereRaw('year(started_at) = '.now()->format('Y'))
		->whereNotNull('started_at')
		->selectRaw('count(*) as count,cities.name_'.app()->getLocale())
		->orderByRaw('count desc')
		->get()->take(12)->toArray();
		$numberOfTravelsPerCityInCurrentYear = HHelpers::removeLevel2($numberOfTravelsPerCityInCurrentYear,1);
		
		
		
		
		$incomeEquation = 'application_share + driver_share + operational_fees ';
		/**
		 * * الدخل اليومي عباره عن 
		 */
	
		 
		$incomes['Daily Income'] = DB::table('payments')->whereDate('created_at', Carbon::today() )->groupBy('currency_name')->selectRaw('currency_name,sum('.$incomeEquation.') as amount')->get();
		$incomes['Weekly Income'] = DB::table('payments')->whereDate('created_at', '<=' , Carbon::today() )->whereDate('created_at','>=',Carbon::today()->subWeek())->groupBy('currency_name')->selectRaw('currency_name,sum('.$incomeEquation.') as amount')->get();
		$incomes['Monthly Income'] = DB::table('payments')->whereDate('created_at', '<=' , Carbon::today() )->whereDate('created_at','>=',Carbon::today()->subMonth())->groupBy('currency_name')->selectRaw('currency_name,sum('.$incomeEquation.') as amount')->get();
		$incomes['Yearly Income'] = DB::table('payments')->whereDate('created_at', '<=' , Carbon::today() )->whereDate('created_at','>=',Carbon::today()->subYear())->groupBy('currency_name')->selectRaw('currency_name,sum('.$incomeEquation.') as amount')->get();
		$incomes['Total Operational Fees'] = DB::table('payments')->groupBy('currency_name')->selectRaw('currency_name,sum(operational_fees) as amount')->get();
		$incomes['Total Coupon Amount'] = DB::table('payments')->groupBy('currency_name')->selectRaw('currency_name,sum(coupon_amount) as amount')->get();
		$incomes['Total Tax Amount'] = DB::table('payments')->groupBy('currency_name')->selectRaw('currency_name,sum(tax_amount) as amount')->get();
		$incomes['Total Cash Fees'] = DB::table('payments')->groupBy('currency_name')->selectRaw('currency_name,sum(cash_fees) as amount')->get();
		$incomes['Total Fine Amount'] = DB::table('payments')->groupBy('currency_name')->selectRaw('currency_name,sum(total_fines) as amount')->get();
		$incomes['Total Application Share'] = DB::table('payments')->groupBy('currency_name')->selectRaw('currency_name,sum(application_share) as amount')->get();
		$incomes['Total Driver Share'] = DB::table('payments')->groupBy('currency_name')->selectRaw('currency_name,sum(driver_share) as amount')->get();
		$noSupportTickets = DB::table('support_tickets')->count();

		
		
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
			'latestSupportTickets'=>$latestSupportTickets,
			'numberOfTravelsPerMonthInCurrentYear'=>$numberOfTravelsPerMonthInCurrentYear,
			'numberOfTravelsPerCityInCurrentYear'=>$numberOfTravelsPerCityInCurrentYear,
			'noListingToOrdersDrivers'=>$noListingToOrdersDrivers,
			'noListingToOrdersDriversPercentage'=>$noListingToOrdersDriversPercentage,
			'incomes'=>$incomes,
			'noSupportTickets'=>$noSupportTickets
        ]);
    }
}
