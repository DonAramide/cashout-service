<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use App\Enums\CardTypes;
use App\Utilities\Helpers;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Enums\TransactionType;
use App\Utilities\Tlv\Decoder;
use App\Utilities\Tlv\Encoder;
use App\Enums\TransactionStatus;
use Illuminate\Support\Facades\Log;
use App\Services\TransactionChannelFee;
use App\Services\Interswitch\TransactPayload;
use App\Utilities\Tlv\Decoder\Exception\MalformedDataBlockException;

trait CashOutTrait
{

    /**
     * Clear Transact Data
     * 
     * @param array $data 
     * @return array
     * @throws MalformedDataBlockException 
     */
    private function clearTransactData(array $data): array
    {
        $data['card_expiry_date'] = (string) $data['card_expiry_date'];
        $data['icc_data'] = $this->cleanIccData($data['icc_data']);
        return $data;
    }

    /**
     * @return string
     */
    public function now(): string
    {
        return Carbon::now()->timezone('Africa/Lagos')->toDateTimeLocalString();
    }

    /**
     * @return int|float 
     */
    public function dayEnd(): int|float
    {
        return (strtotime('tomorrow') - time());
    }

    /**
     * Clean Icc Data
     * 
     * @param mixed $iccdata 
     * @return mixed
     */
    private function cleanIccData(mixed $iccdata): mixed
    {
        $codecObj = new Decoder();
        $iccdata = $codecObj->unserialize($iccdata);
        $allowed_arr = [
            "82",
            "9F36",
            "9F27",
            "9F34",
            "9F10",
            "9F33",
            "84",
            "9F35",
            "9F37",
            "9F03",
            "9F02",
            "5F34",
            "9F1A",
            "5F2A",
            "9C",
            "9F26",
            "9A",
            "9F41",
            "95",
            "9F1E"
        ];
        $clear_arr = [];
        foreach ($iccdata as $key => $val) {
            if (in_array($key, $allowed_arr))
                $clear_arr[$key] = $val;
        }
        $codecObj = new Encoder();
        $iccPayload = $codecObj->serialize($clear_arr);
        return $iccPayload;
    }

    /**
     * Get Icc Data
     * 
     * @param mixed $iccdata 
     * @return mixed
     */
    private function getIccData($iccdata): mixed
    {
        $codecObj = new Decoder();
        $iccPayload = $codecObj->unserialize($iccdata);
        $icc_array = [];

        foreach ($iccPayload as $key => $val) {
            $icc_array[$key] = Str::upper($val->value);
        }
        return $icc_array;
    }

    /**
     * @param mixed $date 
     * @param mixed $format 
     * @return string 
     */
    public function formatDate(mixed $date, mixed $format): string
    {
        return ($format == 'y') ?  substr($date, 0, 2) :  substr($date, 2, 2);
    }

    /**
     * Log Transaction
     * 
     * @param mixed $merchant
     * @param mixed $agent
     * @param string $reference 
     * @param mixed $terminal_id 
     * @param string $issuer 
     * @param mixed $amount 
     * @param mixed $charge 
     * @param TransactPayload $transactionPayload 
     * @param string $provider 
     * @param string $tnx_type 
     * @param mixed $channel_terminal_id 
     * @return App\Services\Transaction 
     */
    public function logTransaction(
        mixed $merchant,
        mixed $agent,
        string $reference,
        mixed $terminal_id,
        mixed $amount,
        mixed $charge,
        TransactPayload $transactionPayload,
        string $provider,
        string $tnx_type = TransactionType::CASHOUT,
        mixed $channel_terminal_id
    ): Transaction {
        $channel_fee = (new TransactionChannelFee($provider, $amount, $tnx_type))->run();
        $channel_fee = Helpers::to_kobo($channel_fee);

        $transaction = new Transaction();
        $transaction->merchant_id = $merchant["id"];
        $transaction->agent_id = $agent["id"];
        $transaction->reference = $reference;
        $transaction->amount = $amount;

        $transaction->net_amount = $amount - $charge;
        $transaction->charge = $charge;
        $transaction->provider = $provider;
        $transaction->terminal_id = $terminal_id;
        $transaction->status = TransactionStatus::PENDING;

        $transaction->pan = $transactionPayload->getCardPan() ? Helpers::maskPan($transactionPayload->getCardPan(), 5) : null;
        $transaction->card_type = self::detectCardType($transactionPayload->getCardPan());
        $transaction->card_bank_issuer = null;
        $transaction->stan = $transactionPayload->getStan() ?? null;
        $transaction->channel_fee = $channel_fee;
        $transaction->commission = $charge - $channel_fee;

        $transaction->rrn = $transactionPayload->getRrn() ?? null;
        $transaction->type = $tnx_type;
        $transaction->customer_reference = $transactionPayload->getCustomerReference() ?? null;
        $transaction->ip = Helpers::load_ip();
        $transaction->channel_terminal_id = $channel_terminal_id;

        try {
            $transaction->save();
        } catch (Exception $e) {
        }
        return $transaction;
    }

    /**
     * @param mixed $num 
     * @return string
     */
    public static function detectCardType($num): string
    {
        if (preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/", $num)) {
            $card = CardTypes::VISA;
        } else if (preg_match("/^(?:5[1-5][0-9]{2}|222[1-9]|22[3-9][0-9]|2[3-6][0-9]{2}|27[01][0-9]|2720)[0-9]{12}$/", $num)) {
            $card = CardTypes::MASTER;
        } else if (preg_match("/^6(?:011|5[0-9]{2})[0-9]{12}$/", $num)) {
            $card = CardTypes::DISCOVER;
        } else if (preg_match("/^((506(0|1))|(507(8|9))|(6500))[0-9]{12,15}$/", $num)) {
            $card = CardTypes::VERVE;
        } else if (preg_match("/^(?:2131|1800|35[0-9]{3})[0-9]{11}$/", $num)) {
            $card = CardTypes::JCB;
        } else if (preg_match("/^3[47][0-9]{13}$/", $num)) {
            $card = CardTypes::AMERICAN_EXPRESS;
        } else {
            $card = 'unknown';
        }
        return $card;
    }
}
