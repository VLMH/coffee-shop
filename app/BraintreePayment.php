<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BraintreePayment extends Model
{
  protected $fillable = ['nonce', 'transaction_id'];

  public function orders()
  {
    return $this->morphMany('App\Order', 'payment');
  }
}
