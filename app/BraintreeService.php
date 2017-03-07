<?php

namespace App;

class BraintreeService
{

  public function __construct()
  {
    \Braintree\Configuration::environment(env('BRAINTREE_ENV', ''));
    \Braintree\Configuration::merchantId(env('BRAINTREE_MERCHANT_ID', ''));
    \Braintree\Configuration::publicKey(env('BRAINTREE_PUBLIC_KEY', ''));
    \Braintree\Configuration::privateKey(env('BRAINTREE_PRIVATE_KEY', ''));
  }

  public function getClientToken()
  {
    return \Braintree\ClientToken::generate();
  }

  public function getTransaction($id)
  {
    return \Braintree\Transaction::find($id);
  }

  public function createTransaction($nonce, $amount, $currency)
  {
    return \Braintree\Transaction::sale([
      'amount' => $amount,
      'paymentMethodNonce' => $nonce,
      'merchantAccountId' => $this->getMerchantAccountId($currency),
      'options' => [
        'submitForSettlement' => true
      ]
    ]);
  }

  public function getMerchantAccountId($currency)
  {
    $curr = strtolower($currency);
    return 'vlmh' . ($curr == 'usd' ? '' : ('-'.$curr));
  }

}
