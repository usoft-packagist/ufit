<?php

namespace Usoft\Ufit\Abstracts\Http;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function rules()
    {
        return [
            'search' => 'sometimes|string',
        ];
    }
}
