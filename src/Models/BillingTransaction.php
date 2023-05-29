<?php

namespace Usoft\Ufit\Models;

use Usoft\Ufit\Services\Merchant\Scopes\MerchantScope;
use Usoft\Ufit\Abstracts\Model;

class BillingTransaction extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('schema.coin');
    }
    protected $guarded = ['id'];

    protected $casts = [
        'merchant_id' => 'integer',
        'user_id' => 'integer',
        'transaction_id' => 'string',
        'relation_type' => 'string',
        'relation_id' => 'integer',
        'amount' => 'integer',
        'created_at' => 'timestamp',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function user()
    {
        $relation = $this->belongsTo(User::class, 'user_id', 'user_id');
        if ($merchant_id = $this->merchant_id) {
            $relation = $relation->where('merchant_id', $merchant_id);
        }
        return $relation;
    }
    protected static function booted()
    {
        static::addGlobalScope(new MerchantScope);
    }
}
