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
    return view('payment');
  }

  public function search(Request $request)
  {
    return response()->json(['result' => 'ok']);
    // return response()->json(['result' => 'not found'], 404);
  }
}
