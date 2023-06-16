<?php

namespace Usoft\Ufit\Services\Merchant\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Usoft\Ufit\Models\Merchant;

class MerchantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // builder is the query
        $merchant_id = request()->merchant_id ?? null;
        if (is_int($merchant_id) && Merchant::where('id', $merchant_id)->exists()) {
            $builder->where($model->getTable() . '.merchant_id', $merchant_id);
        }
    }
}
