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

}
