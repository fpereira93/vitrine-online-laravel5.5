<?php
namespace App\Exceptions\Api;

use Exception;

class RegisterNotFoundException extends Exception 
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
}
