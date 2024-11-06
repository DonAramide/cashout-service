<?php

namespace App\Services;

class TransactionCost
{
    const PERCENT = 'percent';
    const PERCENTAGE = 'percentage';
    const VALUE = 'value';


    public $merchant, $transaction_type, $amount;
    public function __construct($merchant, $transaction_type, $amount)
    {
        $this->merchant = $merchant;
        $this->transaction_type = $transaction_type;
        $this->amount = $amount;
    }
    public function run()
    {
        $transaction_cost = 2;
        return $transaction_cost;
    }
}
