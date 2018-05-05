<?php

namespace Statsational\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Statsational\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('pages.auction');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function new()
     {
         return view('pages.newAuction');
     }

     public function create()
     {



     }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $auction = new Auction;
      $auction->name = request('name');
      $auction->start_time = date('Y-m-d h:i:s',strtotime(request('start_time')));
      $auction->private = request('private');

      $user = Auth::User();
      $user->auctions()->save($auction);

      return $auction;
    }

    /**
     * Display the specified resource.
     *
     * @param  \Statsational\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function show( $auction_id )
    {
        $auction = Auction::find($auction_id);
        // var_dump($auction);
        if($auction->exists()){
          return view('pages.auction',['auction' => $auction]);
        } else {
          // return view('pages.newAuction');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Statsational\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function edit(Auction $auction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Statsational\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Auction $auction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Statsational\Auction  $auction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Auction $auction)
    {
        //
    }
}
