<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class APIController extends Controller
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode): APIController
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function response($data, $statusCode = 200): JsonResponse
    {
        $this->setStatusCode($statusCode);
        $headers = ['Content-type' => 'application/json; charset=utf-8'];
        return response()->json($data, $this->getStatusCode(), $headers, JSON_UNESCAPED_UNICODE);
    }
}
