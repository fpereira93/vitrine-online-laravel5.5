<?php

namespace App\Http\Requests\Example;

use App\Http\Requests\BaseRequest;

class ExampleRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['Name' => 'required'];
    }
}
