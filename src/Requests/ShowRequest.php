<?php

namespace Usoft\Ufit\Requests;

use Usoft\Ufit\Abstracts\Http\FormRequest;

class ShowRequest extends FormRequest
{
    public function validations()
    {
        return [
            'id'=>'required|integer'
        ];
    }
}
