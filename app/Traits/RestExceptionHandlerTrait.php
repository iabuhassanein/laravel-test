<?php


namespace App\Traits;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

trait RestExceptionHandlerTrait
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonResponseForException(Request $request, $e)
    {
        switch(true) {
            case $this->isModelNotFoundException($e):
                $retval = $this->modelNotFound(trans('messages.not_found'));
                break;
            case $this->isAccessForbiddenException($e):
                $retval = $this->accessDenied(__('auth.access_forbidden'));
                break;
            default:
                $retval = parent::render($request, $e);
        }

        return $retval;
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message='Bad request', $statusCode=400)
    {
        return $this->jsonResponse(['status' => false, 'message' => $message], $statusCode);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function modelNotFound($message='Record not found', $statusCode=404)
    {
        return $this->jsonResponse(['status' => false, 'message' => $message], $statusCode);
    }

    protected function accessDenied($message='Access Denied', $statusCode=403)
    {
        return $this->jsonResponse(['status' => false, 'message' => $message], $statusCode);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function jsonResponse(array $payload=null, $statusCode=404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $e
     * @return bool
     */
    protected function isModelNotFoundException( $e)
    {
        return ($e instanceof NotFoundHttpException) || ($e instanceof ModelNotFoundException);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Throwable $e
     * @return bool
     */
    protected function isAccessForbiddenException( $e)
    {
        return ($e instanceof AccessDeniedHttpException) || ($e instanceof AuthorizationException) || ($e instanceof HttpException && $e->getStatusCode() === 403);
    }

}
