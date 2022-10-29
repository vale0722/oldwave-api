<?php

namespace App\Models\Concerns;

use App\Constants\TransactionStatuses;

trait HasStatus
{
    public function isApproved(): bool
    {
        return TransactionStatuses::APPROVED === $this->getStatus();
    }

    public function isPending(): bool
    {
        return TransactionStatuses::PENDING === $this->getStatus();
    }

    public function isFailed(): bool
    {
        return TransactionStatuses::FAILED === $this->getStatus();
    }

    public function isRejected(): bool
    {
        return TransactionStatuses::REJECTED === $this->getStatus();
    }

    public function isProcessed(): bool
    {
        return !empty($this->process_url);
    }

    public function isCompleted(): bool
    {
        return $this->isApproved() || $this->isFailed() || $this->isRejected();
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
