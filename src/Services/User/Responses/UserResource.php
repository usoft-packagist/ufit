<?php

namespace Usoft\Ufit\Services\User\Responses;

use Illuminate\Http\Resources\Json\JsonResource;
use Usoft\Ufit\Services\Upload\Responses\UploadResource;

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
            'avatar' => new UploadResource($this->avatar),
        ];
    }
}
