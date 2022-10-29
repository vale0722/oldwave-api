<?php

namespace App\Http\Controllers;

use App\Actions\TransactionAction;
use App\Constants\Status;
use App\Http\Requests\PaymentRequest;
use App\Models\Transaction;
use App\Services\PaymentServiceContract;

class TransactionController extends Controller
{
    public function index(): array
    {
        return $this->api(Status::OK, [
            'transactions' => auth()->user()->transactions()->with('shopping_car_items', 'shopping_car_items.product')->get(),
        ]);
    }

    public function show(Transaction $transaction): array
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        return $this->api(Status::OK, [
            'transaction' => $transaction->with('shopping_car_items', 'shopping_car_items.product'),
        ]);
    }

    public function store(PaymentRequest $request, PaymentServiceContract $paymentService, TransactionAction $action): array
    {
        try {
            /** @var Transaction $transaction */
            $transaction = $action->setData(array_merge(
                $request->validated(),
                [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]
            ))
                ->store()
                ->process($paymentService)
                ->getModel();

            return $this->api(Status::OK, [
                'redirect_url' => $transaction->process_url,
            ]);
        } catch (\Throwable $exception) {
            return $this->api(Status::ERROR, [
                'error' => 'ha ocurrido un error al procesar la transacción',
            ]);
        }
    }

    public function query(Transaction $transaction, PaymentServiceContract $paymentService, TransactionAction $action): array
    {
        try {
            /** @var Transaction $transaction */
            $transaction = $action->setModel($transaction)->query($paymentService)->getModel();

            return $this->api(Status::OK, [
                $transaction->toArray(),
            ]);
        } catch (\Throwable $exception) {
            return $this->api(Status::ERROR, [
                'error' => 'ha ocurrido un error al procesar la transacción',
            ]);
        }
    }
}
