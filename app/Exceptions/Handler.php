<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Validation\UnauthorizedException;
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
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        switch (true) {
            case $exception instanceof AuthenticationException:
                return response()->json([
                    'errors' => [
                        'title' => 'Unauthenticated',
                    ]
                ], Response::HTTP_UNAUTHORIZED);
            case $exception instanceof AuthorizationException:
                return response()->json([
                    'errors' => [
                        'title' => 'Unauthorized',
                    ]
                ], Response::HTTP_UNAUTHORIZED);
            case $exception instanceof InvalidSignatureException:
                return response()->json([
                    'errors' => [
                        'title' => 'Invalid signature',
                    ]
                ], Response::HTTP_FORBIDDEN);
        }
        return parent::render($request, $exception);
    }
}
