<?php 

namespace App\Support;

use App\Core\Request\HttpRequest;

class Http {
    public static function url(string $url){
        return (new HttpRequest)->url($url);
    }

    public static function method(string $method){
        return (new HttpRequest)->method($method);
    }

    public static function headers(array $headers = [], bool $overwrite = true){
        return (new HttpRequest)->headers($headers, $overwrite);
    }

    public static function setHeader(string $key, mixed $value){
        return (new HttpRequest)->setHeader($key, $value);
    }
    
    public static function withToken(string $token){
        return (new HttpRequest)->withToken($token);
    }
    
    public static function contentType(string $contentType){
        return (new HttpRequest)->contentType($contentType);
    }
    
    public static function acceptType(string $acceptType){
        return (new HttpRequest)->acceptType($acceptType);
    }
    
    public static function withBody(mixed $body = null){
        return (new HttpRequest)->withBody($body);
    }
    
    public static function timeout(int $seconds){
        return (new HttpRequest)->timeout($seconds);
    }
    
    public static function connectTimeout(int $seconds){
        return (new HttpRequest)->connectTimeout($seconds);
    }
    
    public static function verifySSL(){
        return (new HttpRequest)->verifySSL();
    }
    
    public static function withVerifySSL(){
        return (new HttpRequest)->withoutVerifySSL();
    }

    public static function forceToFlushOutput(){
        return (new HttpRequest)->forceToFlushOutput();
    }
    
    public static function streamWriteFunction(mixed $action, ?string $method = null){
        return (new HttpRequest)->streamWriteFunction($action, $method);
    }

    public static function streamHeaderFunction(mixed $action, ?string $method = null){
        return (new HttpRequest)->streamHeaderFunction($action, $method);
    }

    public static function send(?string $url = null, ?string $method = null){
        return (new HttpRequest)->send($url, $method);
    }
    
    public static function get(?string $url = null){
        return (new HttpRequest)->get($url);
    }
    
    public static function post(?string $url = null){
        return (new HttpRequest)->post($url);
    }
    
    public static function put(?string $url = null){
        return (new HttpRequest)->put($url);
    }
    
    public static function delete(?string $url = null){
        return (new HttpRequest)->delete($url);
    }
}


?>