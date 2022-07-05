<?php

namespace App\Http\Controllers\Admin;

use App\Models\Manager;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\cruds\StoreLocationRequest;
use App\Http\Requests\cruds\UpdateLocationRequest;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.locations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.locations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLocationRequest $request)
    {
        $location = Location::create($request->validated());
        return redirect()->route('admin.locations.index')->with('message', 'New Location ' . $location->name . ' created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        return view('admin.locations.show', [
            'location' => $location->load('managers'),
            'managers' => Manager::with('user')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        return view('admin.locations.edit', [
            'location' => $location->load('managers'),
            'managers' => Manager::with('user')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLocationRequest $request, Location $location)
    {
        $location->update($request->validated());
        return redirect()->route('admin.locations.index')->with('message', 'Location ' . $location->name . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        //
    }


    public function addManager(Request $request, Location $location)
    {
        $this->validate($request, [
            'manager_id' => 'required|exists:managers,id',
        ]);
        $location->managers()->syncWithoutDetaching($request->manager_id);
        return redirect()->route('admin.locations.show', $location)->with('message', 'Manager added successfully.');
    }


    public function removeManager(Request $request, Location $location)
    {
        $this->validate($request, [
            'manager_id' => 'required|exists:managers,id',
        ]);
        $location->managers()->detach($request->manager_id);
        return redirect()->route('admin.locations.show', $location)->with('message', 'Manager removed successfully.');
    }
}
