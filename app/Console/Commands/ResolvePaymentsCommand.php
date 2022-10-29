<?php

namespace App\Console\Commands;

use App\Jobs\ProcessPendingPayments;
use App\Models\Transaction;
use DateInterval;
use DateTime;
use Exception;
use Illuminate\Console\Command;

class ResolvePaymentsCommand extends Command
{
    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    protected $signature = 'resolve:payments';

    protected $description = 'Resolve payments pending in app';

    /**
     * @throws Exception
     */
    public function handle()
    {
        $date = date(self::DATE_TIME_FORMAT);
        $this->line('Init sonda: ' . $date);

        $time = new DateTime($date);
        $time->sub(new DateInterval('PT5M'));

        $pendingPayments = Transaction::pendingPayments($time->format(self::DATE_TIME_FORMAT));

        foreach ($pendingPayments as $payment) {
            ProcessPendingPayments::dispatchSync($payment);
        }
    }
}
