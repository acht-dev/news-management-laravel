<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $customSearchable = [
        // [
        //     'morph' => 'refable',
        //     'class' => Appointment::class,
        //     'searchable' => []
        // ]
    ];

    public $searchAble = [];

    public function sendResponse($result, $message)
    {
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    public function __prepareLoadRelation($row)
    {
        $relations = request('relations', '');
        if (!empty($relations)) {
            $exp = explode(',', $relations);
            $rel = [];
            foreach ($exp as $relation) {
                if (!empty(trim($relation))) {
                    $rel[] = trim($relation);
                }
            }
            if (!empty($rel)) {
                $row->load($rel);
            }
        }

        return $row;
    }

    public function __prepareQuerySearchAbleList($query)
    {

        if ($q = request('q')) {
            $query->where(function ($qq) use ($q) {
                foreach ($this->searchAble as $v) {
                    if (Str::contains($v, '.')) {
                        $ex = explode('.', $v);

                        $rel = implode('.', array_values(array_slice($ex, 0, count($ex) - 1)));

                        $qq->orWhereHas($rel, function ($qqq) use ($q, $ex) {
                            $qqq->whereRaw('LOWER(' . $ex[count($ex) - 1] . ') like ?', ['%' . strtolower($q) . '%']);
                        });
                    } else {
                        $qq->orWhereRaw('LOWER(' . $v . ') like ?', ['%' . strtolower($q) . '%']);
                    }
                }
                $this->additionalSearchable($qq, $q);
            });
        }

        return $query;
    }

    public function additionalSearchable($query, $q)
    {
        foreach ($this->customSearchable as $data) {
            foreach ($data['searchable'] as $v) {
                $query->orWhereHasMorph($data['morph'], $data['class'], function ($qq) use ($q, $v) {
                    if (Str::contains($v, '.')) {
                        $ex = explode('.', $v);

                        $rel = implode('.', array_values(array_slice($ex, 0, count($ex) - 1)));

                        $qq->whereHas($rel, function ($qqq) use ($q, $ex) {
                            $qqq->whereRaw('LOWER(' . $ex[count($ex) - 1] . ') like ?', ['%' . strtolower($q) . '%']);
                        });
                    } else {
                        $qq->whereRaw('LOWER(' . $v . ') like ?', ['%' . strtolower($q) . '%']);
                    }
                });
            }
        }
    }
}
