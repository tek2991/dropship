<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Location;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->validate($request, [
            'vehicle_id' => 'nullable|integer|exists:vehicles,id',
            'location_id' => 'nullable|integer|exists:locations,id'
        ]);

        $vehicles = Vehicle::all();
        $locations = Location::all();

        $vehicle_id = $data['vehicle_id'] ?? '';
        $location_id = $data['location_id'] ?? '';

        return view('admin.reports.index', compact('vehicles', 'vehicle_id', 'locations', 'location_id'));
    }
}
