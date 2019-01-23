<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use App\Http\Responses\BaseResponse;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $response = parent::render($request, $exception);

        // 401 = Unauthorized
        // 403 = Unauthorized / Permission Denied

        if (in_array($response->getStatusCode(), [ 401, 403 ])){
            $original = $response->getOriginalContent();
            $errorResponse = BaseResponse::denied($response->getStatusCode(), $original['message']);
            return $response->setContent($errorResponse->toJson());
        }

        return $response;
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $response = parent::convertValidationExceptionToResponse($e, $request);

        // API REQUEST
        if ($request->ajax()){
            $original = $response->getOriginalContent();
            $errorResponse = BaseResponse::errorData($original['errors'], $original['message']);
            return $response->setContent($errorResponse->toJson());
        }

        return $response;
    }
}
