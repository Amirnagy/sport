<?php
namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractMiddlewareResponse
{
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
?>
