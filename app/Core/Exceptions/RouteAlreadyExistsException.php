<?php 

namespace App\Core\Exceptions;

class RouteAlreadyExistsException extends \Exception{
    #[\Override]
    public function __construct(string $message = "", int $code = 0, \Throwable|null $previous = null)
    {
        return parent::__construct($message, $code, $previous);
    }
}


?>