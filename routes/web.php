<?php

use App\BraintreeService;

Route::get('/', function() {
  return view('index', [
    'braintreeAuth' => (new BraintreeService())->getClientToken()
  ]);
});

Route::post('/payments/braintree', 'BraintreeController@create');
Route::post('/payments/paypal', 'PaypalController@create');

Route::get('/payments', 'PaymentController@index');
Route::get('/payments/search', 'PaymentController@search');
