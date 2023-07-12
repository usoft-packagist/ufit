<?php

namespace Usoft\Ufit\Abstracts;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    use Cachable;
    use HasFactory;
    static array $scopes = [];

    protected static function boot()
    {
        parent::boot();
        foreach(self::$scopes as $scope){
            static::addGlobalScope(new $scope);
        }
    }
}
