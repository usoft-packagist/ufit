<?php

namespace Usoft\Ufit\Requests;

use Usoft\Ufit\Abstracts\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    public function validations()
    {
        return [
            'id'=>'required|integer'
        ];
    }
}
