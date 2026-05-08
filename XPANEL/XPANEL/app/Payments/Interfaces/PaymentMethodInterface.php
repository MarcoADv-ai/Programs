<?php
namespace App\Payments\Interfaces;
interface PaymentMethodInterface
{
    public function process() : string;

    static function buildItems(array $products) : array;

}