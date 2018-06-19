<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Auction;
use App\Item;
use App\Bid;
use App\Message;
use Illuminate\Http\Request;

use App\Events\MessageSent;

class AuctionController extends Controller
{

      public function __construct()
      {
        $this->middleware('auth');
      }

     public function store(Request $request)
     {
       $auction = new Auction;
       $auction->name = request('name');
       $auction->start_time = date('Y-m-d h:i:s',strtotime(request('start_time')));
       $auction->private = request('private');
       $auction->queue = array();
       $user = Auth::User();
       $user->auctions()->save($auction);
       return $auction->id;
     }

     public function addItem(Request $request){
       $item = new Item;
       $item->name = request('name');
       $auction = Auction::find(request('auction_id'));
       $auction->items()->save($item);
       $queue = array();
       if(isset($auction->queue) && count($auction->queue) > 0){
         $queue = $auction->queue;
       }
       $queue[] = $item;
       $auction->queue = $queue;
       $auction->save();
       $user = Auth::User();
       broadcast(new MessageSent($user, "update", "update"))->toOthers();
       return $auction->items;
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
           if( isset( $auction->queue ) && count( $auction->queue ) > 0 ){
             $item = Item::find($auction->queue[count($auction->queue)-1]['id']);
             $bids = $item->bids;
           } else {
             $item = new Item;
             $bids = $item->bids;
           }
           return view('pages.auction',[
             'auction' => $auction,
             'item'  => $item,
             'bids' =>  $bids,
             'user'=>Auth::User()
           ]);
         } else {
           return view('pages.joinAuction',[
             'auction' => $auction,
             'user'=>Auth::User()
           ]);
         }
     }

     public function getAuctionData( $auction_id )
     {
         $auction = Auction::find($auction_id);
         $items = $auction->items;
         if( isset( $auction->queue ) && count( $auction->queue ) > 0 ){
           $item = Item::find($auction->queue[0]['id']);
         } else {
           $item = new Item;
         }
         $item->bids = $item->bids;
         $auction->bidders = $auction->bidders();
         $auction->item = $item;
         return $auction;
     }



    public function new()
    {
        return view('pages.newAuction');
    }

    public function bid(Request $request){
      $user = Auth::User();
      $auction = Auction::find($request->auction_id);
      $item = Item::find($request->item_id);
      $bid = new Bid;
      $bid->amount = $request->bid_amount;
      $bid->user()->associate($user);
      $bid->auction()->associate($auction);
      $bid->item()->associate($item);
      $bid->save();
      $bids = $item->bids();
      $auction = array('auction'=>$this->getAuctionData($auction->id));
      $response = $auction;
      broadcast(new MessageSent($user, "getBids", "bid"))->toOthers();
      return $response;
    }

    public function getUsers($auction_id){
      $auction = Auction::find($auction_id);
      $auction->users[] = $auction->user;
      foreach($auction->users as $i => $user){
        if ( ( time() - strtotime($user->active_at) ) > 60000 ){
          $auction->users[$i]->active = false;
        } else {
          $auction->users[$i]->active = true;
        }
        $auction->users[$i]->active = true;
      }
      return $auction->users;
    }

    public function resetTimer(Request $request){
      $user = Auth::User();
      broadcast(new MessageSent($user, $request->seconds, "timer"))->toOthers();
      return $request->seconds;
    }

    public function startNextItem(Request $request){
      $item = Item::find($request->item_id);
      $auction = Auction::find($request->auction_id);

      $queue_ids = array();

      foreach($auction->queue as $key => $queue_item){
        // while you're in there, remove the current item from the queue
        if($item->id == $queue_item['id']){
          unset($auction->queue[$key]);
        } else {
          if(!in_array($item['id'],$queue_ids)){
            $queue_ids[] = $item['id'];
          }
        }
      }
      // persist the changes in the database
      $this->save();

      $auction = $this->getAuctionData($auction->id);
      return $auction;

      // $queue = $auction->queue;
      // $queue_ids = array();
      // foreach($queue as $key => $item){
      //   if($request->item_id == $item['id']){
      //     unset($queue[$key]);
      //   } else {
      //     $queue_ids[] = $item['id'];
      //   }
      // }
      // $auction->queue = $queue;
      // // $auction->save();
      //
      // $bidders = $auction->users;
      // $bidders[] = $auction->user;
      //
      // foreach($auction->items as $item){
      //   // $item = Item::find($item->id);
      //   $high_bid = false;
      //   // return json_encode($item);
      //   foreach($item->bids() as $bid){
      //     if($bid->amount > $high_bid->amount){
      //       $high_bid = $bid;
      //     }
      //   }
      //   foreach($bidders as $key => $bidder){
      //     if( !isset( $bidder->spend ) ){
      //       $bidders[$key]->spend = 0;
      //       $bidders[$key]->items = array();
      //     }
      //     // return $high_bid;
      //     if( ( $high_bid !== false ) && ( $bidder->id == $high_bid->user_id ) && ( !in_array($item->id,$queue_ids) ) ){
      //       $bidders[$key]->items[] = $item;
      //       $bidders[$key]->spend += round($high_bid->amount);
      //     }
      //   }
      //
      // }
      //
      // $response = array(
      //   'queue' =>  $auction->queue,
      //   'bidders' =>  $bidders
      // );
      // return $response;
    }

}
