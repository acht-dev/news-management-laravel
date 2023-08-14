<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $req)
    {
        $query = Category::query();
        $limit = $req->query('limit');

        $query = $this->__prepareQuerySearchAbleList($query);

        if ($req->query('type') == 'pagination') {
            $query = $query->paginate($limit);
        } else {
            if($limit) {
                $query = $query->limit($limit)->get();
            }else {
                $query = $query->get();
            }
        }

        $query = $this->__prepareLoadRelation($query);

        return CategoryResource::collection($query);
    }

    public function show($id)
    {
        $data = Category::where('id', $id)->first();

        $query = $this->__prepareLoadRelation($data);

        return new CategoryResource($query);
    }
}
