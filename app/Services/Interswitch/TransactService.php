<?php

namespace App\Services\Interswitch;

use Exception;
use App\Enums\Provider;
use App\Utilities\Helpers;
use App\Utilities\PinBlock;
use Illuminate\Support\Str;
use App\Enums\TransactionType;
use App\Services\CashOutTrait;
use App\Enums\TransactionStatus;
use Illuminate\Support\Facades\Log;
use App\Services\TransactionMsgCodes;
use Illuminate\Support\Facades\Redis;
use App\Services\Interswitch\TransactBase;
use App\Exceptions\TransactFailedException;
use App\Services\Interswitch\TransactPayload;
use App\Services\Interswitch\TransactResponse;
use App\Services\Interswitch\TransactInterface;

class TransactService extends TransactBase implements TransactInterface
{
    use CashOutTrait;

    const TRANSACT_REQUEST = "kimonoservice/amex";

    /**
     * @param TransactPayload $transactPayload 
     * @return TransactResponse 
     * @throws TransactFailedException 
     */
    public function run(TransactPayload $transactPayload): TransactResponse
    {
        try {
            $payload = $this->constructPayload($transactPayload);
            $this->logInterswitchTransaction($transactPayload);
            $response = $this->makeProtectedPost(self::TRANSACT_REQUEST, $payload);
            if( $response["field39"] === TransactionMsgCodes::_00 ) {
                
                return TransactResponse::create()
                    ->setChannel($transactPayload->getChannel())
                    ->setResponseField39($response["field39"])
                    ->setResponseCode(TransactionMsgCodes::_00)
                    ->setResponseMessage(TransactionStatus::SUCCESSFUL)
                    ->setDescription($response["description"]);
            }

            return TransactResponse::create()
                ->setChannel($transactPayload->getChannel())
                ->setResponseField39($response["field39"])
                ->setResponseCode(TransactionMsgCodes::_92)
                ->setResponseMessage(TransactionStatus::FAILED)
                ->setDescription($response["description"]);

        } catch (\Exception $exception) {
            if ($exception instanceof TransactFailedException) {
                throw new TransactFailedException($exception->getMessage());
            }
            throw new TransactFailedException($exception->getMessage());
        }

    }

    /**
     * @param TransactPayload $transactPayload 
     * @return mixed
     */
    public function constructPayload(TransactPayload $transactPayload): mixed
    {
        $icc = $this->getIccData($transactPayload->getIccData());
        $data = [
            'AmountAuthorized' => $icc['9F02'],
            'AmountOther' => $icc['9F03'],
            'ApplicationInterchangeProfile' => $icc['82'],
            'Cryptogram' => $icc['9F26'],
            'CryptogramInformationData' => $icc['9F27'],
            'CvmResults' => $icc['9F34'],
            'DedicatedFileName' => $icc['84'] ?? '80',
            'TerminalCapabilities' => $icc['9F33'],
            'TerminalCountryCode' => (string)((int) $icc['9F1A']),
            'TerminalType' => $icc['9F35'],

            'batteryInformation' => self::BATTERY_INFO,
            'currencyCode' => self::CURRENCY_CODE,
            'languageInfo' => 'EN',
            'merchantId' => $this->merchant_id,
            'merhcantLocation' => $this->merchant_address,
            'posConditionCode' => self::POS_CONDITION_CODE,
            'posDataCode' => self::POS_DATA_CODE,
            'posEntryMode' => self::POS_ENTRY_MODE,
            'posGeoCode' => self::POS_GEO_CODE,
            'printerStatus' => self::POS_PRINTER_STATUS,

            'terminalId' => $this->channel_terminal_id,
            'transmissionDate' => $this->now(),
            'uniqueId' => $this->default_serial_no,
            'cardSequenceNumber' => sprintf("%03d", $icc['5F34']),
            'atc' => $icc['9F36'],
            'iad' => $icc['9F10'],
            'TransactionCurrencyCode' => (string)((int) $icc['5F2A']),
            'TerminalVerificationResult' => $icc['95'],
            'TransactionDate' => $icc['9A'],
            'TransactionType' => $icc['9C'],

            'UnpredictableNumber' => $icc['9F37'],
            'pan' => $transactPayload->getCardPan(),
            'expiryMonth' => $this->formatDate($transactPayload->getCardExpiry(), 'm'),
            'expiryYear' => $this->formatDate($transactPayload->getCardExpiry(), 'y'),
            'track2' => $transactPayload->getTrack2Data(),
            'originalTransmissionDateTime' => $this->now(),
            'stan' => $transactPayload->getStan(),
            'fromAccount' => Str::upper($transactPayload->getAccountType()),
            'minorAmount' => Helpers::to_kobo($transactPayload->getAmount()),
            'receivingInstitutionId' => $this->merchant_account_institution_code,
            
            'surcharge' => self::SURCHARGE,
            'ksn' => '',
            'ksnd' => self::KSND,
            'pinType' => self::PINTYPE,
            'pinBlock' => '',
            'keyLabel' => self::KEY_LABEL,
            'destinationAccountNumber' => $this->merchant_account_number,
            'extendedTransactionType' => self::EXTENDED_TRANSACTION_TYPE,
            'retrievalReferenceNumber' => $transactPayload->getRrn(),
            'ApplicationTransactionCounter' => $icc['9F41'],
            'customerReference' => $data->reference ?? null,
            'rawIccData' => $transactPayload->getIccData()
        ];
        return $this->transactXml($data);
    }

    /**
     * Log Interswitch transactions
     * @param TransactPayload $transactPayload 
     * @return mixed 
     */
    public function logInterswitchTransaction(TransactPayload $transactPayload)
    {
        $transaction = $this->logTransaction(
            $transactPayload->getMerchant(),
            $transactPayload->getAgent(),
            $transactPayload->getReference(),
            $transactPayload->getTerminalId(),
            $transactPayload->getAmount(),
            $transactPayload->getCharge(),
            $transactPayload,
            Provider::INTERSWITCH,
            TransactionType::CASHOUT,
            $this->channel_terminal_id
        );
        return $transaction;
    }

    /**
     * @param TransactPayload $transactPayload 
     * @return mixed
     */
    public function getInterswitchPinBlock(TransactPayload $transactPayload): mixed
    {
        $encryptedPinBlock = $transactPayload->getPinBlock();
        $sessionId = $transactPayload->getSessionId();
        if (!$encryptedPinBlock) return '';
        $pinBlock = new PinBlock;
        $clearPinBlock = $pinBlock->decryptPinBlock($encryptedPinBlock, $sessionId);
        $key = $clearPinBlock . '-----' . 'interswitch';
        $interswitch_encrypted_pin_block = Redis::get($key); /** Retrieve values from Redis */
        if (!$interswitch_encrypted_pin_block) {
            $interswitch_pinblock = $pinBlock->encrypt($clearPinBlock);
            $interswitch_encrypted_pin_block = json_encode($interswitch_pinblock);
            Redis::set($key, $interswitch_encrypted_pin_block, 'EX', $this->dayEnd()); /** Put values in Redis for 24 hrs */
        }
        return json_decode($interswitch_encrypted_pin_block);
    }
}
