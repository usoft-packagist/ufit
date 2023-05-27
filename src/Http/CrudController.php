<?php

namespace Usoft\Ufit\Http;

use Illuminate\Support\Facades\Request;
use Usoft\Ufit\Abstracts\CrudService;
use Usoft\Ufit\Abstracts\Exceptions\CreateException;
use Usoft\Ufit\Abstracts\Exceptions\NotFoundException;
use Usoft\Ufit\Abstracts\Exceptions\UpdateException;
use Usoft\Ufit\Abstracts\Http\ApiBaseController;
use Usoft\Ufit\Interfaces\Http\CrudBaseController;
use Usoft\Ufit\Abstracts\Service;
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
    public function __construct()
    {
        $this->service = new CrudService();
    }
    public function index(PaginationRequest $request)
    {
        try {
            $itemsQuery = $this->service->getQuery();
        } catch (\Exception $th) {
            return $this->errorBadRequest($th->getMessage(), $th);
        }
        return $this->paginateQuery($itemsQuery, ItemResource::class);
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
            $item = $this->service
                ->setData($request->all())
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
            $item = $this->service
                ->setData($request->all())
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
        return $this->paginateQuery($itemsQuery, ClientItemResource::class);
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

