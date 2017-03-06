<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\PaypalService;

class PaypalController extends Controller
{
  public function create(Request $request)
  {
    $paypalService = new PaypalService();
    $apiContext = $paypalService->getApiContext();
    $payment = $paypalService->buildPayment(
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
      $payment->create($apiContext);
    } catch (\Exception $e) {
      return response()->json(['message' => $e->getMessage()], 400);
    }

    return response()->json(['transaction_id' => $payment->getId()]);
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
