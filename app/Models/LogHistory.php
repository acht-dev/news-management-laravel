<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'log_histories';
    protected $guarded = ['id'];

    public function news()
    {
        return $this->belongsTo(News::class, 'news_id')->withTrashed();;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by')->withTrashed();
    }
}
