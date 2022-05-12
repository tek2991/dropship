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
     * @response status=200 scenario=Success {"status": true, "message": "Driver Invoice statistics", "data":{"total_pending_invoices": 5, "invoices_delivered_today": 0}}
     */
    public function index()
    {
        try {
            $user = Auth::user();

            $total_pending_invoices = $user->driver->invoices()->where('is_delivered', false)->count();
            $today = date('Y-m-d');
            $invoices_delivered_today = $user->driver->invoices()->where('is_delivered', true)->where('invoices.updated_at', '>=', $today)->count();

            return response()->json([
                'status' => true,
                'message' => 'Driver Invoice statistics',
                'data' => (object)[
                    'total_pending_invoices' => $total_pending_invoices,
                    'invoices_delivered_today' => $invoices_delivered_today,
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
