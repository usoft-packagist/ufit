<?php

namespace Usoft\Coin\Merchant\Requests;

use Usoft\Ufit\Abstracts\Http\FormRequest;

class MerchantUpdateRequest extends FormRequest
{
    public function validations()
    {
        return [
            'id'=> 'required|integer|exists:' . config('schema.merchant') . '.merchants,id',
            'name' => 'required|unique:' . config('schema.merchant') . '.merchants,name',
        ];
    }
}
