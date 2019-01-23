<?php

namespace App\Services;

use Validator;
use App\Services\Library\ServiceData;
use App\Services\Library\DataBaseManipulation;
use App\Exceptions\Api\Exception as ExceptionApi;
use Exception;

class BaseService
{
    /**
     * [validate 'Validate params service']
     * @param  Array  $params
     * @param  Array  $rules
     * @param  Boolean  $callException
     * @param  Function  $conditional
     * @return [ServiceData]
     */
    protected function validate(Array $params, Array $rules, $callException = false, $conditional = null)
    {
        if (is_callable($conditional)){
            $result = $conditional($params, $rules);

            if ($result){
                $rules = array_merge($rules, $result);
            }
        }

        $validator = Validator::make($params, $rules);

        $responseService = new ServiceData;

        if ($validator->fails()){
            $messages = $validator->errors()->messages();

            if ($callException){
                throw new ExceptionApi($messages);
            }

            $responseService->setErrors($messages);
        }

        return $responseService;
    }

    /**
     * [transaction 'control transaction database']
     * @param  [function] $callback
     * @return [mixed]
     */
    protected function transaction(callable  $callback)
    {
        return DataBaseManipulation::getInstance()->transaction($callback);
    }

    /**
     * [holdMistake 'control error exception]
     * @param  [function] $callback
     * @return [mixed]
     */
    protected function holdMistake(callable $callback, $callbackWhenError = null)
    {
        try {
            return $callback();
        } catch (ExceptionApi $e) {

            if (is_callable($callbackWhenError)){
                return $callbackWhenError($e);
            }
            return new ServiceData(null, (array)$e->getMessage());

        } catch (Exception $e) {

            if (is_callable($callbackWhenError)){
                return $callbackWhenError($e);
            }
            return new ServiceData(null, (array)$e->getMessage());
        }
    }
}
