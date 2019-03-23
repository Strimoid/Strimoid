<?php

namespace Strimoid\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /** @var array */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     */
    public function report(Exception $exception): void
    {
        if ($this->shouldReport($exception)) {
            Log::error($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     *
     * @return \Illuminate\Http\Response|JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception)) {
            return $this->renderHttpException($exception);
        } elseif ($exception instanceof OAuthException) {
            return Response::json([
                'error' => $exception->errorType,
                'message' => $exception->getMessage(),
            ], $exception->httpStatusCode);
        }

        if (config('app.debug')) {
            $handler = new \Whoops\Handler\PrettyPageHandler();
            $handler->setEditor('sublime');

            $whoops = new \Whoops\Run();
            $whoops->pushHandler($handler);
            $whoops->allowQuit(false);
            $whoops->writeToOutput(false);

            return response(
                $whoops->handleException($exception)
            );
        }

        return parent::render($request, $exception);
    }
}
