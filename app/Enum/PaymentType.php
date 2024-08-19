<?php 
namespace App\Enum;

use App\Helpers\HHelpers;
use App\Interfaces\IEnum;
/**
 * * انواع طرق ادفع وليكن مثلا كاش
 */
class PaymentType  implements IEnum
{
	public const CREDIT_CARD = 'credit-card'; 
	public const MASTER_CARD = 'master-card'; 
	public const CASH = 'cash'; 
	public const WALLET = 'wallet'; 
	public const MADA = 'MADA'; 
	public const APPLE_PAY = 'apple-pay'; 
	
	public static function all():array 
	{
		return [
			self::CREDIT_CARD=>__('Credit Card',[],getApiLang()),
			self::MASTER_CARD=>__('Master Card',[],getApiLang()),
			self::APPLE_PAY=>__('Apple Pay',[],getApiLang()),
			self::MADA=>__('MADA',[],getApiLang()),
			self::WALLET=>__('Wallet',[],getApiLang()),
			self::CASH=>__('Cash',[],getApiLang()),
			
		];
	}
	public static function images()
	{
		return [
			self::CREDIT_CARD=>asset('custom/images/cards/credit-card.png'),
			self::MASTER_CARD=>asset('custom/images/cards/master-card.png'),
			self::APPLE_PAY=>asset('custom/images/cards/apple-pay.png'),
			self::MADA=>asset('custom/images/cards/mada.png'),
			self::WALLET=>asset('custom/images/cards/wallet.jpg'),
			self::CASH=>asset('custom/images/cards/cash.jpg'),
		];
	}
	public static function allFormattedForSelect2():array 
	{
		return HHelpers::formatForSelect2(self::all());
	} 
}
