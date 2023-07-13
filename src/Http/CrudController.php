<?php

namespace Usoft\Ufit\Http;

use Illuminate\Http\Request;
use Usoft\Ufit\Abstracts\CrudService;
use Usoft\Ufit\Abstracts\Exceptions\CreateException;
use Usoft\Ufit\Abstracts\Exceptions\NotFoundException;
use Usoft\Ufit\Abstracts\Exceptions\UpdateException;
use Usoft\Ufit\Abstracts\Exceptions\ValidationException;
use Usoft\Ufit\Abstracts\Http\ApiBaseController;
use Usoft\Ufit\Abstracts\Model;
use Usoft\Ufit\Abstracts\Service;
use Usoft\Ufit\Interfaces\CrudBaseController;

abstract class CrudController extends ApiBaseController implements CrudBaseController
{
    protected Service $service;
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request  $request
     *
     */
    /**
     * Class constructor.
     */
    public function __construct(Model $model, Service $service=null)
    {
        if($service){
            $this->service = new $service($model);
        }else{
            $this->service = new CrudService($model);
        }
    }

    
    public function index(Request $request)
    {
        try {
            $data = $this->service->globalValidation($request->all(), $this->service->indexRules());
            $itemsQuery = $this->service
                ->setData($data)
                ->getQuery();
        } catch (ValidationException $th) {
            return $this->error($th->getMessage(), $th->getCode(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->paginateQuery($this->service->getItemResource(), $itemsQuery, $this->service->getModelTableName());
    }

    public function show(Request $request)
    {
        try {
            $data = $this->service->globalValidation($request->all(), $this->service->showRules());
            $item = $this->service
                ->setData($data)
                ->setById()
                ->get();
        } catch (ValidationException $th) {
            return $this->error($th->getMessage(), $th->getCode(), $th);
        } catch (NotFoundException $th) {
            return $this->errorNotFound($th->getMessage(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->singleItem($this->service->getItemResource(), $item);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function store(Request $request)
    {
        try {
            $data = $this->service->globalValidation($request->all(), $this->service->storeRules());
            $item = $this->service
                ->setData($data)
                ->create()
                ->get();
        } catch (ValidationException $th) {
            return $this->error($th->getMessage(), $th->getCode(), $th);
        } catch (CreateException $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->created($this->service->getItemResource(), $item);
    }

    /**
     * Update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function update(Request $request)
    {
        try {
            $data = $this->service->globalValidation($request->all(), $this->service->updateRules());
            $item = $this->service
                ->setData($data)
                ->setById()
                ->update()
                ->get();
        } catch (ValidationException $th) {
            return $this->error($th->getMessage(), $th->getCode(), $th);
        } catch (UpdateException $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        } catch (NotFoundException $th) {
            return $this->errorNotFound($th->getMessage(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->accepted($this->service->getItemResource(), $item);
    }

    /**
     * Delete resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function destroy(Request $request)
    {
        try {
            $data = $this->service->globalValidation($request->all(), $this->service->destroyRules());
            $this->service
                ->setData($data)
                ->setById()
                ->delete();
        } catch (ValidationException $th) {
            return $this->error($th->getMessage(), $th->getCode(), $th);
        } catch (NotFoundException $th) {
            return $this->errorNotFound($th->getMessage(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->noContent();
    }

    //Controller for client API

    public function findAll(Request $request)
    {
        try {
            $data = $this->service->globalValidation($request->all(), $this->service->findAllRules());
            $itemsQuery = $this->service
                ->setData($data)
                ->getQuery();
        } catch (ValidationException $th) {
            return $this->error($th->getMessage(), $th->getCode(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->paginateQuery($this->service->getClientItemResource(), $itemsQuery, $this->service->getModelTableName());
    }

    public function findOne(Request $request)
    {
        try {
            $data = $this->service->globalValidation($request->all(), $this->service->findOneRules());
            $item = $this->service
                ->setData($data)
                ->setById()
                ->get();
        } catch (ValidationException $th) {
            return $this->error($th->getMessage(), $th->getCode(), $th);
        } catch (NotFoundException $th) {
            return $this->errorNotFound($th->getMessage(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->singleItem($this->service->getClientItemResource(), $item);
    }
}
