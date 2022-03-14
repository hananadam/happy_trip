<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Convert an authentication exception into an unauthenticated response
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|RedirectResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->isJson() || $request->wantsJson() || $request->ajax() || $request->expectsJson()) {

            $response = [
                'status_code' => Response::HTTP_UNAUTHORIZED,
                'status' => false,
                'message' => __('not authorized')
            ];

            return response()->json($response);
        }

        return redirect()->guest('login');
    }
}
