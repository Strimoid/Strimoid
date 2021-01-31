<?php

namespace Strimoid\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    public function report(Throwable $exception): void
    {
        if ($this->shouldReport($exception)) {
            Log::error($exception);
        }

        parent::report($exception);
    }

    public function render($request, Throwable $exception): Response
    {
        if ($this->isHttpException($exception)) {
            return $this->renderHttpException($exception);
        }

        /*
        if ($exception instanceof OAuthException) {
            return Response::json([
                'error' => $exception->errorType,
                'message' => $exception->getMessage(),
            ], $exception->httpStatusCode);
        }
        */

        if (config('app.debug')) {
            $handler = new PrettyPageHandler();
            $handler->setEditor('sublime');

            $whoops = new Run();
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
