<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auctions = \App\Auction::all();
        return view('home')->with('auctions',$auctions);
    }
}
