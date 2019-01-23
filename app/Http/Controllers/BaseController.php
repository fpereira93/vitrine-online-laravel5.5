<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\BaseResponse;

class BaseController extends Controller
{

    /**
     * [formatResponse 'Ajusta response']
     * @param  [ServiceData] $dataService
     */
    protected function formatResponse($dataService)
    {
        if ($dataService->validated()){
            return BaseResponse::successData($dataService->getData(), $dataService->getMessage());
        }

        return BaseResponse::error($dataService->getErrors());
    }

}
