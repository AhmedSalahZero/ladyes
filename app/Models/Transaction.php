<?php

namespace App\Models;

use App\Enum\TransactionType;
use App\Helpers\HDate;
use App\Helpers\HHelpers;
use App\Traits\Models\HasAmount;
use App\Traits\Models\HasCreatedAt;
use App\Traits\Models\HasModelType;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * * عباره عن الحركات المالية التي تحدث وليكن مثلا بسبب 
 * * payment , deposit ,refund , etc
 * * TransactionType::class for all values
 */
class Transaction extends Model
{
    use HasFactory,HasCreatedAt, HasDefaultOrderScope,HasAmount,HasModelType ;
	protected $guarded = [
		'id'
	];

	/**
	 * * هل هي payment , refund , find etc
	 */
	public function getType():string 
	{
		return $this->type ;
	}
	public function getTypeFormatted($lang):string 
	{
		$transactionTypes = TransactionType::all();
		return $transactionTypes[$this->getType()];
	}
	public function getAmount()
	{
		return $this->amount ?: 0 ; 
	}
	public function getNote(string $lang = null)
	{
		$lang = is_null($lang) ? getApiLang() : $lang ;
		return $this['note_'.$lang];
	}
	public function getAmountFormatted()
	{
		return number_format($this->getAmount(),0);
	}
	public function payment()
	{
		return $this->belongsTo(Transaction::class,'type_id','id')->where('transactions.type',TransactionType::PAYMENT);
	}
	public function client()
	{
		return $this->belongsTo(Client::class,'model_id','id')->where('model_type',HHelpers::getClassNameWithoutNameSpace(new Client));
	}
	public function driver()
	{
		return $this->belongsTo(Driver::class,'model_id','id')->where('model_type',HHelpers::getClassNameWithoutNameSpace(new Driver));
	}
	/**
	 * * اسم العميل او السائق
	 */
	public function getUserName():string 
	{
		$user = ('App\Models\\'.$this->getModelType())::find($this->model_id);
		return $user ? $user->getFullName() : __('N/A');
	}	
	/**
	 * * رقم العميل او السائق
	 */
	public function getUserPhone():string 
	{
		$user = ('App\Models\\'.$this->getModelType())::find($this->model_id);
		return $user ? $user->getPhone() : __('N/A');
	}
	public function getCreatedAtFormatted()
	{
		return HDate::formatForView($this->getCreatedAt(),false);
	}
	
	
}
