<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $fillable = ['customer_name', 'customer_tel', 'currency', 'amount'];
}
