<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\LogHistory;
use Illuminate\Support\Facades\Storage;

class NewsRepository
{

    public function saveLogHistory($data)
    {
        LogHistory::create([
            'description' => $data["description"],
            'news_id' => $data["news_id"],
            'created_by' => $data['created_by']
        ]);
    }
}
