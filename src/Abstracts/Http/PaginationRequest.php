<?php

namespace Usoft\Ufit\Abstracts\Http;

use Illuminate\Foundation\Http\FormRequest;

class PaginationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'page' => 'required|numeric|min:1',
            'limit' => 'required|numeric|min:1',
        ];
    }
}
