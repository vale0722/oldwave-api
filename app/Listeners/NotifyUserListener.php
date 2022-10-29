<?php

namespace App\Listeners;

use App\Events\PaymentUpdated;
use App\Notifications\TransactionResolved;

class NotifyUserListener
{
    public function handle(PaymentUpdated $event): void
    {
        $user = $event->transaction->user();
        $user->notify(new TransactionResolved($event->transaction));
    }
}
