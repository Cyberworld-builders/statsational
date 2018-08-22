<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Auction extends Model
{
  protected $casts = [
    'queue' =>  'array',
    'status' =>  'array',
    'settings' =>  'array'

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
    foreach($bidders as $id => $bidder){
      $bidders[$id]->spend = 0;
      $bidders[$id]->item_count = 0;
    }

    // now loop through all items, both queued and completed
    foreach($this->items as $item){
      if($winning_bid = $this->is_owned($item)){
        $bidders[$winning_bid->user_id]->spend += round($winning_bid->amount);
        $bidders[$winning_bid->user_id]->item_count++;
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

  public function is_owned($item){
    $winning_bid = false;
    $queue_ids = array();
    foreach($this->queue as $key => $queue_item){
      if(!in_array($queue_item['id'],$queue_ids)){
        $queue_ids[] = $queue_item['id'];
      }
    }
    if(in_array($item->id,$queue_ids)){
      return false;
    } else {
      $winning_bid = false;
      foreach($item->bids as $bid){
        if($winning_bid === false || $bid->amount > $winning_bid->amount){
          $winning_bid = $bid;
        }
      }
    }
    return $winning_bid;
  }

}
