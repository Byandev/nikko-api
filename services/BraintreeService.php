<?php

namespace App\Services;

use Braintree\Gateway;

class BraintreeService
{
    public Gateway $gateway;

    public function __construct()
    {
        $this->gateway = new Gateway([
            'environment' => config('braintree.environment'),
            'merchantId' => config('braintree.merchant_id'),
            'publicKey' => config('braintree.public_key'),
            'privateKey' => config('braintree.private_key'),
        ]);
    }
}
