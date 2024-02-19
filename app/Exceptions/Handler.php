<?php

namespace App\Exceptions;

use App\Traits\Api\HasApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
	use HasApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
		
		$this->renderable(function (NotFoundHttpException $e, Request $request) {
			if ($request->is('api/*')) {
				return response()->json([
					'status'=>Response::HTTP_NOT_FOUND,
					'data'=>[],
					'message' => __('Record Not Found',[],getApiLang()),
				], Response::HTTP_NOT_FOUND);
			}
		});
		$this->renderable(function (\Exception $e, Request $request) {
			if ($request->is('api/*')) {
				return response()->json([
					'status'=>Response::HTTP_INTERNAL_SERVER_ERROR,
					'data'=>[],
					'message' => $e->getMessage(),
				], Response::HTTP_INTERNAL_SERVER_ERROR);
			}
		});
        $this->reportable(function (Throwable $e) {
            //
        });
    }
	protected function unauthenticated($request, AuthenticationException $exception)
{
    if ($request->expectsJson()) {
        return $this->apiResponse(__('Unauthenticated',[],getApiLang()));
    }

    return redirect()->guest($exception->redirectTo() ?? route('login'));
}

}
