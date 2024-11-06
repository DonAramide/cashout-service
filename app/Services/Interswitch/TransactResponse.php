<?php

namespace App\Services\Interswitch;

class TransactResponse
{
    private string|null $channel;
    private string|null $responseField;
    private string|null $responseCode;
    private string|null $responseMessage;
    private string|null $description;

    /**
     * @return TransactResponse
     */
    public static function create(): TransactResponse
    {
        return new TransactResponse();
    }

    /**
     * @param string|null $channel
     * @return TransactResponse
     */
    public function setChannel(?string $channel): static
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getChannel(): string|null
    {
        return $this->channel;
    }

    /**
     * @param string|null $responseField
     * @return TransactResponse
     */
    public function setResponseField39(?string $responseField): static
    {
        $this->responseField = $responseField;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponseFieldd39(): string|null
    {
        return $this->responseField;
    }

    /**
     * @param string|null $responseCode
     * @return TransactResponse
     */
    public function setResponseCode(?string $responseCode): static
    {
        $this->responseCode = $responseCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponseCode(): string|null
    {
        return $this->responseCode;
    }

    /**
     * @param string|null $responseMessage
     * @return static
     */
    public function setResponseMessage(?string $responseMessage): static
    {
        $this->responseMessage = $responseMessage;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponseMessage(): string|null
    {
        return $this->responseMessage;
    }

    /**
     * @param string|null $description
     * @return TransactResponse
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): string|null
    {
        return $this->description;
    }
}
