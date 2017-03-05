<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\BraintreeService;

class BraintreeController extends Controller
{
  public function get($id)
  {
    return (new BraintreeService())->getTransaction($id);
  }

  public function create(Request $request)
  {
    $result = (new BraintreeService())->createTransaction(
      $request->input('nonce'),
      $request->input('amount')
    );

    $order = Order::create([
      'customer_name' => $request->input('customerName'),
      'customer_tel'  => $request->input('tel'),
      'currency'      => $request->input('currency'),
      'amount'        => $request->input('amount'),
    ]);

    return response()->json([
      'transaction_id' => $result->transaction->id,
      'order_id' => $order->id,
    ]);
  }
}
