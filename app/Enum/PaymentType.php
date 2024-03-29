<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;
/**
 * * انواع طرق ادفع وليكن مثلا كاش
 */
class PaymentType  implements IEnum
{
	public const CASH = 'cash'; 
	public const WALLET = 'wallet'; 
	public const MADA = 'MADA'; 
	
	public static function all():array 
	{
		return [
			self::CASH=>__('Cash'),
			self::WALLET=>__('Wallet'),
			self::MADA=>__('MADA'),
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
