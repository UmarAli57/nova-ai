<?php 

namespace App\Core\Request;

class HttpResponse {
    protected int $httpCode;

    protected ?string $headers = null;

    protected ?string $body = null;

    protected ?string $error = null;

    public function __construct(int $httpCode, ?string $headers = null, ?string $body = null, ?string $error = null)
    {
        $this->httpCode = $httpCode;
        $this->headers = $headers;   
        $this->body = $body;   
        $this->error = $error;
    }

    public function text(): ?string
    {
        return $this->body;
    }

    public function json(bool $assoc = true): mixed
    {
        if ($this->body !== null){
            return json_decode($this->body, associative: $assoc);
        }

        return null;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function isNetworkFailure(): bool
    {
        return $this->statusCode() === 0;
    }

    public function success(): bool
    {
        return ($this->statusCode() >= 200 && $this->statusCode() < 299);
    }

    public function fail(): bool
    {
        return ! $this->success();
    }

    public function statusCode(): int
    {
        return $this->httpCode;
    }

    public function getHeaders(): ?string
    {
        return $this->headers;
    }
}

?>