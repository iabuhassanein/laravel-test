<?php

namespace App\Exceptions;

use App\Traits\RestExceptionHandlerTrait;
use App\Traits\RestTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    use RestExceptionHandlerTrait;
    use RestTrait;
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
        //
    }

    public function render($request, Throwable $exception)
    {
        if(!$this->isApiCall($request)) {
            $retval = parent::render($request, $exception);
        } else {
            $retval = $this->getJsonResponseForException($request, $exception);
        }

        return $retval;
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($request->expectsJson()) return response()->json(['message' => __('auth.unauthenticated')], 401);
        $guard = \Arr::get($exception->guards(), 0);
        switch ($guard){
            case 'panel':
                $redirectUrl = route('panel.login');
                break;
            default:
                $redirectUrl = '/';
        }
        return redirect()->guest($redirectUrl);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request, $just_messages = true)
    {
        $response = parent::convertValidationExceptionToResponse($e, $request);
        if ($response instanceof JsonResponse) {
            $original = $response->getOriginalContent();
//            if($just_messages){
//                $errs = $original['errors'];
//                $original['errors'] = [];
//                foreach ($errs as $key => $val){
//                    array_push($original['errors'], $val );
//                }
//            }
            $original['message'] = __('validation.message');
            $response->setContent(json_encode($original));
        }
        return $response;
    }
}
