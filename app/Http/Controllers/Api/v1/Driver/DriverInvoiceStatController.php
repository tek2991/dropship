<?php

namespace App\Http\Controllers\Api\v1\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DriverInvoiceStatController extends Controller
{
    /**
     * Driver Invoice stats
     * 
     * API endpoint for driver's invoice statistics. If everything is okay, you'll get a 200 Status with paginated response data in JSON format.
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {"status": true, "message": "Driver Invoice statistics", "data":{"total_pending_invoices": 5, "pending_gross_weight": 0}}
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $total_pending_invoices = $user->driver->invoices()->where('delivery_status', 'pending')->count();
            $pending_gross_weight = $user->driver->invoices()->where('delivery_status', 'pending')->sum('gross_weight');

            return response()->json([
                'status' => true,
                'message' => 'Driver Invoice statistics',
                'data' => (object)[
                    'total_pending_invoices' => $total_pending_invoices,
                    'pending_gross_weight' => $pending_gross_weight,
                ],
            ]);
        } catch (\Exception $e) {
            // 🧐 
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch driver invoice statistics',
                'errors' => $e->getMessage(),
                'data' => (object)[],
            ], 200);
        }
    }
}
