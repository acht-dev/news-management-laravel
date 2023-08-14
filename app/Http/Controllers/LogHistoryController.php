<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogHistoryResource;
use App\Models\LogHistory;
use Illuminate\Http\Request;

class LogHistoryController extends Controller
{
    public function index(Request $req)
    {
        $query = LogHistory::query();
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

        return LogHistoryResource::collection($query);
    }

    public function show($id)
    {
        $data = LogHistory::where('id', $id)->first();

        $query = $this->__prepareLoadRelation($data);

        return new LogHistoryResource($query);
    }
}
