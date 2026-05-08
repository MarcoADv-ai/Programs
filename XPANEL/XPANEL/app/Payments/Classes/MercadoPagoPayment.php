<?php

namespace App\Payments\Classes;

use App\Helpers\FunctionsHelper;
use App\Models\MercadoPagoProcess;
use App\Payments\Interfaces\PaymentMethodInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference;

class MercadoPagoPayment implements PaymentMethodInterface
{
    private $iva = 0.16;
    private $items;
    private $returnURL;
    private $cancelURL;

    public function __construct(array $items, string $returnURL, string $cancelURL)
    {
        $this->items = $items;
        $this->returnURL = $returnURL;
        $this->cancelURL = $cancelURL;
    }

    static function buildItems(array $products): array
    {
        $config = Config::get('xpanel.donations');
        $items = [];
        if ($config['shop_type'] != 'points') 
        {
            $donationCollection = collect(Config::get('donations.packages'));

            foreach ($products as $product) {
                $donationPack = $donationCollection->where('id', $product['id'])->first();
                $items[] = [
                    "id" => $donationPack['id'],
                    "title" => $donationPack['name'],
                    "description" => $donationPack['description'],
                    "category_id" => "virtual_goods",
                    "quantity" => $product['qty'],
                    "currency_id" => $config['currency'],
                    "unit_price" => $donationPack['price'],
                ];
            }
        }else {
            $items[] = [
                "id" => 1,
                "title" => "Donation to support ".env('APP_NAME'). " project",
                "description" => "Voluntary donation to support the".env('APP_NAME'). " project.",
                "category_id" => "virtual_goods",
                "quantity" => 1,
                "currency_id" => $config['currency'],
                "unit_price" => $products['donation'],
            ];
        }
        return $items;
    }


    static function buildPaymentData(array $items, string $returnURL, string $cancelURL): array
    {
        $shopType = Config::get('xpanel.donations.shop_type');
        return [
            "auto_return" => "approved",
            "back_urls" => array(
                "success" => $returnURL,
                "failure" => $cancelURL,
            ),
            "expiration_date_form" => Carbon::now()->format('Y-m-d\TH:i:s\Z'),
            "expiration_date_to" => Carbon::now()->addHour()->format('Y-m-d\TH:i:s\Z'),
            "expires" => true,
            "metadata" => array(
                "master_id" => auth()->user()->id,
                "items" => json_encode($items[0]),
                "payment_status" => "pending",
                "paymet_method" => "mercado_pago",
                "mc_gross" => ($shopType == 'points') ? $items[0]['unit_price'] : collect($items)->sum('unit_price'),
            ),
            "items" => $items,
            "binary_mode" => true,
            "payment_methods" => array(
                "default_payment_method_id" => "master",
                "excluded_payment_types" => array(
                    array("id" => "ticket"),
                    array("id" => "atm"),
                    array("id" => "bank_transfer"),
                ),
                "installments" => 3,
                "default_installments" => 1
            ),
            "statement_descriptor" => env('MP_STATEMENT_DESCRIPTOR'),
        ];
    }

    private function registerDonation($response): void
    {
        $config = Config::get('xpanel.donations');
        $total_amount = collect($response->items)->sum('unit_price');

        MercadoPagoProcess::create([
            'preference_id' => $response->id,
            'master_id' => auth()->user()->id,
            'client_id' => $response->client_id,
            'items' => json_encode($response->items),
            'credits' => $total_amount,
            'payment_status' => 'pending',
            'create_date' => $response->date_created,
            'mc_gross' => $total_amount,
            'mc_fee' => 0,
            'mc_currency' => $config['currency'],
        ]);
    }

    public function process(): string
    {
        MercadoPagoConfig::setAccessToken(env('MP_ACCESS_TOKEN'));
        $client = new PreferenceClient();
        $items = self::buildItems($this->items);
        $paymentData = self::buildPaymentData($items, $this->returnURL, $this->cancelURL);
        $response = $client->create($paymentData);
        $this->registerDonation($response);

        return json_encode($response);
    }
}
