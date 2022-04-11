<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailConnectionRequest;
use App\Models\Language;
use App\Models\Position;
use App\Services\MailConnectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MailConnectionController extends Controller
{
    protected $service;

    /**
     * @param MailConnectionService $service
     */
    public function __construct(MailConnectionService $service)
    {
        $this->service = $service;
    }

    public function connect(MailConnectionRequest $request, Position $position, Language $language)
    {
        $this->service->connect($request);
    }

    public function fetch(Position $position, Language $language)
    {
        $this->service->fetch($position, $language);
    }
}
