<?php

namespace App\Helpers\Paypal;

use Illuminate\Support\Facades\Http;

class PaypalHttpClient
{
    public function post($url, $token, $data)
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post($url, $data);
    }

    public function get($url, $token)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->get($url, []);

        return json_decode($response->body());
    }
}
