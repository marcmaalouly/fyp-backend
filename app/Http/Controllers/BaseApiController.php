<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseApiController extends Controller
{
    /**
     * Return Success responses
     *
     * @param $result
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($result, $message = "", $code = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    /**
     * Return error response.
     *
     * @param $message
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($message, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
