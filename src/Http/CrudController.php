<?php

namespace Usoft\Ufit\Http;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Usoft\Ufit\Abstracts\CrudService;
use Usoft\Ufit\Abstracts\Exceptions\CreateException;
use Usoft\Ufit\Abstracts\Exceptions\NotFoundException;
use Usoft\Ufit\Abstracts\Exceptions\UpdateException;
use Usoft\Ufit\Abstracts\Http\ApiBaseController;
use Usoft\Ufit\Abstracts\Model;
use Usoft\Ufit\Abstracts\Service;
use Usoft\Ufit\Interfaces\CrudBaseController;
use Usoft\Ufit\Requests\DestroyRequest;
use Usoft\Ufit\Requests\PaginationRequest;
use Usoft\Ufit\Requests\ShowRequest;
use Usoft\Ufit\Responses\ClientItemResource;
use Usoft\Ufit\Responses\ItemResource;

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
    public function __construct(Model $model)
    {
        $this->service = new CrudService($model);
    }

    public function globalValidation($request, $rules=[]){
        if(count($rules)){
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'message' => trans('ufit_translations::'.$validator->errors()->first())
                ], 422);
            }
        }
        return $request->all();
    }
    public function index(PaginationRequest $request)
    {
        try {
            $itemsQuery = $this->service->getQuery();
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->paginateQuery(ItemResource::class, $itemsQuery);
    }

    public function show(ShowRequest $request)
    {
        try {
            $item = $this->service
                ->setData($request->all())
                ->setById()
                ->get();
        } catch (NotFoundException $th) {
            return $this->errorNotFound($th->getMessage(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->singleItem(ItemResource::class, $item);
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
            $store_rules = $this->service->storeRules();
            $validated = $this->globalValidation($request, $store_rules);
            $item = $this->service
                ->setData($validated)
                ->create()
                ->get();
        } catch (CreateException $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->created(ItemResource::class, $item);
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
            $update_rules = $this->service->updateRules();
            $validated = $this->globalValidation($request, $update_rules);;
            $item = $this->service
                ->setData($validated)
                ->setById()
                ->update()
                ->get();
        } catch (UpdateException $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        } catch (NotFoundException $th) {
            return $this->errorNotFound($th->getMessage(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->accepted(ItemResource::class, $item);
    }

    /**
     * Delete resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function destroy(DestroyRequest $request)
    {
        try {
            $this->service
                ->setData($request->all())
                ->setById()
                ->delete();
        } catch (NotFoundException $th) {
            return $this->errorNotFound($th->getMessage(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->noContent();
    }

    //Controller for client API

    public function findAll(PaginationRequest $request)
    {
        try {
            $itemsQuery = $this->service->getQuery();
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->paginateQuery(ClientItemResource::class, $itemsQuery);
    }

    public function findOne(ShowRequest $request)
    {
        try {
            $item = $this->service
                ->setData($request->all())
                ->setById()
                ->get();
        } catch (NotFoundException $th) {
            return $this->errorNotFound($th->getMessage(), $th);
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->singleItem(ClientItemResource::class, $item);
    }
}
