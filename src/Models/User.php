<?php

namespace Usoft\Models;

use Usoft\Coin\Merchant\Scopes\MerchantScope;
use Usoft\Ufit\Abstracts\Model;

class User extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('schema.user');
    }
    protected $table = 'users';

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::addGlobalScope(new MerchantScope);
    }
}
