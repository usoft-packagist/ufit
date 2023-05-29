<?php

namespace Usoft\Ufit\Abstracts;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    // use SoftDeletes;

    use HasFactory, Cachable;

    public array $store_rules = [];
    public array $update_rules = [];
}
