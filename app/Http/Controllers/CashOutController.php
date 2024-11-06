<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Enums\TransactionType;
use App\Services\TransactionCost;
use Illuminate\Http\JsonResponse;
use App\Services\TransactionMsgCodes;
use App\Http\Requests\TransactRequest;
use App\Services\Interswitch\TransactPayload;

class CashOutController extends Controller
{
    /**
     * Transact
     * @param TransactRequest $request 
     * @return JsonResponse 
     * @throws Exception
     */
    public function transact(TransactRequest $request): JsonResponse
    {
        $merchant = self::getMerchant();
        $agent = self::getAgent();
        $sessionId = self::activeSession();

        try {
            $namespacePath = "App\\Services\\{$request->channel}\\TransactService";
            $charge = (new TransactionCost($merchant, TransactionType::CASHOUT, $request->amount))->run();
            $response = (new $namespacePath)->run(
                TransactPayload::create()
                    ->setChannel($request->channel)
                    ->setTerminalId($request->terminal_id)
                    ->setAmount($request->amount)
                    ->setCharge($charge)
                    ->setCardPan($request->card_pan)
                    ->setCardExpiry($request->card_expiry_date)
                    ->setMerchant($merchant)
                    ->setAgent($agent)
                    ->setSessionId($sessionId)
                    ->setAccountType($request->account_type)
                    ->setIccData($request->icc_data)
                    ->setTrack2Data($request->track2_data)
                    ->setPinBlock($request->pinBlock)
                    ->setSequenceNumber($request->sequence_number)
                    ->setRrn($this->helper()::generateStan())
                    ->setStan($this->helper()::generateRrn())
                    ->setCustomerReference($request->customer_reference)
                    ->setReference($this->helper()::generateReference())
            );
            $transactionResponse = [
                "responseCode" => $response->getResponseCode() ?? null,
                "responseMessage" => $response->getResponseMessage() ?? null,
                "description" => $response->getDescription(),
                "channel" => $response->getChannel(),
            ];

            if($response->getResponseFieldd39() !== TransactionMsgCodes::_00) {
                return $this->helper()->errorResponse(data: $transactionResponse, message: "Transaction was not successful");
            }
        } catch (Exception $exception) {
            return $this->helper()->errorResponse(message: $exception->getMessage());
        }
        return $this->helper()->successResponse(data: $transactionResponse, message: "Transaction was successful");
    }
}
