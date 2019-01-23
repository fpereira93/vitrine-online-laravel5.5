<?php
namespace App\Exceptions\Api;

use Exception;

class DuplicateDataException extends Exception 
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}
