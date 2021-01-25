<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\PrankIdeaCollection;
use App\Models\PrankCategory;
use App\Models\PrankIdea;
use Illuminate\Http\Request;

class PrankIdeaController extends APIController
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){
        $limit = 10;
        if ($request->filled('limit') && is_numeric($request->get('limit'))) $limit = $request->get('limit');
        $list = PrankIdea::query()->filter($request)->paginate($limit);
        return $this->response(PrankIdeaCollection::make($list));
    }

    public function category(Request $request, $prankCategory){
        $category = PrankCategory::query()->where('slug', $prankCategory)->firstOrFail();
        $limit = 10;
        if ($request->filled('limit') && is_numeric($request->get('limit'))) $limit = $request->get('limit');
        $list = $category->prankIdeas()->search($request)->paginate($limit);
        return $this->response(PrankIdeaCollection::make($list));

    }
}
