<?php

namespace Usoft\Coin\User\Responses;

use Illuminate\Http\Resources\Json\JsonResource;
use Usoft\Coin\Upload\Responses\UploadResource;

class UserResource extends JsonResource
{

    public static $wrap = false;

    public function toArray($request)
    {
        return [
            'id' => $this->user_id, 
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'avatar'=>new UploadResource($this->avatar),
        ];
    }
}
