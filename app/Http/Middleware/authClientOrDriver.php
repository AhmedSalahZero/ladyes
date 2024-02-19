<?php

namespace App\Http\Middleware;

use App\Traits\Api\HasApiResponse;
use Closure;
use Illuminate\Http\Request;

class authClientOrDriver
{
	use HasApiResponse;
	
	
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
		if($request->user('client') || $request->user('driver')){
			return $next($request);
		}
        return $this->apiResponse(__('Unauthenticated',[],getApiLang()));
    }
}
