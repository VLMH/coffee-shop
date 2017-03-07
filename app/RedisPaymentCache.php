<?php

namespace App;

use Illuminate\Support\Facades\Redis;

use App\Order;

class RedisPaymentCache
{
  const EXPIRY = 300; // 5 minutes

  public function set(Order $order)
  {
    if (!$order) { return null; }

    $key = $this->getKey($order->customer_name, $order->payment_reference_code);
    $value = [
      'name'     => $order->customer_name,
      'tel'      => $order->customer_tel,
      'currency' => $order->currency,
      'amount'   => $order->amount,
      'code'     => $order->payment_reference_code,
    ];

    Redis::setex($key, EXPIRY, json_encode($value));

    return $order;
  }

  public function get($customerName, $paymentCode)
  {
    if (!$cache = Redis::get($this->getKey($customerName, $paymentCode))) {
      $this->set($this->getOrder($customerName, $paymentCode));
      $cache = Redis::get($this->getKey($customerName, $paymentCode))
    }

    return $cache ? json_decode($cache) : null;
  }

  private function getKey($customerName, $paymentCode)
  {
    return $customerName . '|' . $paymentCode;
  }

  private function getOrder($customerName, $paymentCode)
  {
    return Order::findByNameAndCode($customerName, $paymentCode);
  }
}
