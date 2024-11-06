<?php

namespace App\Enums;

class CardTypes
{
    public const VISA = 'visa';
    public const MASTER = 'mastercard';
    public const DISCOVER = 'discover';
    public const VERVE = 'verve';
    public const JCB = 'jcb';
    public const AMERICAN_EXPRESS = 'american express';

    public static function cards(){
        return [
            self::VISA,
            self::MASTER,
            self::DISCOVER,
            self::VERVE,
            self::JCB,
            self::AMERICAN_EXPRESS,
        ];
    }
}
