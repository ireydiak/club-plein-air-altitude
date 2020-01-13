<?php


namespace App\Database\Adapter;

use Exception;
use Throwable;

class AdapterException extends Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
