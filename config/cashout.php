<?php

return [
    /** Interswitch */
    'interswitch_merchant_id' =>  env('INTERSWITCH_MERCHANT_ID'),
    'interswitch_merchant_address' =>  env('INTERSWITCH_MERCHANT_ADDRESS'),
    'interswitch_merchant_account_number' =>  env('INTERSWITCH_MERCHANT_ACCOUNT_NO'),
    'interswitch_merchant_account_institution_code' =>  env('INTERSWITCH_MERCHANT_ACCOUNT_INSTITUTION_CODE'),
    'interswitch_base_url' =>  env('INTERSWITCH_BASE_URL'),
    'interswitch_charge' => env('INTERSWITCH_CHARGES'),
    'interswitch_request_time_out_duration' =>  env('REQUEST_TIME_OUT_DURAION', 10),
    'interswitch_connection_time_out_duration' =>  env('CONNECTION_TIME_OUT_DURAION', 10),
    'interswitch_default_serial' => env('INTERSWITCH_SERIAL_NO'),
    'interswitch_default_terminal' =>  env('INTERSWITCH_TERMINAL_ID'),
];
