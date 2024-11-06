<?php

namespace App\Services;

class TransactionChannelFee
{
    protected $amount;
    public $channel;
    public $transaction_type;

    const PERCENT = 'percent';
    const PERCENTAGE = 'percentage';
    const VALUE = 'value';

    public function __construct($channel, $amount, $transaction_type)
    {
        $this->channel = $channel;
        $this->amount = $amount;
        $this->transaction_type = $transaction_type;
    }

    public function run()
    {
        $tnxCost = 0;
        return $tnxCost;
    }
}
