<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;
/**
 * * انواع طرق المعاملات المالية وليكن مثلا 
 * * payment , refund , deposit,withdrawal ,Fine , bonus , etc 
 */
class TransactionType  implements IEnum
{
	public const PAYMENT = 'payment'; 
	public const DEPOSIT = 'deposit'; 
	public const FINE = 'fine'; 
	public const BONUS = 'bonus'; 
	public const REFUND = 'refund'; 
	public const WITHDRAWAL = 'withdrawal'; 
	
	public static function all():array 
	{
		return [
			self::PAYMENT=>__('Payment'),
			self::REFUND=>__('Refund'),
			self::DEPOSIT=>__('Deposit'),
			self::WITHDRAWAL=>__('Withdrawal'),
			self::FINE=>__('Fine'),
			self::BONUS=>__('Bonus'),
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
