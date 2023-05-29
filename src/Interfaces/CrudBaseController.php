<?php

namespace Usoft\Ufit\Interfaces\Http;

use Illuminate\Support\Facades\Request;
use Usoft\Ufit\Requests\DestroyRequest;
use Usoft\Ufit\Requests\PaginationRequest;
use Usoft\Ufit\Requests\ShowRequest;

interface CrudBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request  $request
     *
     */
    function index(PaginationRequest $request);
    /**
     * Show resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    function show(ShowRequest $request);

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    function store(Request $request);

    /**
     * Update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    function update(Request $request);

    /**
     * Delete resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    function destroy(DestroyRequest $request);

    //Controller for client API

    /**
     * Enlist all information.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    function findAll(PaginationRequest $request);
    /**
     * Show one information
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    function findOne(ShowRequest $request);
}

