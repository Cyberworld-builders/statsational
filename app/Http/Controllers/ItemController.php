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
      $queue = [$item];

      foreach($auction->queue as $entry){
        if($entry['id'] != $item->id){
          $queue[] = $entry;
        }
      }
      // $auction->item = $item;
      $auction->queue = $queue;
      $auction->save();
      // broadcast(new MessageSent($user, $item, "switchItem"))->toOthers();
      return $auction;
    }
}
