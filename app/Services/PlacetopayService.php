<?php

namespace App\Services;

use App\Constants\TransactionStatuses;
use App\Models\Transaction;
use Carbon\Carbon;
use Dnetix\Redirection\Message\RedirectInformation;

class PlacetopayService implements PaymentServiceContract
{
    private PlacetopayServiceContract $service;

    public function __construct(array $settings)
    {
        $this->service = app(PlacetopayServiceContract::class, $settings);
    }

    public function store(Transaction $transaction): Transaction
    {
        $name = explode(' ', $transaction->name);
        $request = [
            'locale' => 'es_CO',
            'buyer' => [
                'name' => $name[0],
                'surname' => $name[1] ?? '',
                'document' => $transaction->document,
                'documentType' => $transaction->document_type,
            ],
            'payment' => [
                'reference' => $transaction->reference,
                'amount' => ['currency' => $transaction->currency, 'total' => $transaction->total],
            ],
            'skipResult' => false,
            'returnUrl' => str_replace(':transaction', $transaction->reference, config('services.payment_services.front_url')),
            'expiration' => date('c', strtotime('+24 hours')),
            'ipAddress' => $transaction->ip,
            'userAgent' => $transaction->user_agent,
        ];

        try {
            $response = $this->service->request($request);

            $transaction->update(
                $response->isSuccessful()
                    ? ['request_id' => $response->requestId(), 'process_url' => $response->processUrl()]
                    : ['status' => TransactionStatuses::FAILED, 'reason' => $response->status()->reason()]
            );
        } catch (\Throwable $exception) {
            report($exception);
            $transaction->update(['status' => TransactionStatuses::FAILED, 'reason' => $exception->getCode()]);
        }

        return $transaction;
    }

    public function query(Transaction $transaction): Transaction
    {
        /** @var RedirectInformation $response */
        $response = $this->service->query($transaction->request_id);
        if ($response->isSuccessful()) {
            if ($response->status()->isApproved()) {
                $lastPayment = $response->lastTransaction();
                $transaction->status = TransactionStatuses::APPROVED;
                $transaction->receipt = $lastPayment->receipt();
                $transaction->authorization = $lastPayment->authorization();
                $transaction->currency = $lastPayment->amount()->from()->currency();
                $transaction->document = $response->request()->payer()->document();
                $transaction->document_type = $response->request()->payer()->documentType();
                $transaction->paid_at = (new Carbon($lastPayment->status()->date()))->toDateTimeString();
            } elseif ($response->status()->isRejected()) {
                $transaction->status = TransactionStatuses::REJECTED;
                $transaction->reason = $response->status()->reason();
            } elseif ($response->status()->isError()) {
                $transaction->status = TransactionStatuses::FAILED;
                $transaction->reason = $response->status()->reason();
            }
        }

        $transaction->save();
        return $transaction;
    }
}
