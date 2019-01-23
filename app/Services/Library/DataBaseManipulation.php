<?php

namespace App\Services\Library;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Exception;

class DataBaseManipulation
{
    private static $instance = null;
    private static $count = 0;

    private function __construct(){
    }

    public static function getInstance()
    {
        if (self::$instance == null){
            self::$instance = new DataBaseManipulation;
        }

        return self::$instance;
    }

    private function rollback($exception)
    {
        self::$count--;

        if (self::$count == 0){
            DB::rollback();
        } else {
            // sobe a exceção pois esse rollback esta dentro de dentro de outra transacao
            // isso faz com que a exeção vai subindo as camadas detro dos services
            throw $exception;
        }
    }

    private function logError($exception)
    {
        debug([
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ]);
    }

    private function serviceError($exception)
    {
        $this->logError($exception);
        $this->rollback($exception);
    }

    /**
     * [transaction 'control transaction database']
     * @param  [function] $callback
     * @return [mixed]
     */
    public function transaction($callback)
    {
        if (self::$count == 0){
            DB::beginTransaction();
        }

        self::$count++;

        try {
            $response = $callback();
            self::$count--;

            if (self::$count == 0){
                DB::commit();
            }

            return $response;
        } catch (QueryException $e){

            $this->serviceError($e);

            return (new ServiceData())->setErrors([' Falha ao executar o serviço. ']);
        } catch (Exception $e) {

            $this->serviceError($e);

            return $this->createDataError($e);
        }
    }

    /**
     * [createDataError 'make a response data error Default']
     * @param  Exception $e
     * @return [ServiceData]
     */
    private function createDataError(Exception $e)
    {
        return new ServiceData(null, (array)$e->getMessage());
    }
}
