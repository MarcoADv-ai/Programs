<?php
namespace App\Services;

use App\Payments\Interfaces\PaymentMethodInterface;

class PaymentContext
{
    private $paymentMethod;

    public function __construct(PaymentMethodInterface $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function processPayment()
    {
        return $this->paymentMethod->process();
    }
}