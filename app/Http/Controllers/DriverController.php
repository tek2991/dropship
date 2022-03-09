<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Driver;
use App\Http\Requests\cruds\StoreDriverRequest;
use App\Http\Requests\cruds\UpdateDriverRequest;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cruds.drivers.index', [
            'drivers' => Driver::with('user')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cruds.drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDriverRequest $request)
    {
        $user = User::create($request->validated());
        $user->assignRole('driver');
        $user->driver()->create();

        return redirect()->route('drivers.index')->with('message', 'Driver: ' . $user->name . ' created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        return view('cruds.drivers.show', [
            'driver' => $driver->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        return view('cruds.drivers.edit', [
            'driver' => $driver->load('user'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        $driver->user->update($request->validated());

        return redirect()->route('drivers.index')->with('message', 'Driver: ' . $driver->user->name . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        return back();
    }
}