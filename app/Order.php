<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = ['customer_name', 'customer_tel', 'currency', 'amount', 'payment_id', 'payment_type', 'payment_reference_code'];

  public static function findByNameAndCode($name, $code)
  {
    return DB::table('orders')
      ->where('customer_name', '=', $name)
      ->where('payment_reference_code', '=', $code)
      ->first();
  }

  public function payment()
  {
    return $this->morphTo();
  }
}
