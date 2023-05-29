<?php

namespace Usoft\Ufit\Abstracts;

// use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Model extends Eloquent
{
    // use Cachable;
    use HasFactory;

    public array $store_rules = [];
    public array $update_rules = [];
    static array $scopes = [];

    // public array $item_resource = [];
    // public array $client_item_resource = [];


    protected static function boot()
    {
        parent::boot();
        foreach(self::$scopes as $scope){
            static::addGlobalScope(new $scope);
        }
    }
}
