<?php

namespace Usoft\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Merchant extends Model
{
    use SoftDeletes;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('schema.merchant');
    }

    protected $table = 'merchants';
    public $timestamps = true;
    protected $casts = [
        'name' => 'string'
    ];
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::uuid4()->toString();
        });
    }
}
