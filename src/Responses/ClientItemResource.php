<?php

namespace Usoft\Ufit\Responses;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class ClientItemResource extends JsonResource
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

            if (
                method_exists($this->resource, Str::camel($key)) &&
                isset($this->{Str::camel($key)}) &&
                $this->resource->{Str::camel($key)}() instanceof \Illuminate\Database\Eloquent\Relations\Relation
            ) {
                unset($attributes[$key.'_id']);
                $attributes[$key] = new ClientItemResource($this->{Str::camel($key)});
            }

            if(('created_at' == $key || 'updated_at' == $key) && isset($value)){
                $attributes[$key] = Carbon::parse($attributes[$key])->format('Y-m-d H:i:s');
            }
        }

        return $attributes;
    }
}
