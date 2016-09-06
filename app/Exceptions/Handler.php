<?php namespace Strimoid\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthException;
use Log;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
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
     *
     * @param \Exception $e
     *
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldReport($e)) {
            Log::error($e);
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     *
     * @return \Illuminate\Http\Response|JsonResponse
     */
    public function render($request, Exception $e)
    {
        if ($this->isHttpException($e)) {
            return $this->renderHttpException($e);
        } elseif ($e instanceof OAuthException) {
            return Response::json([
                'error'     => $e->errorType,
                'message'   => $e->getMessage(),
            ], $e->httpStatusCode);
        }

        if (config('app.debug')) {
            $handler = new \Whoops\Handler\PrettyPageHandler();
            $handler->setEditor('sublime');

            $whoops = new \Whoops\Run();
            $whoops->pushHandler($handler);
            $whoops->allowQuit(false);
            $whoops->writeToOutput(false);

            return response(
                $whoops->handleException($e),
                $e->getStatusCode(),
                $e->getHeaders()
            );
        }

        return parent::render($request, $e);
    }
}
