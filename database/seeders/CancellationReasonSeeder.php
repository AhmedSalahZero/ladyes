<?php

namespace Database\Seeders;

use App\Enum\CancellationReasonPhases;
use App\Enum\PaymentType;
use App\Enum\TransactionType;
use App\Enum\TravelStatus;
use App\Helpers\HHelpers;
use App\Models\Address;
use App\Models\Admin;
use App\Models\CancellationReason;
use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\CarSize;
use App\Models\City;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\Driver;
use App\Models\EmergencyContact;
use App\Models\Payment;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\Travel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CancellationReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		CancellationReason::factory()->create([
			'name_en'=>'Client did not come',
			'name_ar'=>'العميل لم ياتي',
			'model_type'=>'Driver',
		]);
	
		
		CancellationReason::factory()->create([
			'name_en'=>'By Mistake',
			'name_ar'=>'لقد طلبت عن طريق الخطا',
			'model_type'=>'Client',
			'phase'=>CancellationReasonPhases::WHILE_SEARCHING
		]);
		
		
		CancellationReason::factory()->create([
			'name_en'=>'Driver Is Too Late',
			'name_ar'=>'السائق تاخر كثيرا',
			'model_type'=>'Client',
			'phase'=>CancellationReasonPhases::BEFORE_DRIVER_ARRIVE
		]);
		
		
		CancellationReason::factory()->create([
			'name_en'=>'Bad Behavior',
			'name_ar'=>' سلوك السائق غير مناسب لي ',
			'model_type'=>'Client',
			'phase'=>CancellationReasonPhases::AFTER_DRIVER_ARRIVE
		]);
		
		
		///////////// Roles 
		
		Role::create([
			'name'=>'مدير',
			'guard_name'=>'admin'
		]);
		Role::create([
			'name'=>'مشرف',
			'guard_name'=>'admin'
		]);
		
		
		
		
		$admin = Admin::factory()->create([
			'email'=>'admin@admin.com',
			'password'=>Hash::make('admin'),
			'name'=>'احمد صلاح'
		]);
		
		$admin->assignRole(Role::find(1));
		
		
		
		$admin = Admin::factory()->create([
			'email'=>'ali@admin.com',
			'password'=>Hash::make('admin'),
			'name'=>'علي خالد'
		]);
		
		$admin->assignRole(Role::find(1));		
		
		
		
		DB::unprepared("INSERT INTO `countries` (`id`, `name_en`, `name_ar`, `iso3`, `numeric_code`, `iso2`, `phonecode`, `capital`, `currency`, `currency_name`, `currency_symbol`, `nationality`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
		(1, 'Afghanistan', 'افغانستان', 'AFG', '004', 'AF', '93', 'Kabul', 'AFN', 'Afghan afghani', '؋', 'Afghan', 33.00000000, 65.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51');
		INSERT INTO `countries` (`id`, `name_en`, `name_ar`, `iso3`, `numeric_code`, `iso2`, `phonecode`, `capital`, `currency`, `currency_name`, `currency_symbol`, `nationality`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
		(2, 'Aland Islands', 'جزایر الند', 'ALA', '248', 'AX', '+358-18', 'Mariehamn', 'EUR', 'Euro', '€', 'Aland Island', 60.11666700, 19.90000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51');
		INSERT INTO `countries` (`id`, `name_en`, `name_ar`, `iso3`, `numeric_code`, `iso2`, `phonecode`, `capital`, `currency`, `currency_name`, `currency_symbol`, `nationality`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
		(3, 'Albania', 'آلبانی', 'ALB', '008', 'AL', '355', 'Tirana', 'ALL', 'Albanian lek', 'Lek', 'Albanian ', 41.00000000, 20.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51');
		INSERT INTO `countries` (`id`, `name_en`, `name_ar`, `iso3`, `numeric_code`, `iso2`, `phonecode`, `capital`, `currency`, `currency_name`, `currency_symbol`, `nationality`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
		(4, 'Algeria', 'الجزایر', 'DZA', '012', 'DZ', '213', 'Algiers', 'DZD', 'Algerian dinar', 'دج', 'Algerian', 28.00000000, 3.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(18, 'Bahrain', 'البحرين', 'BHR', '048', 'BH', '973', 'Manama', 'BHD', 'Bahraini dinar', '.د.ب', 'Bahraini', 26.00000000, 50.55000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(65, 'Egypt', 'مصر', 'EGY', '818', 'EG', '20', 'Cairo', 'EGP', 'Egyptian pound', 'ج.م', 'Egyptian', 27.00000000, 30.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(104, 'Iraq', 'عراق', 'IRQ', '368', 'IQ', '964', 'Baghdad', 'IQD', 'Iraqi dinar', 'د.ع', 'Iraqi', 33.00000000, 44.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(111, 'Jordan', 'الاردن', 'JOR', '400', 'JO', '962', 'Amman', 'JOD', 'Jordanian dinar', 'ا.د', 'Jordanian', 31.00000000, 36.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(117, 'Kuwait', 'الكويت', 'KWT', '414', 'KW', '965', 'Kuwait City', 'KWD', 'Kuwaiti dinar', 'ك.د', 'Kuwaiti', 29.50000000, 45.75000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(121, 'Lebanon', 'لبنان', 'LBN', '422', 'LB', '961', 'Beirut', 'LBP', 'Lebanese pound', '£', 'Lebanese', 33.83333333, 35.83333333, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(124, 'Libya', 'ليبيا', 'LBY', '434', 'LY', '218', 'Tripolis', 'LYD', 'Libyan dinar', 'د.ل', 'Libyan', 25.00000000, 17.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(139, 'Mauritania', 'وریتانی', 'MRT', '478', 'MR', '222', 'Nouakchott', 'MRO', 'Mauritanian ouguiya', 'MRU', 'Mauritanian', 20.00000000, -12.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(149, 'Morocco', 'المغرب', 'MAR', '504', 'MA', '212', 'Rabat', 'MAD', 'Moroccan dirham', 'DH', 'Moroccan', 32.00000000, -5.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(166, 'Oman', 'عمان', 'OMN', '512', 'OM', '968', 'Muscat', 'OMR', 'Omani rial', '.ع.ر', 'Omani', 21.00000000, 57.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(179, 'Qatar', 'قطر', 'QAT', '634', 'QA', '974', 'Doha', 'QAR', 'Qatari riyal', 'ق.ر', 'Qatari', 25.50000000, 51.25000000, '2018-07-21 12:11:03', '2024-01-10 22:13:51'),
		(194, 'Saudi Arabia', 'المملكة العربية السعودية', 'SAU', '682', 'SA', '966', 'Riyadh', 'SAR', 'Saudi riyal', '﷼', 'Saudi, Saudi Arabian', 25.00000000, 45.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:52'),
		(209, 'Sudan', 'السودان', 'SDN', '729', 'SD', '249', 'Khartoum', 'SDG', 'Sudanese pound', '.س.ج', 'Sudanese', 15.00000000, 30.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:52'),
		(215, 'Syria', 'سوريا', 'SYR', '760', 'SY', '963', 'Damascus', 'SYP', 'Syrian pound', 'LS', 'Syrian', 35.00000000, 38.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:52'),
		(224, 'Tunisia', 'تونس', 'TUN', '788', 'TN', '216', 'Tunis', 'TND', 'Tunisian dinar', 'ت.د', 'Tunisian', 34.00000000, 9.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:52'),
		(231, 'United Arab Emirates', 'الامارات', 'ARE', '784', 'AE', '971', 'Abu Dhabi', 'AED', 'United Arab Emirates dirham', 'إ.د', 'Emirati, Emirian, Emiri', 24.00000000, 54.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:52'),
		(233, 'United States', 'الولايات المتحدة الامريكية', 'USA', '840', 'US', '1', 'Washington', 'USD', 'United States dollar', '$', 'American', 38.00000000, -97.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:52'),
		(245, 'Yemen', 'اليمن', 'YEM', '887', 'YE', '967', 'Sanaa', 'YER', 'Yemeni rial', '﷼', 'Yemeni', 15.00000000, 48.00000000, '2018-07-21 12:11:03', '2024-01-10 22:13:52')
		");
	
	
	$carSize = CarSize::factory()->create([
		'name_en'=>'Eco',
		'name_ar'=>'Eco',
		'image'=>CarSize::ECO_IMAGE
	]);
	
	$carSize->countryPrices()->attach([
		
		'country_id'=>65
	],[
		'price'=>40,
		'model_type'=>'CarSize'
	]);
	
	
	
	$carSize = CarSize::factory()->create([
		'name_en'=>'vip',
		'name_ar'=>'متميز',
		'image'=>CarSize::VIP_IMAGE
	]);
	$carSize->countryPrices()->attach([
		
		'country_id'=>194
	],[
		'price'=>90,
		'model_type'=>'CarSize'
	]);
	
	$carSize->countryPrices()->attach([
		
		'country_id'=>65
	],[
		'price'=>90,
		'model_type'=>'CarSize'
	]);
	
	$carSize = CarSize::factory()->create([
		'name_en'=>'family',
		'name_ar'=>'عائلي',
		'image'=>CarSize::FAMILY_IMAGE
	]);
	$carSize->countryPrices()->attach([
		
		'country_id'=>194
	],
['price'=>90,
'model_type'=>'CarSize']
);

$carSize->countryPrices()->attach([
		
	'country_id'=>65
],
['price'=>90,
'model_type'=>'CarSize']
);

	$carSize = CarSize::factory()->create([
		'name_en'=>'private',
		'name_ar'=>'خاص',
		'image'=>CarSize::PRIVATE_IMAGE
	]);
	$carSize->countryPrices()->attach([
	
		'country_id'=>194
	],[
		'price'=>20,
		'model_type'=>'CarSize'
	]);
	$carSize->countryPrices()->attach([
	
		'country_id'=>65
	],[
		'price'=>20,
		'model_type'=>'CarSize'
	]);

	CarMake::factory()->create([
		'name_en'=>'toyota',
		'name_ar'=>'تويوتا',
	]);
	CarModel::factory()->create([
		'make_id'=>CarMake::first()->id 
	]);
	
	$city = City::factory()->create([
		'name_en'=>'Mecca',
		'name_ar'=>'مكة',
		// 'longitude'=>'21.42250',
		// 'latitude'=>'39.82611',
		'country_id'=>194 // sa
	   ]);
	   
	   $city->rushHours()->create([
		'start_time'=>now()->toTimeString(),
		'end_time'=>now()->addHour()->toTimeString(),
		'percentage'=>'1/5',
		'km_price'=>$city->km_price + 2 ,
		'minute_price'=>$city->minute_price + 1 ,
		'operating_fees'=>$city->operating_fees + 1 ,
	   ]);
	   

	   
	   $city = City::factory()->create([
		'name_en'=>'Cairo',
		'name_ar'=>'القاهرة',

		// 'longitude'=>'21.42250',
		// 'latitude'=>'39.82611',
		'country_id'=>65 // eg
	   ]);
	   
	   $city->rushHours()->create([
		'start_time'=>now()->toTimeString(),
		'end_time'=>now()->addHour()->toTimeString(),
		// 'price'=>$city->price + 10 ,
		'percentage'=>'2/5',
		'km_price'=>$city->km_price + 10 ,
		'minute_price'=>$city->minute_price + 10 ,
		'operating_fees'=>$city->operating_fees + 1 ,
	   ]);
	
	   Driver::factory()->create([
		'first_name'=>'ahmed',
		'last_name'=>'salah',
		'email'=>'asalahdev5@gmail.com',
		'phone'=>'01025894984',
		'country_id'=>65,
		'city_id'=>2,
		// 'verification_code'=>1234
	]);
	
	Driver::factory()->count(50)->create();
	
	$client = Client::factory()->create([
		'phone'=>'01025894984',
		'country_id'=>65,
		'email'=>'asalahdev5@gmail.com',
		// 'verification_code'=>1234
	]);
	$address = Address::factory()->make([
		'category'=>'المنزل',
		'description'=>'عنوان المنزل',
	])->toArray();
	$client->addresses()->create($address);
	
	
	EmergencyContact::factory()->create([
		'name'=>'احمد صلاح',
		'email'=>'asalahdev5@gmail.com',
		'phone'=>'01025894984',
		'country_id'=>65,
	]);
	
	EmergencyContact::factory()->count(10)->create()->each(function(EmergencyContact $emergencyContact){
		$emergencyContact->drivers()->attach(Driver::first()->id,[
			'model_type'=>'Driver',
			'can_receive_travel_info'=>1
		]);
	});	
	
	Coupon::factory()->create([
		'code'=>'abc123'
	]);
	Coupon::factory()->count(10)->create();
	$travel = Travel::factory()->create([
		'status'=>TravelStatus::CANCELLED,
		'cancelled_by'=>'Client',
		'cancellation_reason_id'=>CancellationReason::where('model_type','Client')->inRandomOrder()->first()->id	
	]);
	
	
	
	Travel::factory()->create([
		'status'=>TravelStatus::ON_THE_WAY
	]);
	
	Travel::factory()->create()->each(function(Travel $travel){
		/**
		 * @var Payment $payment 
		 */
		$payment = Payment::factory()->create([
		
			'type'=> PaymentType::CASH,
			'travel_id'=>$travel->id ,
			'model_id'=>$travel->client->id ,
			'model_type'=>HHelpers::getClassNameWithoutNameSpace($travel->client)
		]);
		Transaction::factory()->create([
			'amount'=>$amount = $payment->getPrice() * -1 ,
			'type'=>TransactionType::PAYMENT,
			'type_id'=>$payment->id ,
			'model_id'=>$travel->client->id ,
			'model_type'=>HHelpers::getClassNameWithoutNameSpace($travel->client),
			'note_en'=>__('Amount Has Been Subtracted From Your Wallet :amount :currency For Travel # :travelId',['amount'=>number_format($amount),'currency'=>'SAR','travelId'=>$travel->id],'en'),
			'note_ar'=>__('Amount Has Been Subtracted From Your Wallet :amount :currency For Travel # :travelId',['amount'=>number_format($amount),'currency'=>__('SAR',[],'ar'),'travelId'=>$travel->id],'ar')
		]);
	});
	
	SupportTicket::factory()->create([
	
	]);
	
    }
}
