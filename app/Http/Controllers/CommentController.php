<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiCommentStore;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function index(Request $req)
    {
        $query = Comment::query();
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

        return CommentResource::collection($query);
    }

    public function show($id)
    {
        $data = Comment::where('id', $id)->first();

        $query = $this->__prepareLoadRelation($data);

        return new CommentResource($query);
    }

    public function create(ApiCommentStore $req)
    {
        DB::beginTransaction();
        try{

            $comment = Comment::create([
                'content' => $req['content'],
                'news_id' => $req['news_id'],
                'created_by' => Auth::id()
            ]);

            DB::commit();

            return new CommentResource($comment);
        }catch(\Exception $e){
            DB::rollBack();
            // return $this->errorResponse($e->getMessage(), 500);
            return $this->sendError($e->getMessage(), null, 500);
        }
    }

    public function update(ApiCommentStore $req, $id)
    {
        DB::beginTransaction();
        try{
            $comment = Comment::where('id', $id)->firstOrFail();
            $comment->content = $req['content'];
            $comment->news_id = $req['news_id'];
            $comment->updated_by = Auth::id();
            $comment->save();

            DB::commit();

            return new CommentResource($comment);
        }catch(\Exception $e){
            DB::rollBack();
            // return $this->errorResponse($e->getMessage(), 500);
            return $this->sendError($e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $comment = Comment::where('id', $id)->firstOrFail();
            $comment->delete();

            DB::commit();

            return ["Success" => true];

        }catch(\Exception $e){
            DB::rollBack();
            // return $this->errorResponse($e->getMessage(), 500);
            return $this->sendError($e->getMessage(), null, 500);
        }
    }
}
