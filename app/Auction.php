<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Auction extends Model
{

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
    return $this->hasMany('App\Item');
  }



}
