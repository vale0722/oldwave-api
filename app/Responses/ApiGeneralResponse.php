<?php

namespace App\Responses;

use Illuminate\Contracts\Support\Arrayable;

class ApiGeneralResponse implements Arrayable
{
    private string $status;
    private $data;

    public function __construct(string $status, $data = [])
    {
        $this->status = $status;
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'data' => $this->data,
        ];
    }
}
