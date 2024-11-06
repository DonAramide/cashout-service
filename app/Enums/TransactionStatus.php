<?php

namespace App\Enums;

class TransactionStatus
{
    public const INITIATED = 'intiated';
    public const REDIRECTING = 'redirecting';
    public const REINITIATING = 'reinitiating';
    public const SUCCESSFUL = "successful";
    public const PENDING = "pending";
    public const PROCESSING = "processing";
    public const PROCESSED = "processed";
    public const FAILED = "failed";
    public const SETTLED = "settled";
    public const PRELOAD_FAILED = "preload failed";
    public const PRELOAD_SUCCESSFUL = "preload successful";
    public const SETTLEMENT_FAILED = "settlement failed";
    public const CHARGEBACK_FAILED = "chargeback failed";
    public const CHARGED_BACK = "charged_back";
    public const SETTLING_FAILED_TRANSACTION = "settling failed transaction";
    public const REFUND_FAILED = "refunding failed";
    public const REFUNDED = "refunded successfully";
    public const CHARGEBACK_FAILED_REVERSAL = "charge reversal failed";
    public const CHARGEBACK_FAILED_SUCCESSFUL = "charge reversal successful";
    public const REFUND_INITIATED="refund initiated";
}