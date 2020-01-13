<?php


namespace App\Domain;

use Exception;
use Throwable;

class DomainException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
