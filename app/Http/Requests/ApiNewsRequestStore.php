<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ApiNewsRequestStore extends FormRequest
{
    public function authorize()
    {
        $checkUser = Auth::user()->getRoleNames()->toArray();

        if(!in_array('admin', $checkUser)) {
            return false;
        }
        return true;
    }

    public function rules()
    {
        return [
            "title" => "required",
            "content" => "required",
            "status" => "required|integer",
            "category_id" => "required|exists:categories,id",
            "news_image" => "nullable|image|mimes:jpeg,jpg,png|max:5000",
        ];
    }
}
