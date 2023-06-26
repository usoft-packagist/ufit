<?php

namespace Usoft\Ufit\Models;

use Usoft\Ufit\Services\Merchant\Scopes\MerchantScope;
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

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function upload()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }

    public function avatar()
    {
        return $this->belongsTo(Upload::class, 'upload_id');
    }
}
