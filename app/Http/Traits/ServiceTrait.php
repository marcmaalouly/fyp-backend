<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait ServiceTrait
{
    public function error($data = [], $code = 401)
    {
        return [
            'status' => 'sendError',
            'data' => $data,
            'code' => $code
        ];
    }

    public function success($data = [], $message = '')
    {
        return [
            'status' => 'sendResponse',
            'data' => $data,
            'message' => $message
        ];
    }

    public function validate(Request $request)
    {
        return $request->validated();
    }
}
