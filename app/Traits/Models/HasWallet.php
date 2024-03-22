<?php 
namespace App\Traits\Models;

trait HasWallet 
{
	/**
	 * *  الرصيد المتاح في المحفظة حاليا 
	 */
	public function getTotalWalletBalance()
	{
		return $this->current_wallet_balance ?: 0  ;
	}
	
}
