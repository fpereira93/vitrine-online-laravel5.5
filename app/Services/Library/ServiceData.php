<?php

namespace App\Services\Library;

class ServiceData
{
    private $data = null;
    private $message = null;
    private $errors = Array();

    public function __construct($data = null, $errors = Array())
    {
        $this->setData($data);
        $this->setErrors($errors);
    }

    /**
     * [setMessage 'Set message string']
     * @param [string] $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * [getMessage 'get message']
     * @return [string]
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * [setErrors 'Set errors format array']
     * @param Array $errors
     */
    public function setErrors(Array $errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * [getErrors 'Get error service']
     * @return [Array]
     */
    public function getErrors(callable $callback = null)
    {
        if ($callback){
            return $callback($this->errors);
        }

        return $this->errors;
    }

    /**
     * [setData 'Set anything data']
     * @param [mixed] $data
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * [getData 'Get anything data']
     * @param  callable $callback
     * @return [mixed]
     */
    public function getData(callable $callback = null)
    {
        if ($callback){
            return $callback($this->data);
        }

        return $this->data;
    }

    /**
     * [getErrorByIndex 'get error string by position array']
     * @param  integer $index
     * @return [string]
     */
    public function getErrorByIndex($index)
    {
        return $this->errors[$index] ?? null;
    }

    /**
     * [validated 'check if validated data service']
     * @return [bool]
     */
    public function validated()
    {
        return empty($this->errors);
    }
}
