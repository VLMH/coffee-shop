<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Order;
use App\BraintreePayment;
use App\BraintreeService;

class BraintreeController extends Controller
{
  public function get($id)
  {
    return (new BraintreeService())->getTransaction($id);
  }

  public function create(Request $request)
  {
    $order = Order::create([
      'customer_name' => $request->input('customerName'),
      'customer_tel'  => $request->input('tel'),
      'currency'      => $request->input('currency'),
      'amount'        => $request->input('amount'),
    ]);

    $payment = BraintreePayment::create(['nonce' => $request->input('nonce')]);

    $payment->orders()->save($order);

    $result = (new BraintreeService())->createTransaction(
      $request->input('nonce'),
      $request->input('amount'),
      $request->input('currency')
    );

    $payment->fill(['transaction_id' => $result->transaction->id])->save();
    $order->fill(['payment_reference_code' => $result->transaction->id])->save();

    return response()->json([
      'transaction_id' => $result->transaction->id,
      'order_id' => $order->id,
    ]);
  }
}
