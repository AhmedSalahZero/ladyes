<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;
/**
 * * حالة عمليه الدفع وليكن مثلا عملية ناجة او فاشلة او لاتزال معلقة
 */
class PaymentStatus  implements IEnum
{
	public const PENDING = 'pending'; 
	public const SUCCESS = 'success'; 
	public const FAILED = 'failed'; 
	
	public static function all():array 
	{
		return [
			self::PENDING=>__('Pending'),
			self::SUCCESS=>__('Success'),
			self::FAILED=>__('Failed'),
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
