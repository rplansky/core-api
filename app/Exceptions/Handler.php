<?php

namespace Boitata\Exceptions;

use Boitata\Http\CorsService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        TokenMismatchException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @throws Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ('testing' === app('env')) {
            throw $exception;
        }

        return $this->renderForApi($request, $exception);
    }

    /**
     * @param Request   $request
     * @param Exception $e
     *
     * @return JsonResponse
     */
    protected function renderForApi($request, Exception $e)
    {
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage() ?:
                Response::$statusTexts[$statusCode] ?? 'Error';
        } else {
            $statusCode = 500;
            $message = 'Unknown error occurred';
        }

        $payload = compact('message');

        if (true === config('app.debug')) {
            $payload = array_merge(
                $payload,
                [
                    'exception' => [
                        'class'   => get_class($e),
                        'message' => $e->getMessage(),
                        'file'    => $e->getFile(),
                        'line'    => $e->getLine(),
                        'trace'   => array_slice($e->getTrace(), 0, 10),
                    ],
                    'request' => [
                        'url'   => $request->url(),
                        'input' => $request->input(),
                    ],
                ]
            );
        }

        $response = response()->json($payload, $statusCode);
        $corsService = app(CorsService::class);

        return $corsService->handle($request, $response);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }
}
