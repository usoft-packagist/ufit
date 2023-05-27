<?php

namespace Usoft\Ufit\Abstracts;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Usoft\Ufit\Abstracts\Exceptions\NotFoundException;
use Usoft\Ufit\Abstracts\Exceptions\CreateException;
use Usoft\Ufit\Abstracts\Exceptions\UpdateException;
use Usoft\Ufit\Abstracts\Jobs\StoreJob;
use Usoft\Ufit\Abstracts\Jobs\UpdateJob;
use Usoft\Ufit\Interfaces\ServiceInterface;

abstract class Service implements ServiceInterface
{
    public Model $model;
    protected array $data = [];
    protected $private_key_name = 'id';

    protected $is_job = false;

    protected $query = null;

    /**
     * Class constructor.
     */
    public function __construct(Model $model = null)
    {
        $this->model = $model;
    }

    public function setPrivateKeyName($private_key_name)
    {
        $this->private_key_name = $private_key_name;
        return $this;
    }

    /**
     * @throws NotFoundException
     */
    public function get()
    {
        if (!isset($this->model)) {
            throw new NotFoundException();
        }
        return $this->model;
    }

    public function getQuery($request = null)
    {
        return ($this->query) ? $this->query : $this->model::query();
    }

    public function setQuery($query)
    {
        return $this->query = $query;
    }

    /**
     * @throws NotFoundException
     */
    public function set(Model $model)
    {
        if (isset($model)) {
            $this->model = $model;
        } else {
            throw new NotFoundException();
        }
        return $this;
    }

    /**
     * @throws NotFoundException
     */
    public function setById($data = [])
    {
        if(empty($data)){
            $data = $this->getData();
        }
        if(array_key_exists($this->private_key_name, $data)){
            $model = $this->getQuery()->where($this->private_key_name, $data[$this->private_key_name])->first();
            if ($model) {
                $this->set($model);
            } else {
                throw new NotFoundException('Not found with id:' . $data[$this->private_key_name]);
            }
        } else {
            throw new NotFoundException('Request key not found with name:' . $this->private_key_name);
        }
        return $this;
    }

    /**
     * @throws NotFoundException
     */
    public function getData()
    {
        if (!isset($this->data)) {
            throw new NotFoundException();
        }
        return $this->data;
    }
    /**
     * @throws NotFoundException
     */
    public function setData(array $data)
    {
        if (isset($data)) {
            $this->data = $data;
        } else {
            throw new NotFoundException();
        }
        return $this;
    }
    public function beforeCreate()
    {
        $rules = $this->model->create_rules;

        return $this;
    }
    public function createJob($data)
    {
        $this->is_job = true;
        StoreJob::dispatchSync($data, $this);
        return $this;
    }
    /**
     * @throws CreateException
     */
    public function create($data = [])
    {
        DB::beginTransaction();
        try {
            if(empty($data)){
                $data = $this->getData();
            }else{
                $this->setData($data);
            }
            $this->beforeCreate();
            $keys = Schema::getColumnListing((new $this->model)->getTable());
            $filtered_data = array_intersect_key($data, array_flip($keys));
            $model = $this->model::create($filtered_data);
        } catch (\Exception $exception) {
            DB::rollBack();
            $message = "Cannot create. ERROR:{$exception->getMessage()}. TRACE: {$exception->getTraceAsString()}";
            if ($this->is_job) {
                Log::error($message);
            } else {
                throw new CreateException($message);
            }
        }
        DB::commit();
        $this->set($model);
        $this->afterCreate();
        return $this;
    }

    public function afterCreate()
    {
        return $this;
    }
    public function beforeUpdate()
    {
        return $this;
    }

    public function updateJob($data)
    {
        $this->is_job = true;
        UpdateJob::dispatchSync($data, $this);
        return $this;
    }
    /**
     * @throws UpdateException
     */
    public function update($data = [])
    {
        DB::beginTransaction();
        try {
            if(empty($data)){
                $data = $this->getData();
            }else{
                $this->setData($data);
            }
            $this->beforeUpdate();
            $keys = Schema::getColumnListing((new $this->model)->getTable());
            $filtered_data = array_intersect_key($data, array_flip($keys));
            $this->get()->update($filtered_data);
        } catch (\Exception $exception) {
            DB::rollBack();
            $message = "Cannot update. ERROR:{$exception->getMessage()}. TRACE: {$exception->getTraceAsString()}";
            if ($this->is_job) {
                Log::error($message);
            } else {
                throw new UpdateException($message);
            }
        }
        DB::commit();
        $this->afterUpdate();
        return $this;
    }
    public function afterUpdate()
    {
        return $this;
    }

    public function createOrUpdate($data)
    {
        if ($model = $this->model::where($this->private_key_name, $data[$this->private_key_name])->first()) {
            $this->set($model)->update($data);
        } else {
            $this->create($data);
        }
        return $this;
    }
    public function beforeDelete()
    {
        return $this;
    }

    public function delete()
    {
        $this->beforeDelete();
        $this->get()->delete();
        $this->afterDelete();
        return $this;
    }
    public function afterDelete()
    {
        return $this;
    }


}
