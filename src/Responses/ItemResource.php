<?php

namespace Usoft\Ufit\Responses;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Usoft\Ufit\Services\Merchant\Responses\MerchantResource;
use Usoft\Ufit\Services\Upload\Responses\UploadResource;
use Usoft\Ufit\Services\User\Responses\UserResource;
class ItemResource extends JsonResource
{

    public static $wrap = false;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->forModel();
    }

    public function forModel($with = [])
    {
        if (isset($with))
            $this->load($with);

        $attributes = $this->resource->toArray();
        foreach ($attributes as $key => $value) {
            $key = str_replace('_id', '', $key);
            $method = Str::camel($key);
            if (
                method_exists($this->resource, $method) &&
                isset($this->{$method}) &&
                $this->resource->{$method}() instanceof \Illuminate\Database\Eloquent\Relations\Relation
            ) {
                unset($attributes[$key.'_id']);
                switch ($key){
                    case 'upload':
                        $attributes[$key] = new UploadResource($this->{$method});
                        break;
                    case 'merchant':
                        $attributes[$key] = new MerchantResource($this->{$method});
                        break;
                    case 'user':
                        $attributes[$key] = new UserResource($this->{$method});
                        break;
                    default:
                        $attributes[$key] = new ItemResource($this->{$method});
                }
            }

            if(('created_at' == $key || 'updated_at' == $key) && isset($value)){
                $attributes[$key] = Carbon::parse($attributes[$key])->format('Y-m-d H:i:s');
            }
        }

        return $attributes;
    }
}
