<?php
namespace App\Helpers\Paypal;

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalApiContext
{

    private $clientID;
    private $secret;

    public function __construct()
    {
        $this->clientID = env('PAYPAL_CLIENT_ID');
        $this->secret = env('PAYPAL_SECRET_KEY');
    }

    private function getApiContext()
    {
        return new ApiContext(
            new OAuthTokenCredential(
                $this->clientID,
                $this->secret
            )
        );
    }

    public function getAccessToken()
    {

        $apiContext = $this->getApiContext();
        $apiContext->setConfig(
            array(
                'mode' => env('PAYPAL_MODE'),
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
                'log.FileName' => storage_path('logs/paypal.log'),
                'log.LogLevel' => env('PAYPAL_MODE') == 'sandbox' ? 'DEBUG' : 'INFO',
                'validation.level' => env('PAYPAL_MODE') == 'sandbox' ? 'log' : 'strict'
            )
        );

        return $apiContext->getCredential()->getAccessToken($apiContext->getConfig());
    }
}
