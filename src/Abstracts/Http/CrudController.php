<?php

namespace Usoft\Ufit\Abstracts\Http;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Usoft\Ufit\Abstracts\CrudService;
use Usoft\Ufit\Abstracts\Exceptions\CreateException;
use Usoft\Ufit\Abstracts\Exceptions\NotFoundException;
use Usoft\Ufit\Abstracts\Exceptions\UpdateException;
use Usoft\Ufit\Abstracts\Model;
use Usoft\Ufit\Abstracts\Service;
use Usoft\Ufit\Requests\DestroyRequest;
use Usoft\Ufit\Requests\PaginationRequest;
use Usoft\Ufit\Requests\ShowRequest;

abstract class CrudController extends ApiBaseController
{
    private Service $service;
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
            $items = $this->service->getQuery()->pagiante($request->limit);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 400, $exception);
        }
        return $this->paginated($items, ItemResource::class);
    }

    public function show(ShowRequest $request)
    {
        try {
            $organization = $this->service
                ->setById($request->id)
                ->get();
        } catch (NotFoundException $th) {
            return $this->error($th->getMessage(), 400);
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->singleItem(Resource::class, $organization);
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
            $organization = $this->service->create($request->validated())->get();
        } catch (CreateException $th) {
            return $this->error($th->getMessage(), 400);
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->singleItem(Resource::class, $organization, 201);
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
            $organization = $this->service
                ->setById($request->id)
                ->update($request->validated())->get();
        } catch (UpdateException $th) {
            return $this->error($th->getMessage(), 400);
        } catch (NotFoundException $th) {
            return $this->error($th->getMessage(), 400);
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->singleItem(Resource::class, $organization, 202);
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
            $this->service->setById($request->id)->get()->delete();
        } catch (NotFoundException $th) {
            return $this->error($th->getMessage(), 400);
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->noContent();
    }

    //Controller for client API

    public function findAll(PaginationRequest $request)
    {
        try {
            $items = $this->service->getQuery()->pagiante($request->limit);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 400, $exception);
        }
        return $this->paginated($items, ItemClientResource::class);
    }

    public function findOne(ShowRequest $request)
    {
        try {
            $organization = $this->service->setById($request->id)->get();
        } catch (NotFoundException $th) {
            return $this->error($th->getMessage(), 400);
        } catch (\Exception $th) {
            return $this->error($th->getMessage(), 400);
        }
        return $this->singleItem(ItemClientResource::class, $organization);
    }
}

