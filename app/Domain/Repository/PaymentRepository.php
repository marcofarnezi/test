<?php

namespace App\Domain\Repository;

use App\Domain\Model\User;
use App\Domain\Model\Order;

interface PaymentRepository
{
    public function pay(Order $order, User $user): Order;
}
