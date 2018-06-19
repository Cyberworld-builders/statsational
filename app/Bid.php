<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{

  protected $fillable = ['bid'];

  public function user(){
    return $this->belongsTo('App\User');
  }

  public function auction(){
    return $this->belongsTo('App\Auction');
  }

  public function item(){
    return $this->belongsTo('App\Item');
  }


}
