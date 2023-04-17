<?php

namespace App\Actions;

use App\Events\PaymentUpdated;
use App\Models\Item;
use App\Models\ShoppingCarItem;
use App\Models\Transaction;
use App\Services\PaymentServiceContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionAction extends Action
{
    public ?Transaction $model = null;

    public function store(): self
    {
        DB::transaction(function () {
            $this->setModel(new Transaction());

            $this->model->name = $this->data['name'];
            $this->model->email = $this->data['email'];
            $this->model->city = $this->data['city'];
            $this->model->address = $this->data['address'];
            $this->model->document = $this->data['document'];
            $this->model->document_type = $this->data['document_type'];
            $this->model->reference = 'REF' . now()->format('dmYhis') . $this->data['document'];
            $this->model->currency = 'COP';
            $this->model->ip = $this->data['ip'];
            $this->model->user_agent = $this->data['user_agent'];
            $this->model->user_id = auth()->id();
            $this->model->save();

            $this->makeShoppingCar();
        });

        return $this;
    }

    public function process(PaymentServiceContract $payment): self
    {
        $this->model = $payment->store($this->model);
        return $this;
    }

    public function query(PaymentServiceContract $payment): self
    {
        try {
            $this->model = $payment->query($this->model);
            if ($this->model->isCompleted()) {
                PaymentUpdated::dispatch($this->model);
            }
        } catch (\Throwable $exception) {
            Log::error('Error Process Payment', [
                'exception' => $exception,
                'reference' => $this->model->reference,
            ]);
        }

        return $this;
    }

    protected function makeShoppingCar()
    {
        $total = 0;
        foreach ($this->data['car'] as $itemCar) {
            $item = Item::where('slug', $itemCar['slug'])->first();
            $shoppingCarItem = new ShoppingCarItem();
            $shoppingCarItem->transaction_id = $this->model->id;
            $shoppingCarItem->item_id = $item->id;
            $shoppingCarItem->price = $item->price;
            $shoppingCarItem->discount = $item->discount;
            $shoppingCarItem->count = $itemCar['count'];
            $shoppingCarItem->total = (
                $shoppingCarItem->price
                - ($shoppingCarItem->price * $shoppingCarItem->discount)
            ) * $shoppingCarItem->count;
            $total += $shoppingCarItem->total;
            $shoppingCarItem->save();
        }

        $this->model->total = $total;
        $this->model->save();
    }
}
