<?php

namespace App\Jobs;

use App\Actions\TransactionAction;
use App\Models\Transaction;
use App\Notifications\TransactionResolved;
use App\Services\PaymentServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPendingPayments implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private Transaction $transaction;
    private TransactionAction $action;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->action = app(TransactionAction::class)->setModel($transaction);
    }

    public function handle(): void
    {
        $this->action->query(app(PaymentServiceContract::class));
        $user = $this->transaction->user();
        $user->notify(new TransactionResolved($this->transaction));
    }
}
