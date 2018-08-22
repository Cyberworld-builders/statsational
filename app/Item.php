<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    public function auction(){
      $this->belongsToMany('\App\Auction');
    }

    public function bids(){
      return $this->hasMany('App\Bid')->orderBy('updated_at','desc');
    }



}
