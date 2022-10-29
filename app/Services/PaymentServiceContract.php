<?php

namespace App\Services;

use App\Models\Transaction;

interface PaymentServiceContract
{
    public function store(Transaction $transaction): Transaction;
    public function query(Transaction $transaction): Transaction;
}
