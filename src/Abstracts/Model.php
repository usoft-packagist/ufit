<?php

namespace Usoft\Ufit\Abstracts;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class Model extends Eloquent
{
    use SoftDeletes;

}
