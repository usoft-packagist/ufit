<?php

namespace Usoft\Ufit\Services\Merchant\Requests;

use Usoft\Ufit\Abstracts\Http\FormRequest;

class MerchantStoreRequest extends FormRequest
{
    public function validations()
    {
        return [
            'name' => 'required|unique:' . config('schema.merchant') . '.merchants,name',
        ];
    }
}
