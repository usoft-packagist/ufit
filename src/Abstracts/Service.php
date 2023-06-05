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
use Illuminate\Support\Str;
use Usoft\Ufit\Requests\DestroyRequest;
use Usoft\Ufit\Requests\PaginationRequest;
use Usoft\Ufit\Requests\ShowRequest;

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
    public function __construct(Model $model)
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
        if (empty($data)) {
            $data = $this->getData();
        }
        if (array_key_exists($this->private_key_name, $data)) {
            $model = $this->withoutScopes()->where($this->private_key_name, $data[$this->private_key_name])->first();
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
            if (empty($data)) {
                $data = $this->getData();
            } else {
                $this->setData($data);
            }
            $this->beforeCreate();
            $keys = $this->getModelColumns();
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
            if (empty($data)) {
                $data = $this->getData();
            } else {
                $this->setData($data);
            }
            $this->beforeUpdate();
            $keys = $this->getModelColumns();
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

    public function withoutScopes(array $scopes = [])
    {
        $new_query = $this->getQuery();
        if (count($scopes) > 0) {
            foreach ($scopes as $scope) {
                $new_query = $new_query->withoutGlobalScope($scope);
            }
        } else {
            $new_query->withoutGlobalScopes();
        }
        $this->setQuery($new_query);
        return $this;
    }

    public function getModelColumns($model = null)
    {
        if(!$model){
            $model=$this->model;
        }
        // $table = (new $model)->getTable();
        // $keys = Schema::getColumnListing($table);
        // $model = new $model();
        $keys = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        return $keys;
    }

    public function indexRules($rules = [], $replace = false){
        if ($replace) {
            return $rules;
        } else {
            $model_rules = (new PaginationRequest)->rules();
            return array_merge($rules, $model_rules);
        }
    }


    public function showRules($rules = [], $replace = false){
        if ($replace) {
            return $rules;
        } else {
            $model_rules = (new ShowRequest)->rules();
            return array_merge($rules, $model_rules);
        }
    }

    public function destroyRules($rules = [], $replace = false){
        if ($replace) {
            return $rules;
        } else {
            $model_rules = (new DestroyRequest)->rules();
            return array_merge($rules, $model_rules);
        }
    }

    public function storeRules($rules = [], $replace = false)
    {
        if ($replace) {
            return $rules;
        } else {
            // $table = (new $this->model)->getTable();
            $keys = $this->getModelColumns();
            $model_rules = [];
            $required_fields = [];
            $exceptional_fields = ['id', 'updated_at', 'created_at', 'deleted_at'];
            foreach ($keys as $key) {
                // $type = Schema::getColumnType($table, $key);
                // $model_rules[$key]='required|'.$type;
                if (in_array($key, $exceptional_fields)) {
                    //skip
                    continue;
                }
                $rule = 'required';
                if (in_array($key, $required_fields)) {
                    $rule = 'required';
                }
                $rule = $this->getRelationRule($key, $rule);
                $model_rules[$key] = $rule;
            }
            return array_merge($rules, $model_rules);
        }
    }

    public function updateRules($rules = [], $replace = false)
    {
        if ($replace) {
            return $rules;
        } else {
            // $table = (new $this->model)->getTable();
            $keys = $this->getModelColumns();
            $model_rules = [];
            $required_fields = ['id'];
            $exceptional_fields = ['updated_at', 'created_at', 'deleted_at'];
            foreach ($keys as $key) {
                // $type = Schema::getColumnType($table, $key);
                // $model_rules[$key]='required|'.$type;
                if (in_array($key, $exceptional_fields)) {
                    //skip
                    continue;
                }
                $rule = 'sometimes';
                if (in_array($key, $required_fields)) {
                    $rule = 'required';
                }
                $rule = $this->getRelationRule($key, $rule);
                $model_rules[$key] = $rule;
            }
            return array_merge($rules, $model_rules);
        }
    }

    private function getRelationRule($key, $rule)
    {
        if (str_ends_with($key, '_id')) {
            $relation_table = Str::plural(str_replace('_id', '', $key));
            $relation = Str::camel(str_replace('_id', '', $key));
            if (
                method_exists($this->model, $relation)
                && $this->model->{Str::camel($key)}() instanceof \Illuminate\Database\Eloquent\Relations\Relation    
            ) {
                $relation_model = (new $this->model)->{$relation}()->getRelated();
                $tableName = $relation_model->getTable();
                $relation_keys = $this->getModelColumns($relation_model);
                if(in_array($key, $relation_keys)){
                    $rule = $rule . "|exists:{$tableName},{$key}";
                }else{
                    $rule = $rule . "|exists:{$tableName},id";
                }
            }else if (Schema::hasTable($relation_table)) {
                $rule = $rule . "|exists:{$relation_table},id";
            }
        }
        return $rule;
    }
}
