<?php

namespace App;

class BraintreeService
{

  public function __construct()
  {
    \Braintree\Configuration::environment('sandbox');
    \Braintree\Configuration::merchantId('m46jtcw8fbndch2g');
    \Braintree\Configuration::publicKey('s5bd4z26pb48rq9k');
    \Braintree\Configuration::privateKey('3562c27fdc7e55ebb1ba43233fecd9fd');
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
    return 'vlmh' . ($currency == 'usd' ? '' : ('-'.$currency));
  }

}
