<?php

namespace App\Constants;

class TransactionStatuses extends EnumBase
{
    public const APPROVED = 'APPROVED';
    public const PENDING = 'PENDING';
    public const REJECTED = 'REJECTED';
    public const FAILED = 'FAILED';
}
