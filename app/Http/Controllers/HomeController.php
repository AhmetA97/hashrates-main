<?php

namespace App\Http\Controllers;

use App\Http\Requests\HashrateRequest;
use App\Models\Worker;
use App\Services\HashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Calculate hashrate for every day between two dates
     *
     * @return View
     * */
    public function result(HashrateRequest $request, HashService $hashService)
    {
        $result = $hashService->calculateHash($request);

        return view('home', compact(['result', 'request']));
    }
}
