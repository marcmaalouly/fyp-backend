<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $service;

    /**
     * @param DashboardService $service
     */
    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->get();
    }
}
