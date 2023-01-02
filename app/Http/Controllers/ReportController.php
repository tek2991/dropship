<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->validate($request, [
            'vehicle_id' => 'nullable|integer|exists:vehicles,id'
        ]);

        $vehicles = Vehicle::all();

        $vehicle_id = $data['vehicle_id'] ?? '';

        return view('admin.reports.index', compact('vehicles', 'vehicle_id'));
    }
}
