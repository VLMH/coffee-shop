<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\PaypalService;
use App\PaypalPayment;
use App\Order;
use App\RedisPaymentCache;

class PaypalController extends Controller
{
  public function create(Request $request)
  {
    $order = Order::create([
      'customer_name' => $request->input('customerName'),
      'customer_tel'  => $request->input('tel'),
      'currency'      => $request->input('currency'),
      'amount'        => $request->input('amount'),
    ]);

    $payment = PaypalPayment::create();

    $payment->orders()->save($order);

    $paypalService = new PaypalService();
    $apiContext = $paypalService->getApiContext();
    $ppPayment = $paypalService->buildPayment(
      $this->getPayPalCardType($request->input('cardType')),
      $request->input('cardNumber'),
      $request->input('expMonth'),
      $request->input('expYear'),
      $request->input('cvv'),
      $request->input('firstName'),
      $request->input('lastName'),
      strtoupper($request->input('currency')),
      $request->input('amount')
    );
    try {
      $ppPayment->create($apiContext);
    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], 400);
    }

    $order->fill(['payment_reference_code' => $ppPayment->getId()])->save();

    (new RedisPaymentCache())->set($order);

    return response()->json([
      'payment_reference_code' => $ppPayment->getId(),
      'order_id' => $order->id,
    ]);
  }

  private function getPayPalCardType($cardType)
  {
    $mapping = [
      'visa'             => 'VISA',
      'master-card'      => 'MASTERCARD',
      'american-express' => 'AMEX',
      'discover'         => 'DISCOVER',
      'jcb'              => 'JCB',
      'unionpay'         => 'CHINA_UNION_PAY',
      'maestro'          => 'MAESTRO',
    ];

    return isset($mapping[$cardType]) ? $mapping[$cardType] : '';
  }
}
