<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function auction(){
      $this->belongsToMany('\App\Auction');
    }

    public function bids(){
      return $this->hasMany('App\Bid');
    }

}
