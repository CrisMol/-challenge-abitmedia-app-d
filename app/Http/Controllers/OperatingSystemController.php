<?php

namespace App\Http\Controllers;

use App\Models\OperatingSystem;
use App\Http\Requests\StoreOperatingSystemRequest;
use App\Http\Requests\UpdateOperatingSystemRequest;

class OperatingSystemController extends Controller
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
    public function store(StoreOperatingSystemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OperatingSystem $operatingSystem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OperatingSystem $operatingSystem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOperatingSystemRequest $request, OperatingSystem $operatingSystem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OperatingSystem $operatingSystem)
    {
        //
    }
}
