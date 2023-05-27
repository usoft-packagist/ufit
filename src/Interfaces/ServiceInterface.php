<?php

namespace Usoft\Ufit\Interfaces;

use Usoft\Ufit\Abstracts\Model;

interface ServiceInterface
{
    public function setPrivateKeyName(string $private_key_name);
    public function get();
    public function set(Model $model);
    public function setById(array $data);

    public function getQuery($request);

    public function getData();
    public function setData(array $data);

    public function beforeCreate();
    public function createJob(array $data);
    public function create(array $data);
    public function afterCreate();

    public function beforeUpdate();
    public function updateJob(array $data);
    public function update(array $data);
    public function afterUpdate();

    public function createOrUpdate(array $data);
    public function beforeDelete();
    public function delete();
    public function afterDelete();

}
