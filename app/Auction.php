<?php

namespace Statsational;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    public function user(){
      return $this->belongsTo('App\User');
    }
}
