<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Item;
use App\Auction;

class ItemController extends Controller
{

    public function __construct()
    {
      $this->middleware('auth');
    }

    public function switchItem(Request $request){

      $auction = Auction::find($request->auction_id);
      $item = Item::find($request->item_id);



      if(isset($auction->queue[1])){

        $status = $auction->status;
        if(!isset($status)){
          $status = array(
            'time_remaining'  =>  $auction->bid_timer,
            'item_end_time' =>  time() + $auction->bid_timer,
            'status'  =>  array(
              'label' =>  "Not Started",
              'in_progress' =>  false
            )
          );
        } else {
          $status['time_remaining'] = $auction->bid_timer;
          $status['item_end_time'] =  time() + $auction->bid_timer;
        }
        $auction->status = $status;

        $queue = [$item];

        foreach($auction->queue as $entry){
          if($entry['id'] != $item->id){
            $queue[] = $entry;
          }
        }
        $auction->queue = $queue;
        $auction->save();
      }

      return $auction;
    }
}
