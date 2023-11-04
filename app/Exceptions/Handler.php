<?php

namespace App\Exceptions;

use App\Http\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return Response::error(__('Not found'), $e->getStatusCode());
            }
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                $header = $request->header('Authorization');

                if ($header === null || $header === '') {
                    return Response::error(__('Unauthenticated'), Response::FORBIDDEN);
                } else {
                    return Response::error(__('Unauthenticated'), Response::UNAUTHORIZED);
                }
            }
        });

        $this->renderable(function (ValidationException $e, $request) {
            if ($request->is('api/*')) {
                $errors = [];

                foreach ($e->errors() as $field => $messages) {
                    $errors[] = [
                        'property' => $field,
                        'errors' => $messages,
                    ];
                    if ($request->is('api/*')) {
                        $errors = [];

                        foreach ($e->errors() as $field => $messages) {
                            $errors[] = [
                                'property' => $field,
                                'errors' => $messages,
                            ];
                        }

                        return Response::error($errors, Response::VALIDATION_ERROR);
                    }
                }

                return Response::error($errors, Response::VALIDATION_ERROR);
            }
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                if (method_exists($e, 'getStatusCode')) {
                    $code = $e->getStatusCode();
                } else {
                    $code = Response::INTERNAL_ERROR;
                }

                return Response::error(__($e->getMessage()), $code);
            }
        });
    }
}
