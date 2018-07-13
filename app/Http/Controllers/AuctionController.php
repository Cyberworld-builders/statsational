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
       $auction->rules = request('rules');
       $auction->start_time = date('Y-m-d h:i:s',strtotime(request('start_time')));
       $auction->private = request('private');
       $auction->manual_next = request('manual_next');
       $auction->bid_increment = request('bid_increment');
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
           $current_item = Item::find($auction->queue[0]['id']);
         } else {
           $current_item = new Item;
         }

         if( count($items) > 0) {
           unset($auction->items);
           $auction->items = [];
           $items_reversed = [];
           $last_item_key = (count($items)-1);
           for($i=$last_item_key;$i>=0;$i--){
             $item = $items[$i];
             if( isset($item->bids) ){
               if( count($item->bids) > 0 ) {
                   foreach($item->bids as $key => $bid){
                     $item->bids[$key]->amount = round($bid->amount);
                   }
               }

             }
             $items_reversed[] = $item;
           }
           $auction->items = $items_reversed;
         }

         $current_item->bids = $current_item->bids;
         $auction->bidders = $auction->bidders();
         $auction->item = $current_item;

         return $auction;
     }

     public function getBidderData( Request $request )
     {
         $auction = Auction::find($request->auction_id);
         $bidders = $auction->bidders();
         $bidder = $bidders[$request->bidder_id];
         $owned_items = array();

         foreach($auction->items as $item){
           if($winning_bid = $auction->is_owned($item)){
             if ($winning_bid->user_id == $request->bidder_id){
               // $item->cost = rount($winning_bid->amount);
               $owned_items[] = array(
                 'item'=>$item,
                 'winning_bid'=>$winning_bid
               );
             }
           }
         }
         $bidder->owned_items = $owned_items;
         return $bidder;
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
        if( $item->id != $queue_item['id'] ){
          $queue_ids[] = $queue_item['id'];
        }
      }
      $queue = array();
      foreach($auction->items as $queue_item){
        if(in_array($queue_item['id'],$queue_ids)){
          $queue[] = $queue_item;
        }
      }
      $auction->queue = $queue;
      $auction->save();
      $user = Auth::User();
      $auction = $this->getAuctionData($auction->id);
      $debug = $queue;
      broadcast(new MessageSent($user, "update", "update"))->toOthers();
      // return array('queue_ids'=>$queue_ids,'queue'=>$queue,'auction'=>$auction);
      return $auction;

    }

}
