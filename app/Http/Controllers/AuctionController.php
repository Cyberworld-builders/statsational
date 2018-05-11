<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Auction;
use App\Item;
use Illuminate\Http\Request;

class AuctionController extends Controller
{

     public function store(Request $request)
     {
       $auction = new Auction;
       $auction->name = request('name');
       $auction->start_time = date('Y-m-d h:i:s',strtotime(request('start_time')));
       $auction->private = request('private');
       $user = Auth::User();
       $user->auctions()->save($auction);
       return $auction->id;
     }

     public function addItem(Request $request){

       $item = new Item;
       $item->name = request('name');

       $auction = Auction::find(request('auction_id'));
       // $auction->items()->save($item);

       return $auction->items;

       // return $auction;

     }

     public function join(Request $request)
     {
       $user = Auth::User();
       $auction = Auction::find($request->auction_id);
       $auction->users()->save($user);
       return $auction->id;
     }

    public function show( $auction_id )
    {
        $auction = Auction::find($auction_id);
        if($auction->is_participant()){
          return view('pages.auction',['auction' => $auction]);
        } else {
          return view('pages.joinAuction',['auction' => $auction]);
        }

    }

}
