<?php

namespace App\Services\Interswitch;

use Exception;
use App\Enums\Provider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Mtownsend\XmlToArray\XmlToArray;
use App\Services\TransactionMsgCodes;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class TransactBase
{
    protected string $baseUrl;
    public $merchant_id;
    public $channel_terminal_id;
    public $default_serial_no;
    public $merchant_address;
    public $merchant_account_number;
    public $merchant_account_institution_code;
    public $connection_time_out_duration;
    public $request_time_out_duration;
    protected string $token_request_call;
    protected string $transact_call;
    protected null|string $access_token;

    const POS_CONDITION_CODE = '00';
    const POS_DATA_CODE = '510101511344101';
    const POS_ENTRY_MODE = '051';
    const POS_GEO_CODE = '00234000000000566';
    const POS_PRINTER_STATUS = '1';
    const CURRENCY_CODE = '566';
    const COUNTRY_CODE = '566';
    const BATTERY_INFO = 100;
    const SURCHARGE = '0';
    const KSND = '605';
    const PINTYPE = 'Dukpt';
    const KEY_LABEL = "000002";
    const EXTENDED_TRANSACTION_TYPE = "6106";

    public function __construct()
    {
        $this->merchant_id = config('cashout.interswitch_merchant_id');
        $this->default_serial_no = config('cashout.interswitch_default_serial');
        $this->channel_terminal_id = config('cashout.interswitch_default_terminal');
        $this->merchant_address = config('cashout.interswitch_merchant_address');
        $this->merchant_account_number = config('cashout.interswitch_merchant_account_number');
        $this->baseUrl = config('cashout.interswitch_base_url');
        $this->merchant_account_institution_code = config('cashout.interswitch_merchant_account_institution_code');
        $this->connection_time_out_duration = config('cashout.interswitch_connection_time_out_duration');
        $this->request_time_out_duration = config('cashout.interswitch_request_time_out_duration');
        $this->token_request_call = "requesttoken/perform-process";
        $this->transact_call = "kimonoservice/amex";
    }

    /**
     * @param mixed $data 
     * @return string 
     */
    public function transactXml($data): string
    {
        $xmlData = '
        <transferRequest>
            <cardData>
            <cardSequenceNumber>' . $data['cardSequenceNumber'] . '</cardSequenceNumber>
            <emvData>
                <AmountAuthorized>' . $data['AmountAuthorized'] . '</AmountAuthorized>
                <AmountOther>' . $data['AmountOther'] . '</AmountOther>
                <ApplicationInterchangeProfile>' . $data['ApplicationInterchangeProfile'] . '</ApplicationInterchangeProfile>
                <Cryptogram>' . $data['Cryptogram'] . '</Cryptogram>
                <CryptogramInformationData>' . $data['CryptogramInformationData'] . '</CryptogramInformationData>
                <CvmResults>' . $data['CvmResults'] . '</CvmResults>
                <DedicatedFileName>' . $data['DedicatedFileName'] . '</DedicatedFileName>
                <TerminalCapabilities>' . $data['TerminalCapabilities'] . '</TerminalCapabilities>
                <TerminalCountryCode>' . $data['TerminalCountryCode'] . '</TerminalCountryCode>
                <TerminalType>' . $data['TerminalType'] . '</TerminalType>
                <TerminalVerificationResult>' . $data['TerminalVerificationResult'] . '</TerminalVerificationResult>
                <TransactionCurrencyCode>' . $data['TransactionCurrencyCode'] . '</TransactionCurrencyCode>
                <TransactionDate>' . $data['TransactionDate'] . '</TransactionDate>
                <TransactionType>' . $data['TransactionType'] . '</TransactionType>
                <UnpredictableNumber>' . $data['UnpredictableNumber'] . '</UnpredictableNumber>
                <atc>' . $data['atc'] . '</atc>
                <iad>' . $data['iad'] . '</iad>
            </emvData>
            <track2>
                <expiryMonth>' . $data['expiryMonth'] . '</expiryMonth>
                <expiryYear>' . $data['expiryYear'] . '</expiryYear>
                <pan>' . $data['pan'] . '</pan>
                <track2>' . $data['track2'] . '</track2>
            </track2>
            </cardData>
            <destinationAccountNumber>' . $data['destinationAccountNumber'] . '</destinationAccountNumber>
            <extendedTransactionType>' . $data['extendedTransactionType'] . '</extendedTransactionType>
            <fromAccount>' . $data['fromAccount'] . '</fromAccount>
            <keyLabel>' . $data['keyLabel'] . '</keyLabel>
            <minorAmount>' . $data['minorAmount'] . '</minorAmount>
            <originalTransmissionDateTime>' . $data['originalTransmissionDateTime'] . '</originalTransmissionDateTime>
            ';


                if (isset($data['pinBlock']) && $data['pinBlock'] != null) {
                    $xmlData .= ' <pinData>
                                    <ksn>' . $data['ksn'] . '</ksn>
                                    <ksnd>' . $data['ksnd'] . '</ksnd>
                                    <pinBlock>' . $data['pinBlock'] . '</pinBlock>
                                    <pinType>' . $data['pinType'] . '</pinType>
                                </pinData>';
                }

                $xmlData .= '<receivingInstitutionId>' . $data['receivingInstitutionId'] . '</receivingInstitutionId>
            <retrievalReferenceNumber>' . $data['retrievalReferenceNumber'] . '</retrievalReferenceNumber>
            <stan>' . $data['stan'] . '</stan>
            <surcharge>' . $data['surcharge'] . '</surcharge>
            <terminalInformation>
                <batteryInformation>' . $data['batteryInformation'] . '</batteryInformation>
                <currencyCode>' . $data['currencyCode'] . '</currencyCode>
                <languageInfo>' . $data['languageInfo'] . '</languageInfo>
                <merchantId>' . $data['merchantId'] . '</merchantId>
                <merhcantLocation>' . $data['merhcantLocation'] . '</merhcantLocation>
                <posConditionCode>' . $data['posConditionCode'] . '</posConditionCode>
                <posDataCode>' . $data['posDataCode'] . '</posDataCode>
                <posEntryMode>' . $data['posEntryMode'] . '</posEntryMode>
                <posGeoCode>' . $data['posGeoCode'] . '</posGeoCode>
                <printerStatus>' . $data['printerStatus'] . '</printerStatus>
                <terminalId>' . $data['terminalId'] . '</terminalId>
                <terminalType>' . $data['TerminalType'] . '</terminalType>
                <transmissionDate>' . $data['transmissionDate'] . '</transmissionDate>
                <uniqueId>' . $data['uniqueId'] . '</uniqueId>
            </terminalInformation>
            <toAccount></toAccount>
        </transferRequest>
        ';
        return $xmlData;
    }

    /**
     * @param mixed $data 
     * @return string 
     */
    public static function initiateXml(mixed $data): string
    {
        return '
            <tokenPassportRequest>
                <terminalInformation>
                    <merchantId>' . $data['merchantId'] . '</merchantId>
                    <terminalId>' . $data['terminalId'] . '</terminalId>
                </terminalInformation>
            </tokenPassportRequest>
            ';
    }

    /**
     * @return string|null 
     */
    private function getTokenPayload(): string|null
    {
        $data = [
            'merchantId' => $this->merchant_id,
            'terminalId' => $this->channel_terminal_id
        ];
        return self::initiateXml($data);
    }

    /**
     * @return mixed
     */
    private function initiateToken(): mixed
    {
        try {
            $key = $this->channel_terminal_id .'----------'.Provider::INTERSWITCH;
            $response = Redis::get($key); /** Retrieve values from Redis */
            if($response) {
                $response_access_token = json_decode($response);
                $this->access_token = $response_access_token;
                return $response_access_token;
            }
            $guzzleClient = new GuzzleClient();
            $request = $guzzleClient->post(
                "{$this->baseUrl}/{$this->token_request_call}",
                [
                    'connect_timeout' => $this->connection_time_out_duration,
                    'timeout' => $this->request_time_out_duration,
                    'body' => $this->getTokenPayload(),
                    'headers' => $this->headers([]),
                    'http_errors' => false,
                    'exceptions' => false,
                ]
            );
            $response = $request->getBody()->getContents();
            $response = $this->handleTokenResponse($response);
            Redis::set($key, json_encode($response["token"]), 'EX', 86400); /** Put values in Redis for 24 hrs */

            if ( isset($response["token"]) && !empty($response["token"]) ) $this->access_token = $response["token"];
            else $this->access_token = null;
            return $response;

        } catch(Exception $exception) {
            Log::error($exception);
            return false;
        }
    }

    /**
     * @param mixed $header 
     * @return array 
     */
    public function headers($header): array
    {
        $base_header = [
            'Content-Type' => 'Application/xml',
            'accept' => '*/*',
            'accept-encoding' => 'gzip, deflate'
        ];

        return array_merge($base_header, $header);
    }

    /**
     * @return array
     */
    protected function authorizationToken(): array
    {
        $this->initiateToken();
        $header = [
            'Authorization' => 'Bearer ' . $this->access_token
        ];
        return $header;
    }

    /**
     * @param string $path 
     * @param mixed $payload 
     * @return mixed 
     */
    public function makeProtectedPost(string $path, mixed $payload): mixed
    {
        try {
            $guzzleClient = new GuzzleClient();
            $request = $guzzleClient->post(
                "{$this->baseUrl}/{$path}",
                [
                    'connect_timeout' => $this->connection_time_out_duration,
                    'timeout' => $this->request_time_out_duration,
                    'body' => $payload,
                    'headers' => $this->headers($this->authorizationToken()),
                    'http_errors' => false,
                    'exceptions' => false,
                ]
            );
            $response = $request->getBody()->getContents();
            $response = $this->handleTransactResponse($response);
            Log::info($response);
            
        } catch(Exception $exception) {
            $exceptionResponse = [
                'field39' =>  TransactionMsgCodes::_92,
                'description' => TransactionMsgCodes::getDescription('92'),
                'responseCode' => TransactionMsgCodes::_92,
                'responseMessage' =>TransactionMsgCodes::getDescription('92'),
            ];
            return $exceptionResponse;
        }

        return $response;
    }

    /**
     * @param mixed $response 
     * @return mixed 
     */
    private function handleTokenResponse(mixed $response): mixed
    {
        if($this->isJson($response)) {
            return json_decode($response, true);
        } else if ($this->isXml($response)) {
            /** XML to array */
            $xmlToArrayResponse = XmlToArray::convert($response);
            $response = $xmlToArrayResponse['tokenPassportResponse'];
            return $response; 
        }
        return "Unknown format";
    }

    /**
     * @param mixed $response
     * @return mixed 
     */
    private function handleTransactResponse(mixed $response): mixed
    {
        if($this->isJson($response)) {
            return json_decode($response, true);
        } else if ($this->isXml($response)) {
            /** XML to array */
            $xmlToArrayResponse = XmlToArray::convert($response);
            return $xmlToArrayResponse; 
        }
        return "Unknown format";
    }

    /**
     * @param mixed $jsonResponse 
     * @return bool 
     */
    public function isJson($jsonResponse): bool
    {
        json_decode($jsonResponse);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * @param mixed $xmlResponse 
     * @return bool 
     */
    public function isXml(mixed $xmlResponse): bool
    {
        $xml = @simplexml_load_string($xmlResponse);
        return ($xml !== false);
    }
    
    
}
