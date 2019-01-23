<?php
namespace App\Exceptions\Api;

use Exception as RealException;

class Exception extends RealException
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}
