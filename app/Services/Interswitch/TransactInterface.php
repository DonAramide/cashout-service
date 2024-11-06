<?php

namespace App\Services\Interswitch;

interface TransactInterface
{
    public function run(TransactPayload $transactPayload): TransactResponse;
}