<?php

namespace Modules\Auth\Http\Controllers\Payment;

use Braintree\Gateway;
use Illuminate\Support\Facades\Auth;

class PaymentController
{
    public function clientToken()
    {
        $gateway = new Gateway([
            'environment' => config('braintree.environment'),
            'merchantId' => config('braintree.merchant_id'),
            'publicKey' => config('braintree.public_key'),
            'privateKey' => config('braintree.private_key'),
        ]);

        $user = Auth::user();

        if (! $user->braintree_customer_id) {

            $response = $gateway->customer()->create([
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'email' => $user->email,
            ]);

            if ($response->success) {
                $user->update(['braintree_customer_id' => $response->customer->id]);
            }
        }

        $clientToken = $gateway->clientToken()->generate(['customerId' => $user->braintree_customer_id]);

        return response(['data' => $clientToken]);
    }
}
