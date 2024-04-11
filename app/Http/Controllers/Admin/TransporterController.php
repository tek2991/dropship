<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\cruds\StoreTransporterRequest;
use App\Http\Requests\cruds\UpdateTransporterRequest;
use App\Models\Location;

class TransporterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.transporters.index', [
            'transporters' => Transporter::paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.transporters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransporterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('transporter');
        $user->transporter()->create([
            'is_first_party' => $request->is_first_party,
        ]);

        return redirect()->route('admin.transporters.index')->with('message', 'Transporter: ' . $user->name . ' created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transporter  $transporter
     * @return \Illuminate\Http\Response
     */
    public function show(Transporter $transporter)
    {
        return view('admin.transporters.show', [
            'transporter' => $transporter->load('user', 'locations'),
            'locations' => Location::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transporter  $transporter
     * @return \Illuminate\Http\Response
     */
    public function edit(Transporter $transporter)
    {
        return view('admin.transporters.edit', [
            'transporter' => $transporter->load('user', 'locations'),
            'locations' => Location::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transporter  $transporter
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransporterRequest $request, Transporter $transporter)
    {
        $transporter->update([
            'is_first_party' => $request->is_first_party,
        ]);
        $transporter->user->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.transporters.index')->with('message', 'Transporter: ' . $transporter->user->name . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transporter  $transporter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transporter $transporter)
    {
        //
    }

    public function updatePassword(Request $request, Transporter $transporter)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        $transporter->user->update(['password' => Hash::make($request->password)]);
        return redirect()->route('admin.transporters.index')->with('message', 'Transporter: ' . $transporter->user->name . ' updated successfully.');
    }

    public function addLocation(Request $request, Transporter $transporter)
    {
        $request->validate([
            'location_id' => ['required', 'exists:locations,id'],
        ]);
        $transporter->locations()->syncWithoutDetaching($request->location_id);
        return redirect()->route('admin.transporters.show', $transporter)->with('message', 'Transporter: ' . $transporter->user->name . ' updated successfully.');
    }

    public function removeLocation(Request $request, Transporter $transporter)
    {
        $request->validate([
            'location_id' => ['required', 'exists:locations,id'],
        ]);
        $transporter->locations()->detach($request->location_id);
        return redirect()->route('admin.transporters.show', $transporter)->with('message', 'Transporter: ' . $transporter->user->name . ' updated successfully.');
    }
}
