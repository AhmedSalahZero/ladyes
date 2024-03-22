<?php

namespace App\Models;

use App\Traits\Accessors\IsBaseModel;
use App\Traits\Models\HasLatitudeAndLatitude;
use App\Traits\Models\HasSingleCategories;
use App\Traits\Models\HasSingleDescription;
use App\Traits\Scope\HasDefaultOrderScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * * العناوين المفضلة للعميل وليكن مثلا منزلة و جهة العمل الخ
 */
class Address extends Model
{
    use IsBaseModel ;
    use HasDefaultOrderScope ;
    use HasSingleCategories;
    use HasSingleDescription ;
    use HasLatitudeAndLatitude ;
    use HasFactory ;
	
	protected $guarded = [
		'id'
	];
	public function client()
	{
		return $this->belongsTo(Client::class , 'client_id','id');
	}
}
