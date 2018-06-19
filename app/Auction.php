<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Auction extends Model
{
  protected $casts = [
    'queue' =>  'array'
  ];

  public function user(){
    return $this->belongsTo('App\User');
  }

  public function users(){
    return $this->belongsToMany('App\User');
  }

  public function is_owner(){
    if(Auth::user()->id == $this->user->id){
      return true;
    } else {
      return false;
    }
  }

  public function is_participant(){
    $participants = $this->users->pluck('id');
    $user_id = Auth::user()->id;
    if($this->is_owner()){
      return true;
    }
    foreach($participants as $participant){
      if($participant == $user_id){
        return true;
      }
    }
    return false;
  }

  public function items(){
    return $this->belongsToMany('App\Item');
  }

  public function bids(){
    return $this->hasMany('App\Bid');
  }

  public function messages()
  {
    return $this->hasMany(Message::class);
  }

  public function bidders(){

    // get an array of user objects for all bidders in the auction
    $bidders = array();
    $bidders[$this->user->id] = $this->user;
    foreach( $this->users as $user){
      $bidders[$user->id] = $user;
    }

    // get a list of ids for queued items.
    $queue_ids = array();
    foreach($this->queue as $key => $queue_item){
      if(!in_array($queue_item['id'],$queue_ids)){
        $queue_ids[] = $queue_item['id'];
      }
    }

    // now loop through all items, both queued and completed
    foreach($this->items as $item){
      // we only want to check completed items, so make sure the id is not in the queued item ids array
      if(!in_array($item->id,$queue_ids)){
        $item = Item::find($item->id);
        $high_bid = false;
        // loop through all bids for completed items to get the highest (winning) bid
        foreach($item->bids() as $bid){
          if($bid->amount > $high_bid->amount){
            $high_bid = $bid;
          }
        }
        // now loop through the bidders array, updating their total spend and won items array
        foreach($bidders as $key => $bidder){
          if( !isset( $bidder->spend ) ){
            $bidders[$key]->spend = 0;
            $bidders[$key]->items = array();
          }
          // return $high_bid;
          if( ( $high_bid !== false ) && ( $bidder->id == $high_bid->user_id ) ){
            $bidders[$key]->items[] = $item;
            $bidders[$key]->spend += round($high_bid->amount);
          }
        }
      }
    }

    $spend_list = array();
    foreach($bidders as $bidder){
      $spend_list[$bidder->id] = $bidder->spend;
    }
    arsort($spend_list);
    $sorted_bidders = array();
    foreach($spend_list as $bidder_id => $spend){
      $bidders[$bidder_id]->average = 0;
      if(is_array($bidders[$bidder_id]->items) && count($bidders[$bidder_id]->items) > 0){
        $bidders[$bidder_id]->average = ( $bidders[$bidder_id]->spend / count($bidders[$bidder_id]->items) ) * 100;
      }
      $sorted_bidders[$bidder_id] = $bidders[$bidder_id];
    }

    return $sorted_bidders;

  }

}
