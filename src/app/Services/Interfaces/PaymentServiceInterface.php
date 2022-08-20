<?php


namespace App\Services\Interfaces;


use App\Order;

interface PaymentServiceInterface
{
    public function createOrderPayment(Order $order);
    public function clearPayment(Order $order, string $ref);
}
