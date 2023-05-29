<?php

namespace Usoft\Ufit\Services\Merchant\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class MerchantResource extends JsonResource
{

    public static $wrap = false;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
