<?php

use App\BraintreeService;

Route::get('/', function() {
  return view('index', [
    'braintreeAuth' => (new BraintreeService())->getClientToken()
  ]);
});

Route::post('/payments/braintree', 'BraintreeController@create');
// Route::get('/payments/braintree/{id}', 'BraintreeController@get');
