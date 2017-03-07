<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalPayment extends Model
{
  protected $fillable = ['transaction_id'];

  public function orders()
  {
    return $this->morphMany('App\Order', 'payment');
  }
}
