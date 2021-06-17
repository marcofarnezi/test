<?php

namespace App\Domain\Repository;

use App\Domain\Model\Order;
use App\Domain\Model\User;

interface PaymentRepository
{
    public function pay(Order $order, User $user): Order;
}
