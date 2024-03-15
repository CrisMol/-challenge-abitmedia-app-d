<?php

namespace App\Http\Controllers\api\v1;

use App\Models\Software;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Controllers\ApiController;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Software $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Software $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Software $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Software $product)
    {
        //
    }
}
