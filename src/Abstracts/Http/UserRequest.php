<?php

namespace Usoft\Ufit\Abstracts\Http;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'merchant_id' => 'required|integer|exists:' . config('schema.merchant') . '.merchants,id',
            'user_id' => 'required|integer|exists:' . config('schema.user') . '.users,user_id,user_id,' . $this->user_id . ',merchant_id,' . $this->merchant_id,
        ];
    }
}
