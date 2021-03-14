<?php

namespace App\Exceptions;

use Flugg\Responder\Exceptions\ConvertsExceptions;
use Flugg\Responder\Exceptions\Http\HttpException;
use Flugg\Responder\Exceptions\Http\PageNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Laravel\Passport\Exceptions\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    use ConvertsExceptions;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        OAuthServerException::class,
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Throwable $exception
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
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if (env('APP_DEBUG') == true) {
            //return parent::render($request, $exception);
        }
        $rendered = parent::render($request, $exception);
        $this->convertDefaultException($exception);

        $this->convert($exception, [
            PageNotFoundException::class => ResourceNotFoundException::class,
        ]);

        if (!$exception instanceof HttpException) {
            $errorCode = 'internal_error';

            if ($exception instanceof OAuthServerException) {
                $errorCode = $exception->getPrevious() ? $exception->getPrevious()->getErrorType() : null;
            } else if ($exception instanceof ThrottleRequestsException) {
                $errorCode = 'too_many_request';
            }

            $exception = new ApiHttpException($rendered->getStatusCode(), $errorCode, $exception->getMessage(), $this->getHeaders($exception));

        }
        return $this->renderResponse($exception);
    }


    /**
     * Get the headers from the exception.
     *
     * @param Throwable $exception
     *
     * @return array
     */
    protected function getHeaders(Throwable $exception)
    {
        return $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];
    }
}
