<?php

namespace App;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\Transaction;


class PaypalService
{
    private $clientId = 'AV4HK9Fgz7JSQEqk4xyzCtE5rfGuq6vAomj8vUyFc-QFYducIq2CZ9cR7XjxrI-wAkFa2nlP3NE7VMMC';
    private $clientSecret = 'EJcTCFFL47MA25cftbuVEpq-MchZ6hF5ZtypzAnaflUSrZ2ZFaRd_iO5lIrkCgRjvz6xcGyFnO5ZVBFP';

    public function getClientId() { return $this->clientId; }

    public function getApiContext()
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->clientId,
                $this->clientSecret
            )
        );

        $apiContext->setConfig([
            'mode' => 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName' => '../storage/logs/PayPal.log',
            'log.LogLevel' => 'DEBUG',
            'cache.enabled' => true
        ]);
        return $apiContext;
    }

    public function getPaymentCard($cardType, $cardNum, $expMonth, $expYear, $cvv, $fName, $lName)
    {
        $card = new PaymentCard();
        $card
            ->setType($cardType)
            ->setNumber($cardNum)
            ->setExpireMonth($expMonth)
            ->setExpireYear($expYear)
            ->setCvv2($cvv)
            ->setFirstName($fName)
            ->setLastName($lName)
            ->setBillingCountry("US");
        return $card;
    }

    public function getInstrument(PaymentCard $card)
    {
        return (new FundingInstrument())->setPaymentCard($card);
    }

    public function getPayer(FundingInstrument $fi)
    {
        $payer = new Payer();
        $payer
            ->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));
        return $payer;
    }

    public function getItemList($currency, $price)
    {
        $item = new Item();
        $item
            ->setName('Ground Coffee 40 oz')
            ->setDescription('Ground Coffee 40 oz')
            ->setCurrency($currency)
            ->setQuantity(1)
            ->setPrice($price);

        $itemList = new ItemList();
        $itemList->setItems([$item]);
        return $itemList;
    }

    public function getAmount($currency, $totalAmount)
    {
        $amount = new Amount();
        $amount
            ->setCurrency($currency)
            ->setTotal($totalAmount);
        return $amount;
    }

    public function getTransaction(Amount $amount, ItemList $itemList)
    {
        $transaction = new Transaction();
        $transaction
            ->setAmount($amount)
            ->setItemList($itemList)
            ->setInvoiceNumber(uniqid());
        return $transaction;
    }

    public function getPayment(Payer $payer, Transaction $transaction)
    {
        $payment = new Payment();
        $payment
            ->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions([$transaction]);
        return $payment;
    }

    public function buildPayment($cardType, $cardNum, $expMonth, $expYear, $cvv, $fName, $lName, $currency, $amount)
    {
        $card = $this->getPaymentCard($cardType, $cardNum, $expMonth, $expYear, $cvv, $fName, $lName);
        $instrument = $this->getInstrument($card);
        $payer = $this->getPayer($instrument);
        $ppAmount = $this->getAmount($currency, $amount);
        $itemList = $this->getItemList($currency, $amount);
        $transaction = $this->getTransaction($ppAmount, $itemList);
        return $this->getPayment($payer, $transaction);
    }

}
