<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function finalResponse($message = "success",
                                $statusCode = 200,
                                $data = null,
                                $pagnation = null,
                                $errors = null) : JsonResponse
    {
        return response()->json([
            "message" => $message,
            "data" => $data,
            'pagination' => $pagnation,
            'errors' => $errors
        ],$statusCode);
    }


}
