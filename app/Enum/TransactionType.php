<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;
/**
 * * انواع طرق المعاملات المالية وليكن مثلا 
 * * payment , refund , deposit,withdrawal ,Fine, etc 
 */
class TransactionType  implements IEnum
{
	public const PAYMENT = 'payment'; 
	public const REFUND = 'refund'; 
	public const DEPOSIT = 'deposit'; 
	public const WITHDRAWAL = 'withdrawal'; 
	public const FINE = 'fine'; 
	
	public static function all():array 
	{
		return [
			self::PAYMENT=>__('Payment'),
			self::REFUND=>__('Refund'),
			self::DEPOSIT=>__('Deposit'),
			self::WITHDRAWAL=>__('Withdrawal'),
			self::FINE=>__('Fine'),
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
