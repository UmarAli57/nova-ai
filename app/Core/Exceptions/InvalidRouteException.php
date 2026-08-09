<?php 

namespace App\Core\Exceptions;

class InvalidRouteException extends \Exception{
    #[\Override]
    public function __construct(string $message = "", int $code = 0, \Throwable|null $previous = null)
    {
        return parent::__construct($message, $code, $previous);
    }
}


?>