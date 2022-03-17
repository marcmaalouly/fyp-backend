<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait ServiceTrait
{
    public function error()
    {
        return 'sendError';
    }

    public function success()
    {
        return 'sendResponse';
    }

    public function validate(Request $request)
    {
        return $request->validated();
    }
}
