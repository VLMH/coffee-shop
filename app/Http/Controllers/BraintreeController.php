<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BraintreeService;

class BraintreeController extends Controller
{
  public function get($id)
  {
    return (new BraintreeService())->getTransaction($id);
  }

  public function create(Request $request)
  {
    return (new BraintreeService())->createTransaction($request->input('nonce'));
  }
}
