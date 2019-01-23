<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use ReflectionClass;
use Exception;

class BaseResponse implements Responsable
{
    const ERROR = 0;
    const SUCCESS = 1;

    const UNAUTHENTICATED = 401;
    const FORBIDDEN = 403;

    protected $status;
    protected $message = null;
    protected $response = null;

    /**
     * [transformData 'Opcao de converter os dados']
     * @param  [type] $dataTransform [Dados a serem convertidos]
     * @return [void]
     */
    protected function transformData($dataTransform)
    {
        return $dataTransform;
    }

    /**
     * [setResponse 'Seta o reponse']
     */
    public function setResponse($response)
    {
        $this->response = $this->transformData($response);
        return $this;
    }

    /**
     * [setStatus 'Seta os status']
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * [setMessage 'Seta a mensagem']
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * [createResponse 'Cria Response']
     * @param  [int] $status
     * @param  [string] $message
     * @param  [mixed] $response
     * @return [BaseResponse]
     */
    private static function createResponse($status, $message, $response = null)
    {
        $res = new static();

        $res->setStatus($status);
        $res->setMessage($message);
        $res->setResponse($response);

        return $res;
    }

    /**
     * [allProperties 'Devolve todas as propriedade do response']
     */
    private static function allProperties($object)
    {
        $reflectionClass = new ReflectionClass(get_class($object));

        $array = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $property->setAccessible(true);
            $array[$property->getName()] = $property->getValue($object);
            $property->setAccessible(false);
        }

        return $array;
    }

    /**
     * [success 'Devolve instancia ja com status Sucesso']
     */
    public static function success($message)
    {
        return self::createResponse(self::SUCCESS, $message);
    }

    /**
     * [error 'Devolve instancia ja com status Erro']
     */
    public static function error($message)
    {
        return self::createResponse(self::ERROR, $message);
    }

    /**
     * [successData 'Devolve instancia com status Sucesso e com Dados']
     */
    public static function successData($response, $message = "")
    {
        return self::createResponse(self::SUCCESS, $message, $response);
    }

    /**
     * [errorData 'Devolve instancia com status Erro e com Dados']
     */
    public static function errorData($response, $message = "")
    {
        return self::createResponse(self::ERROR, $message, $response);
    }

    /**
     * [denied 'Sem permissão']
     */
    public static function denied($status, $message)
    {
        if (!in_array($status, [ self::UNAUTHENTICATED, self::FORBIDDEN, ])){
            throw new Exception("Status denied not found");
        }

        return self::createResponse($status, $message);
    }

    public function toJson()
    {
        return json_encode(self::allProperties($this));
    }

    public function toResponse($request)
    {
        return response()->json(self::allProperties($this));
    }
}
