<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiNewsRequestStore;
use App\Http\Resources\NewsResource;
use App\Models\Comment;
use App\Models\File;
use App\Models\News;
use App\Repositories\FileRepository;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function __construct(FileRepository $filRepository, NewsRepository $newsRepository)
    {
        $this->fileRepository = $filRepository;
        $this->newsRepository = $newsRepository;
    }

    public function index(Request $req)
    {
        $query = News::query();
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

        return NewsResource::collection($query);
    }

    public function show($id)
    {
        $data = News::where('id', $id)->first();

        $query = $this->__prepareLoadRelation($data);

        return new NewsResource($query);
    }

    public function create(ApiNewsRequestStore $req)
    {
        DB::beginTransaction();
        try{
            $image = $req->file("news_image");
            if($image) {
                $uploadFolder = "news/images";
                $fileData = [
                    "mime_type" => $image->getMimeType(),
                    "size" => $image->getSize(),
                    "folder_path" => $uploadFolder
                ];

                $dataFile = $this->fileRepository->saveFile($fileData, $image, $uploadFolder);

                $news = News::create([
                    'title' => $req['title'],
                    'content' => $req['content'],
                    'status' => $req['status'],
                    'category_id' =>  $req['category_id'],
                    'news_image_id' => $dataFile->id ?? null,
                ]);

                $data = [
                    'description' => 'Membuat Berita ' . $req['title'],
                    'news_id' => $news->id,
                    'created_by' => Auth::id()
                ];

                $this->newsRepository->saveLogHistory($data);

                DB::commit();

                return new NewsResource($news);
            }
        }catch(\Exception $e){
            DB::rollBack();
            // return $this->errorResponse($e->getMessage(), 500);
            return $this->sendError($e->getMessage(), null, 500);
        }
    }

    public function update(ApiNewsRequestStore $req, $id)
    {
        DB::beginTransaction();
        try{
            $image = $req->file("news_image");
            if($image) {
                $uploadFolder = "news/images";
                $fileData = [
                    "mime_type" => $image->getMimeType(),
                    "size" => $image->getSize(),
                    "folder_path" => $uploadFolder
                ];

                $dataFile = $this->fileRepository->saveFile($fileData, $image, $uploadFolder);

                $news = News::where('id', $id)->firstOrFail();
                $news->title = $req['title'];
                $news->content = $req['content'];
                $news->status = $req['status'];
                $news->category_id = $req['category_id'];
                $news->news_image_id = $dataFile->id ?? null;
                $news->save();

                $data = [
                    'description' => 'Mengupdate Berita ' . $req['title'],
                    'news_id' => $news->id,
                    'created_by' => Auth::id()
                ];

                $this->newsRepository->saveLogHistory($data);

                DB::commit();

                return new NewsResource($news);
            }
        }catch(\Exception $e){
            DB::rollBack();
            // return $this->errorResponse($e->getMessage(), 500);
            return $this->sendError($e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        $checkUser = Auth::user()->getRoleNames()->toArray();

        if(!in_array('admin', $checkUser)) {
            return $this->sendError("Hanya Admin Yang Bisa Menghapus.", null, 403);
        }

        DB::beginTransaction();
        try{
            $news = News::where('id', $id)->firstOrFail();

            File::where('id', $news->news_image_id)->delete();
            Comment::where('news_id', $news->id)->delete();

            $data = [
                'description' => 'Mengupdate Berita ' . $news->title,
                'news_id' => $news->id,
                'created_by' => Auth::id()
            ];

            $this->newsRepository->saveLogHistory($data);

            $news->delete();

            DB::commit();

            return ["Success" => true];

        }catch(\Exception $e){
            DB::rollBack();
            // return $this->errorResponse($e->getMessage(), 500);
            return $this->sendError($e->getMessage(), null, 500);
        }
    }
}
