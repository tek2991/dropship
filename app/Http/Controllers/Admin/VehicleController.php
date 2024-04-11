<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\cruds\StoreVehicleRequest;
use App\Http\Requests\cruds\UpdateVehicleRequest;
use App\Models\Location;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.vehicles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVehicleRequest $request)
    {
        $vehicle = Vehicle::create($request->validated());

        return redirect()->route('admin.vehicles.index')->with('message', 'Vehicle: ' . $vehicle->registration_number . ' created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        return view('admin.vehicles.show', [
            'vehicle' => $vehicle->load('locations'),
            'locations' => Location::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', [
            'vehicle' => $vehicle->load('locations'),
            'locations' => Location::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $vehicle->update($request->validated());

        return redirect()->route('admin.vehicles.index')->with('message', 'Vehicle: ' . $vehicle->registration_number . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }

    public function addLocation(Request $request, Vehicle $vehicle){
        $this->validate($request, [
            'location_id' => 'required|exists:locations,id',
        ]);
        $vehicle->locations()->syncWithoutDetaching($request->location_id);
        return redirect()->route('admin.vehicles.index')->with('message', 'Vehicle: ' . $vehicle->registration_number . ' updated successfully.');
    }

    public function removeLocation(Request $request, Vehicle $vehicle){
        $this->validate($request, [
            'location_id' => 'required|exists:locations,id',
        ]);
        $vehicle->locations()->detach($request->location_id);
        return redirect()->route('admin.vehicles.index')->with('message', 'Vehicle: ' . $vehicle->registration_number . ' updated successfully.');
    }
}
