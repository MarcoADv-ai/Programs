<?php

namespace App\Payments\Classes;

use App\Helpers\Paypal\PaypalApiContext;
use App\Helpers\Paypal\PaypalHttpClient;
use App\Payments\Interfaces\PaymentMethodInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class PaypalPayment implements PaymentMethodInterface
{
    private $currency;
    private $apiContext;
    private $httpClient;
    private $products;
    private $returnURL;
    private $cancelURL;
    const SANDBOX_URL = 'https://api-m.sandbox.paypal.com/v2/checkout/orders';
    const LIVE_URL = 'https://api-m.paypal.com/v2/checkout/orders';

    public function __construct(array $products, string $returnURL, string $cancelURL)
    {
        $this->currency = Config::get('donations.currency');
        $this->apiContext = new PaypalApiContext();
        $this->httpClient = new PaypalHttpClient();
        $this->products = $products;
        $this->returnURL = $returnURL;
        $this->cancelURL = $cancelURL;
    }

    private function getPaypalUrl()
    {
        return env('PAYPAL_MODE') == 'SANDBOX' ? self::SANDBOX_URL : self::LIVE_URL;
    }

    public function getOrderInfo($orderID)
    {
        $token = $this->apiContext->getAccessToken();
        $response = Http::withToken($token)->get($this->getPaypalUrl() . '/' . $orderID);

        return $response;
    }

    public function process(): string
    {
        $token = $this->apiContext->getAccessToken();
        $items = $this->buildItems($this->products);
        $totalPrice = $this->calculateTotalPrice($items);
        $data = $this->buildPaymentData($totalPrice, $items, $this->returnURL, $this->cancelURL);
        $response = $this->httpClient->post($this->getPaypalUrl(), $token, $data);
        $response = json_encode($response->json());

        return $response;
    }

    public function capturePayment($orderID)
    {
        $token = $this->apiContext->getAccessToken();
        $response = $this->httpClient->post($this->getPaypalUrl() . '/' . $orderID . '/capture', $token, [
            'id' => $orderID
        ]);

        return json_encode($response->json());
    }

    static function buildItems(array $products): array
    {
        $config = Config::get('xpanel.donations');
        $items = [];
    
        if ($config['shop_type'] != 'points') {
            $donationCollection = collect(Config::get('donations.packages'));
    
            foreach ($products as $product) {
                $donationPack = $donationCollection->where('id', $product['id'])->first();
                $items[] = [
                    'name' => $donationPack['name'],
                    'currency' => $config['currency'],
                    'quantity' => $product['qty'],
                    'price' => $donationPack['price'],
                    'sku' => $donationPack['id'],
                    'description' => $donationPack['description'],
                    'category' => 'DONATION',
                    'unit_amount' => [
                        'currency_code' => $config['currency'],
                        'value' => $donationPack['price'],
                    ],
                ];
            }
        } else {
            $items[] = [
                'name' => "Donation to support ".env('APP_NAME'). " project",
                'currency' => $config['currency'],
                'quantity' => 1,
                'price' => $products['donation'],
                'sku' => 1,
                'description' => "Voluntary donation to support the".env('APP_NAME'). " project.",
                'category' => 'DONATION',
                'unit_amount' => [
                    'currency_code' => $config['currency'],
                    'value' => $products['donation'],
                ],
            ];
        }
    
        return $items;
    }

    // static function buildItems($products): array
    // {
    //     $donaPackages = collect(Config::get('xpanel.donations.packages'));
    //     $items = [];

    //     foreach ($products as $product) {
    //         $donationPack = $donaPackages->where('id', $product['id'])->first();

    //         $items[] = [
    //             'name' => $donationPack['name'],
    //             'currency' => self::$currency,
    //             'quantity' => $product['qty'],
    //             'price' => $donationPack['price'],
    //             'sku' => $donationPack['id'],
    //             'description' => $donationPack['description'],
    //             'category' => 'DONATION',
    //             'unit_amount' => [
    //                 'currency_code' => self::$currency,
    //                 'value' => $donationPack['price'],
    //             ],
    //         ];
    //     }

    //     return $items;
    // }

    private function calculateTotalPrice($items)
    {
        return array_reduce($items, function ($carry, $item) {
            return $carry + $item['price'] * $item['quantity'];
        }, 0);
    }

    private function buildPaymentData($totalPrice, $items, $returnURL, $cancelURL): array
    {
        return [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $this->currency,
                        'value' => $totalPrice,
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $this->currency,
                                'value' => $totalPrice,
                            ]
                        ]
                    ],
                    'items' => $items
                ]
            ],
            'application_context' => [
                'brand_name' => env('APP_NAME') . ' Donations',
                'description' => 'Thank you for supporting our ' . env('APP_NAME') . ' free server! Your generous donation will directly contribute to improving and maintaining our free services. We are grateful for your ongoing support in the development of our project.',
                'locale' => 'en-US',
                'landing_page' => 'NO_PREFERENCE',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
                'payment_method' => [
                    'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED'
                ],
                'return_url' => $returnURL,
                'cancel_url' => $cancelURL
            ]
        ];
    }
}
