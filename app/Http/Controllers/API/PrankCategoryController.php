<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\PrankCategoryCollection;
use App\Models\PrankCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrankCategoryController extends APIController
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request): JsonResponse
    {
        $limit = 10;
        if ($request->filled('limit') && is_numeric($request->get('limit'))) $limit = $request->get('limit');
        $list = PrankCategory::query()->filter($request)->paginate($limit);
        return $this->response(PrankCategoryCollection::make($list));
    }
}
