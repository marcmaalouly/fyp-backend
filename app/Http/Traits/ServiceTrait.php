<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait ServiceTrait
{
    public function error($data = [], $code = 401)
    {
        $response = [
            'status' => 'Error',
            'data' => $data,
            'code' => $code
        ];

        return response()->json($response, $code);
    }

    public function success($data = [], $message = '')
    {
        $response = [
            'status' => 'Success',
            'data' => $data,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function validate(Request $request)
    {
        return $request->validated();
    }
}
