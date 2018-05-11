<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function auction(){
      $this->belongsTo('\App\Auction');
    }
}
