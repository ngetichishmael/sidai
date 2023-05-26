<?php

namespace App\Http\Controllers;

use App\Models\PriceGroup;
use App\Http\Requests\StorePriceGroupRequest;
use App\Http\Requests\UpdatePriceGroupRequest;

class PriceGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('livewire.price-group.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePriceGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePriceGroupRequest $request)
    {
        PriceGroup::create($request->validated());

        Session()->flash('success', "Pricing successfully added");
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PriceGroup  $priceGroup
     * @return \Illuminate\Http\Response
     */
    public function show(PriceGroup $priceGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PriceGroup  $priceGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(PriceGroup $priceGroup)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePriceGroupRequest  $request
     * @param  \App\Models\PriceGroup  $priceGroup
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePriceGroupRequest $request, PriceGroup $priceGroup)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PriceGroup  $priceGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceGroup $priceGroup)
    {
        //
    }
}
