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
}
