<?php

namespace App\Services;

class TransactionMsgCodes
{
    const _00 = '00'; //'Transaction Approved';
    const _01 = '01'; //'Refer to card issuer';
    const _02 = '02'; //'Refer to card issuer, special condition';
    const _03 = '03'; //'Invalid merchant';
    const _04 = '04'; //'Pick-up card';
    const _05 = '05'; //'Do not honor';
    const _06 = '06'; //'Error';
    const _07 = '07'; //'Pick-up card, special condition';
    const _08 = '08'; //'Honor with identification';
    const _09 = '09'; //'Request in progress';
    const _10 = '10'; //'Approved, partial';
    const _11 = '11'; //'Approved, VIP';
    const _12 = '12'; //'Invalid transaction';
    const _13 = '13'; //'Invalid amount';
    const _14 = '14'; //'Invalid card number';
    const _15 = '15'; //'No such issuer';
    const _16 = '16'; //'Approved, update track 3';
    const _17 = '17'; //'Customer cancellation';
    const _18 = '18'; //'Customer dispute';
    const _19 = '19'; //'Re-enter transaction';
    const _20 = '20'; //'Invalid response';
    const _21 = '21'; //'No action taken';
    const _22 = '22'; //'Suspected malfunction';
    const _23 = '23'; //'Unacceptable transaction fee';
    const _24 = '24'; //'File update not supported';
    const _25 = '25'; //'Unable to locate record';
    const _26 = '26'; //'Duplicate record';
    const _27 = '27'; //'File update field edit error';
    const _28 = '28'; //'File update file locked';
    const _29 = '29'; //'File update failed';
    const _30 = '30'; //'Format error';
    const _31 = '31'; //"Bank not supported";
    const _32 = '32'; //"Completed partially";
    const _33 = '33'; //"Expired card, pick-up";
    const _34 = '34'; //"Suspected fraud, pick-up";
    const _35 = '35'; //"Contact acquirer, pick-up";
    const _36 = '36'; //"Restricted card, pick-up";
    const _37 = '37'; //"Call acquirer security, pick-up";
    const _38 = '38'; //"PIN tries exceeded, pick-up";
    const _39 = '39'; //"No credit account";
    const _40 = '40'; //"Function not supported";
    const _41 = '41'; //"Lost card, pick-up";
    const _42 = '42'; //"No universal account";
    const _43 = '43'; //"Stolen card, pick-up";
    const _44 = '44'; //"No investment account";
    const _45 = '45'; //"Account closed";
    const _46 = '46'; //"Identification required";
    const _47 = '47'; //"Identification cross-check required";
    const _48 = '48'; //"Unknown Error From Postilion";
    const _51 = '51'; //"Not sufficient funds";
    const _52 = '52'; //"No check account";
    const _53 = '53'; //"No savings account";
    const _54 = '54'; //"Expired card";
    const _55 = '55'; //"Incorrect PIN";
    const _56 = '56'; //"No card record";
    const _57 = '57'; //"Transaction not permitted to cardholder";
    const _58 = '58'; //"Transaction not permitted on terminal";
    const _59 = '59'; //"Suspected fraud";
    const _60 = '60'; //"Contact acquirer";
    const _61 = '61'; //"Exceeds withdrawal limit";
    const _62 = '62'; //"Restricted card";
    const _63 = '63'; //"Security violation";
    const _64 = '64'; //"Original amount incorrect";
    const _65 = '65'; //"Exceeds withdrawal frequency";
    const _66 = '66'; //"Call acquirer security";
    const _67 = '67'; //"Hard capture";
    const _68 = '68'; //"Response received too late";
    const _69 = '69'; //"Advice received too late";
    const _70 = '70'; //"Reserved for future Postilion use";
    const _75 = '75'; //"PIN tries exceeded";
    const _76 = '76'; //"Reserved for future Postilion use";
    const _77 = '77'; //"Intervene, bank approval required";
    const _78 = '78'; //"Intervene, bank approval required for partial amount";
    const _79 = '79'; //"Reserved for client-specific use (declined)";
    const _90 = '90'; //"Cut-off in progress";
    const _91 = '91'; //"Issuer or switch inoperative";
    const _92 = '92'; //"Routing error";
    const _93 = '93'; //"Violation of law";
    const _94 = '94'; //"Duplicate transaction";
    const _95 = '95'; //"Reconcile error";
    const _96 = '96'; //"System malfunction";
    const _97 = '97'; //"Reserved for future Postilion use";
    const _98 = '98'; //"Exceeds cash limit";
    const _99 = '99'; //"Reserved for future Postilion use";

