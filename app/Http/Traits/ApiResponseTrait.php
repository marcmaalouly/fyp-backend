<?php

namespace App\Http\Traits;

trait ApiResponseTrait {
    public function error() {
        return 'sendError';
    }

    public function success() {
        return 'sendResponse';
    }
}
