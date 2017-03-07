<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\BraintreeService;

class BraintreeServiceTest extends TestCase
{
  public function testGetMerchantAccountIdUsd()
  {
    $this->assertEquals('vlmh', (new BraintreeService())->getMerchantAccountId('usd'));
  }

  public function testGetMerchantAccountIdHkd()
  {
    $this->assertEquals('vlmh-hkd', (new BraintreeService())->getMerchantAccountId('hkd'));
  }

  public function testGetMerchantAccountIdEur()
  {
    $this->assertEquals('vlmh-eur', (new BraintreeService())->getMerchantAccountId('eur'));
  }

  public function testGetMerchantAccountIdEurInCapitalLetter()
  {
    $this->assertEquals('vlmh-eur', (new BraintreeService())->getMerchantAccountId('EUR'));
  }
}