    /**
     * Returns constant's description
     * @param string $code
     * @return array
     */
    public static function getDescription(string $code): array
    {
        $description =  [
            '00' => 'Transaction Approved',
            '01' => 'Refer to card issuer',
            '02' => 'Refer to card issuer, special condition',
            '03' => 'Invalid merchant',
            '04' => 'Pick-up card',
            '05' => 'Do not honor',
            '06' => 'Error',
            '07' => 'Pick-up card, special condition',
            '08' => 'Honor with identification',
            '09' => 'Request in progress',
            '10' => 'Approved, partial',
            '11' => 'Approved, VIP',
            '12' => 'Invalid transaction',
            '13' => 'Invalid amount',
            '14' => 'Invalid card number',
            '15' => 'No such issuer',
            '16' => 'Approved, update track 3',
            '17' => 'Customer cancellation',
            '18' => 'Customer dispute',
            '19' => 'Re-enter transaction',
            '20' => 'Invalid response',
            '21' => 'No action taken',
            '22' => 'Suspected malfunction',
            '23' => 'Unacceptable transaction fee',
            '24' => 'File update not supported',
            '25' => 'Unable to locate record',
            '26' => 'Duplicate record',
            '27' => 'File update field edit error',
            '28' => 'File update file locked',
            '29' => 'File update failed',
            '30' => 'Format error',
            '31' => "Bank not supported",
            '32' => "Completed partially",
            '33' => "Expired card, pick-up",
            '34' => "Suspected fraud, pick-up",
            '35' => "Contact acquirer, pick-up",
            '36' => "Restricted card, pick-up",
            '37' => "Call acquirer security, pick-up",
            '38' => "PIN tries exceeded, pick-up",
            '39' => "No credit account",
            '40' => "Function not supported",
            '41' => "Lost card, pick-up",
            '42' => "No universal account",
            '43' => "Stolen card, pick-up",
            '44' => "No investment account",
            '45' => "Account closed",
            '46' => "Identification required",
            '47' => "Identification cross-check required",
            '48' => "Unknown Error From Postilion",
            '51' => "Not sufficient funds",
            '52' => "No check account",
            '53' => "No savings account",
            '54' => "Expired card",
            '55' => "Incorrect PIN",
            '56' => "No card record",
            '57' => "Transaction not permitted to cardholder",
            '58' => "Transaction not permitted on terminal",
            '59' => "Suspected fraud",
            '60' => "Contact acquirer",
            '61' => "Exceeds withdrawal limit",
            '62' => "Restricted card",
            '63' => "Security violation",
            '64' => "Original amount incorrect",
            '65' => "Exceeds withdrawal frequency",
            '66' => "Call acquirer security",
            '67' => "Hard capture",
            '68' => "Response received too late",
            '69' => "Advice received too late",
            '70' => "Reserved for future Postilion use",
            '75' => "PIN tries exceeded",
            '76' => "Reserved for future Postilion use",
            '77' => "Intervene, bank approval required",
            '78' => "Intervene, bank approval required for partial amount",
            '79' => "Reserved for client-specific use (declined)",
            '90' => "Cut-off in progress",
            '91' => "Issuer or switch inoperative",
            '92' => "Routing error",
            '93' => "Violation of law",
            '94' => "Duplicate transaction",
            '95' => "Reconcile error",
            '96' => "System malfunction",
            '97' => "Reserved for future Postilion use",
            '98' => "Exceeds cash limit",
            '99' => "Processor malfunction"
        ];
        return $description[$code];
    }

    /**
     * @return array 
     */
    public static function getDescriptions(): array
    {
        $description =  [
            '00' => 'Transaction Approved',
            '01' => 'Refer to card issuer',
            '02' => 'Refer to card issuer, special condition',
            '03' => 'Invalid merchant',
            '04' => 'Pick-up card',
            '05' => 'Do not honor',
            '06' => 'Error',
            '07' => 'Pick-up card, special condition',
            '08' => 'Honor with identification',
            '09' => 'Request in progress',
            '10' => 'Approved, partial',
            '11' => 'Approved, VIP',
            '12' => 'Invalid transaction',
            '13' => 'Invalid amount',
            '14' => 'Invalid card number',
            '15' => 'No such issuer',
            '16' => 'Approved, update track 3',
            '17' => 'Customer cancellation',
            '18' => 'Customer dispute',
            '19' => 'Re-enter transaction',
            '20' => 'Invalid response',
            '21' => 'No action taken',
            '22' => 'Suspected malfunction',
            '23' => 'Unacceptable transaction fee',
            '24' => 'File update not supported',
            '25' => 'Unable to locate record',
            '26' => 'Duplicate record',
            '27' => 'File update field edit error',
            '28' => 'File update file locked',
            '29' => 'File update failed',
            '30' => 'Format error',
            '31' => "Bank not supported",
            '32' => "Completed partially",
            '33' => "Expired card, pick-up",
            '34' => "Suspected fraud, pick-up",
            '35' => "Contact acquirer, pick-up",
            '36' => "Restricted card, pick-up",
            '37' => "Call acquirer security, pick-up",
            '38' => "PIN tries exceeded, pick-up",
            '39' => "No credit account",
            '40' => "Function not supported",
            '41' => "Lost card, pick-up",
            '42' => "No universal account",
            '43' => "Stolen card, pick-up",
            '44' => "No investment account",
            '45' => "Account closed",
            '46' => "Identification required",
            '47' => "Identification cross-check required",
            '48' => "Unknown Error From Postilion",
            '51' => "Not sufficient funds",
            '52' => "No check account",
            '53' => "No savings account",
            '54' => "Expired card",
            '55' => "Incorrect PIN",
            '56' => "No card record",
            '57' => "Transaction not permitted to cardholder",
            '58' => "Transaction not permitted on terminal",
            '59' => "Suspected fraud",
            '60' => "Contact acquirer",
            '61' => "Exceeds withdrawal limit",
            '62' => "Restricted card",
            '63' => "Security violation",
            '64' => "Original amount incorrect",
            '65' => "Exceeds withdrawal frequency",
            '66' => "Call acquirer security",
            '67' => "Hard capture",
            '68' => "Response received too late",
            '69' => "Advice received too late",
            '70' => "Reserved for future Postilion use",
            '75' => "PIN tries exceeded",
            '76' => "Reserved for future Postilion use",
            '77' => "Intervene, bank approval required",
            '78' => "Intervene, bank approval required for partial amount",
            '79' => "Reserved for client-specific use (declined)",
            '90' => "Cut-off in progress",
            '91' => "Issuer or switch inoperative",
            '92' => "Routing error",
            '93' => "Violation of law",
            '94' => "Duplicate transaction",
            '95' => "Reconcile error",
            '96' => "System malfunction",
            '97' => "Reserved for future Postilion use",
            '98' => "Exceeds cash limit",
            '99' => "Processor malfunction"
        ];
        return $description;
    }
}
