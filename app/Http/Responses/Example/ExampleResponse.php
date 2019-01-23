<?php

namespace App\Http\Responses\Example;

use App\Http\Responses\BaseResponse;

class ExampleResponse extends BaseResponse // sempre herdar de "BaseResponse"
{

    // aqui poderá customizar o response.
    // Caso não precise customizar os response do serviço nem precisa criar o Response, use o BaseResponse mesmo
    protected function transformData($dataTransform)
    {
        return $dataTransform->map(function($record){
            return [
                "id" => $record->id,
                "nome" => $record->name,
                "email" => $record->email
            ];
        });
    }
}
