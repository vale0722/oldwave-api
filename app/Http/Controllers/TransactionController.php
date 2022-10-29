<?php

namespace App\Http\Controllers;

use App\Actions\TransactionAction;
use App\Constants\Status;
use App\Http\Requests\PaymentRequest;
use App\Models\Transaction;
use App\Services\PaymentServiceContract;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(): array
    {
        return response()->api(Status::OK, [
            'transactions' => auth()->user()->transactions()->with('shoppingCarItems', 'shoppingCarItems.item')->get(),
        ]);
    }

    public function show(Transaction $transaction): array
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        return response()->api(Status::OK, [
            'transaction' => $transaction->load('shoppingCarItems', 'shoppingCarItems.item'),
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

            return response()->api(Status::OK, [
                'redirect_url' => $transaction->process_url,
            ]);
        } catch (\Throwable $exception) {
            dd($exception);
            Log::error('error', [
                $exception,
            ]);
            return response()->api(Status::ERROR, [
                'error' => 'ha ocurrido un error al procesar la transacción',
            ]);
        }
    }

    public function query(Transaction $transaction, PaymentServiceContract $paymentService, TransactionAction $action): array
    {
        try {
            /** @var Transaction $transaction */
            $transaction = $action->setModel($transaction)->query($paymentService)->getModel();
            return response()->api(Status::OK, [
                $transaction->load('shoppingCarItems', 'shoppingCarItems.item'),
            ]);
        } catch (\Throwable $exception) {
            return response()->api(Status::ERROR, [
                $transaction->load('shoppingCarItems', 'shoppingCarItems.item'),
                'error' => 'ha ocurrido un error al procesar la transacción',
            ]);
        }
    }
}
