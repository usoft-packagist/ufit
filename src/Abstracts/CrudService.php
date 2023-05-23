<?php

namespace Usoft\Ufit\Abstracts;


use Illuminate\Database\Eloquent\Model;

class CrudService extends Service
{
    protected Model $model;
    protected array $data;
    protected $private_key_name = 'id';

    protected $is_job = false;

    protected $query = null;
}
