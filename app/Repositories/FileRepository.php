<?php

namespace App\Repositories;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileRepository
{

    public function saveFile($data, $file, $folderPath)
    {
        $uploadFolder = $data["folder_path"] ?? "/";
        $path = $file->store($uploadFolder, "public");

        $newFile = new File();
        $newFile->url = Storage::disk("public")->url($path);
        $newFile->mime_type = $data["mime_type"] ?? null;
        $newFile->size = $data["size"] ?? null;
        $newFile->save();

        return $newFile;
    }
}
