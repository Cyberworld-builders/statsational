<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Item;

class ItemController extends Controller
{

    public function __construct()
    {
      $this->middleware('auth');
    }

    public function switchItem($item_id){
      $user = Auth::User();
      $item = Item::find($item_id);
      $bids = $item->bids;
      // $response = array(
      //   'item'  =>  $item,
      //   'bids'  =>  $bids
      // );
      broadcast(new MessageSent($user, $item, "switchItem"))->toOthers();
      return $item;
    }
}
