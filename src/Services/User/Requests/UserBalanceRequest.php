<?php

namespace Usoft\Coin\User\Requests;
use Usoft\Ufit\Abstracts\Http\FormRequest;

class UserBalanceRequest extends FormRequest
{
    protected $is_user_request = true;
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function validations()
    {
        return [
            'merchant_id' => 'required|integer|exists:' . config('schema.merchant') . '.merchants,id',
            'user_id' => 'required|integer',
        ];
    }
}
