<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
	const DEFAULT_PAGINATION_LENGTH_FOR_ADMIN = 10 ;
	
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
