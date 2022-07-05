<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\cruds\StoreClientRequest;
use App\Http\Requests\cruds\UpdateClientRequest;
use App\Models\Location;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.clients.index', [
            'clients' => Client::paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'address' => $request->address,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole('client');
        $user->client()->create([
            'client_number' => $request->client_number,
        ]);

        return redirect()->route('admin.clients.index')->with('message', 'Client: ' . $user->name . ' created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return view('admin.clients.show', [
            'client' => $client->load('user', 'locations'),
            'locations' => Location::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('admin.clients.edit', [
            'client' => $client->load('user', 'locations'),
            'locations' => Location::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'address' => $request->address,
            'phone' => $request->phone,
            'alternate_phone' => $request->alternate_phone,
            'is_active' => $request->is_active,
        ]);
        $client->update([
            'client_number' => $request->client_number,
        ]);
        return redirect()->route('admin.clients.index')->with('message', 'Client: ' . $client->user->name . ' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }

    public function updatePassword(Request $request, Client $client)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        $client->user->update(['password' => Hash::make($request->password)]);
        return redirect()->route('admin.clients.index')->with('message', 'Client: ' . $client->user->name . ' updated successfully.');
    }
    
    public function addLocation(Request $request, Client $client)
    {
        $request->validate([
            'location_id' => ['required', 'exists:locations,id'],
        ]);
        $client->locations()->syncWithoutDetaching($request->location_id);
        return redirect()->route('admin.clients.show', $client)->with('message', 'Client: ' . $client->user->name . ' updated successfully.');
    }

    public function removeLocation(Request $request, Client $client)
    {
        $request->validate([
            'location_id' => ['required', 'exists:locations,id'],
        ]);
        $client->locations()->detach($request->location_id);
        return redirect()->route('admin.clients.show', $client)->with('message', 'Client: ' . $client->user->name . ' updated successfully.');
    }
}
