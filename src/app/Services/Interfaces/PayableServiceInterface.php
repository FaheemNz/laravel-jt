<?php


namespace App\Services\Interfaces;

use App\Payment;

interface PayableServiceInterface
{
    public function generatePayable(Payment $payment);
}
