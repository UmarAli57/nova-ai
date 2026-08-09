<?php 

namespace App\Core\Request;

use Closure;

class HttpRequest {
    protected array $headers = [];

    protected string $method = "";

    protected string $url = "";

    protected mixed $body = null;

    protected bool $isSecure = true;

    protected ?int $timeout = null;

    protected ?int $connectionTimeout = null;

    protected bool $returnTransfer = true;

    protected mixed $writeFunction = null;

    protected mixed $headerFunction = null;

    
    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function method(string $method)
    {
        $this->method = $method;
        return $this;
    }

    public function headers(array $headers, bool $overwrite = true): self
    {
        if ($overwrite){
            $this->headers = $headers;
        } 
        else{    
            foreach ($headers as $name => $val){
                $this->headers[$name] = $val;
            }
        }
        return $this;
    }

    public function setHeader(string $key, mixed $value): self
    {
        $this->headers([$key => $value], overwrite: false);
        return $this;
    }

    public function contentType(string $type): self
    {
        $this->setHeader("Content-Type", $type);
        return $this;
    }

    public function acceptType(string $type): self
    {
        $this->setHeader("Accept", $type);
        return $this;
    }

    public function withToken(string $token): self
    {
        $this->setHeader("Authorization", "Bearer $token");
        return $this;
    }

    public function withBody(mixed $data): self
    {
        $this->body = $data;
        return $this;
    }

    public function verifySSL(): self
    {
        $this->isSecure = true;
        return $this;
    }
        
    public function withoutVerifySSL(): self
    {
        $this->isSecure = false;
        return $this;
    }

    public function timeout(int $seconds): self
    {
        $this->timeout = $seconds;
        return $this;
    }

    public function connectTimeout(int $seconds): self
    {
        $this->connectionTimeout = $seconds;
        return $this;
    }

    public function forceToFlushOutput(): self
    {
        $this->returnTransfer = false;
        return $this;
    }

    public function streamWriteFunction(mixed $action, ?string $method = null): self
    {
        if (is_object($action) && !($action instanceof Closure)){
            $this->writeFunction = [$action, $method];
        }
        else {
            $this->writeFunction = $action;
        }

        return $this;
    }

    public function streamHeaderFunction(mixed $action, ?string $method = null): self
    {
        if (is_object($action) && !($action instanceof Closure)){
            $this->headerFunction = [$action, $method];
        }
        else {
            $this->headerFunction = $action;
        }

        return $this;
    }

    public function send(?string $url = null, ?string $method = null)
    {
        $method = strtoupper($method ?? $this->method);

        if ($method === "GET"){
            $this->body = null;
        }

        $options = [
            CURLOPT_URL => $url ?? $this->url,
            CURLOPT_RETURNTRANSFER => $this->returnTransfer,
            CURLOPT_POSTFIELDS => $this->resolveBody(),
            CURLOPT_SSL_VERIFYPEER => $this->isSecure,
            CURLOPT_HTTPHEADER => $this->resolveHeaders()
        ];

        if (in_array($method, ['POST', "GET"])){
            $options[CURLOPT_POST] = $method === "POST";
        }
        else{
            $options[CURLOPT_CUSTOMREQUEST] = $method;
        }

        if ($this->timeout){
            $options[CURLOPT_TIMEOUT] = $this->timeout;
        }

        if ($this->connectionTimeout){
            $options[CURLOPT_CONNECTTIMEOUT] = $this->connectionTimeout;
        }

        if ($this->writeFunction){
            if (is_array($this->writeFunction)){
                $action = function ($ch, $chunk){
                    [$obj, $method] = $this->writeFunction;
                    return $obj->$method($ch, $chunk);
                };
            }
            else{
                $action = $this->writeFunction;
            }

            $options[CURLOPT_WRITEFUNCTION] = $action;
        }

        if ($this->headerFunction){
            if (is_array($this->headerFunction)){
                $action = function ($ch, $chunk){
                    [$obj, $method] = $this->headerFunction;
                    return $obj->$method($ch, $chunk);
                };
            }
            else{
                $action = $this->headerFunction;
            }

            $options[CURLOPT_HEADERFUNCTION] = $action;
        }

        $curl = curl_init();

        curl_setopt_array($curl, $options);

        $exec = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($exec === false){
            $error = curl_error($curl);
            return new HttpResponse(httpCode: $statusCode, error: $error);
        }
        elseif ($exec === true){
            return new HttpResponse(httpCode: $statusCode);
        }

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $resHeaders = substr($exec, 0, $headerSize);
        $resBody = substr($exec, $headerSize);

        return new HttpResponse(
            httpCode: $statusCode,
            headers: $resHeaders,
            body: $resBody
        );
    }

    public function get(?string $url = null)
    {
        return $this->send($url, "get");
    }

    public function post(?string $url = null)
    {
        return $this->send($url, "post");
    }

    public function put(?string $url = null)
    {
        return $this->send($url, "put");
    }

    public function delete(?string $url = null)
    {
        return $this->send($url, "delete");
    }

    protected function resolveHeaders()
    {
        $headers = [];
        
        foreach ($this->headers as $name => $val){
            $headers[] = "$name: $val";
        }

        return $headers;
    }

    protected function resolveBody(): ?string
    {
        if ($this->body !== null){
            if (isset($this->headers['Content-Type']) && str_contains($this->headers['Content-Type'], "json")){
                return json_encode($this->body);
            }
            elseif (is_array($this->body)){
                return http_build_query($this->body);
            }
            else{
                return $this->body;
            }
        }

        return null;
    }
}


?>