<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\PaypalService;
use App\PaypalPayment;
use App\Order;
use App\RedisPaymentCache;

class PaymentController extends Controller
{
  public function index()
  {
    // TODO: only pass braintree auth token to payment form page
    return view('payment', ['braintreeAuth' => (new BraintreeService())->getClientToken()]);
  }

  public function search(Request $request)
  {
    $paymentCache = (new RedisPaymentCache())->get(
      $request->input('name'),
      $request->input('code')
    );

    return $paymentCache
           ? response()->json($paymentCache)
           : response()->json(null, 404);
  }
}
