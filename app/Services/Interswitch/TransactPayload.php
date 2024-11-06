<?php

namespace App\Services\Interswitch;

class TransactPayload
{
    private string|null $channel;
    private string|null $terminalId;
    private string|int $amount;
    private string|int $charge;
    private string|null $cardPan;
    private string $cardExpiry;
    private array|null $merchant;
    private array|null $agent;
    private string $accountType;
    private string $iccData;
    private string $track2Data;
    private mixed $pinBlock;
    private string|null $sequenceNumber;
    private string|null $reference;
    private mixed $rrn;
    private mixed $stan;
    private mixed $customerReference;
    private string|null $sessionId;

    /**
     * @return TransactPayload
     */
    public static function create(): TransactPayload
    {
        return new TransactPayload();
    }

    /**
     * @param string $channel
     * @return TransactPayload
     */
    public function setChannel(?string $channel): static
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string $terminalId
     * @return TransactPayload
     */
    public function setTerminalId(?string $terminalId): static
    {
        $this->terminalId = $terminalId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTerminalId(): string|null
    {
        return $this->terminalId;
    }

    /**
     * @param string|int $amount
     * @return TransactPayload
     */
    public function setAmount(string|int $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string|int
     */
    public function getAmount(): string|int
    {
        return $this->amount;
    }

    /**
     * @param string|int $charge
     * @return TransactPayload
     */
    public function setCharge(string|int $charge): static
    {
        $this->charge = $charge;
        return $this;
    }

    /**
     * @return string|int
     */
    public function getCharge(): string|int
    {
        return $this->charge;
    }

    /**
     * @param string $cardPan
     * @return TransactPayload
     */
    public function setCardPan(?string $cardPan): static
    {
        $this->cardPan = $cardPan;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCardPan(): string|null
    {
        return $this->cardPan;
    }

    /**
     * @param string $cardExpiry
     * @return TransactPayload
     */
    public function setCardExpiry(string $cardExpiry): static
    {
        $this->cardExpiry = $cardExpiry;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardExpiry(): string
    {
        return $this->cardExpiry;
    }

    /**
     * @param array|null $merchant
     * @return TransactPayload
     */
    public function setMerchant(?array $merchant): static
    {
        $this->merchant = $merchant;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMerchant(): mixed
    {
        return $this->merchant;
    }

    /**
     * @param array|null $agent
     * @return TransactPayload
     */
    public function setAgent(?array $agent): static
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAgent(): mixed
    {
        return $this->agent;
    }

    /**
     * @param string $accountType
     * @return TransactPayload
     */
    public function setAccountType(string $accountType): static
    {
        $this->accountType = $accountType;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountType(): string
    {
        return $this->accountType;
    }

    /**
     * @param string $iccData
     * @return TransactPayload
     */
    public function setIccData(string $iccData): static
    {
        $this->iccData = $iccData;
        return $this;
    }

    /**
     * @return string
     */
    public function getIccData(): string
    {
        return $this->iccData;
    }

    /**
     * @param string $track2Data
     * @return TransactPayload
     */
    public function setTrack2Data(string $track2Data): static
    {
        $this->track2Data = $track2Data;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrack2Data(): string
    {
        return $this->track2Data;
    }

    /**
     * @param mixed $pinBlock
     * @return TransactPayload
     */
    public function setPinBlock(mixed $pinBlock): static
    {
        $this->pinBlock = $pinBlock;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPinBlock(): mixed
    {
        return $this->pinBlock;
    }

    /**
     * @param string $sequenceNumber
     * @return TransactPayload
     */
    public function setSequenceNumber(string $sequenceNumber): static
    {
        $this->sequenceNumber = $sequenceNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getSequenceNumber(): string
    {
        return $this->sequenceNumber;
    }

    /**
     * @param string $reference
     * @return TransactPayload
     */
    public function setReference(string $reference): static
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param mixed $rrn
     * @return TransactPayload
     */
    public function setRrn(mixed $rrn): static
    {
        $this->rrn = $rrn;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRrn(): mixed
    {
        return $this->rrn;
    }

    /**
     * @param mixed $stan
     * @return TransactPayload
     */
    public function setStan(mixed $stan): static
    {
        $this->stan = $stan;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStan(): mixed
    {
        return $this->stan;
    }

    /**
     * @param mixed $customerReference
     * @return TransactPayload
     */
    public function setCustomerReference(mixed $customerReference): static
    {
        $this->customerReference = $customerReference;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomerReference(): mixed
    {
        return $this->customerReference;
    }

    /**
     * @param string|null $sessionId
     * @return TransactPayload
     */
    public function setSessionId(?string $sessionId): static
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSessionId(): string|null
    {
        return $this->sessionId;
    }
}
