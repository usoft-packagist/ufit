<?php

namespace Usoft\Ufit\Abstracts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    // use SoftDeletes;

    use HasFactory;

    public array $store_rules = [];
    public array $update_rules = [];
}
