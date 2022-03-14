<?php

namespace App\Http\Controllers\Api\v1\Driver;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\v1\InvoiceResource;

class UpdatedInvoiceController extends Controller
{
    public function index(){
        $user = Auth::user();
        $invoices = $user->driver->invoices()->where('is_delivered', true)->with('clientUser', 'images', 'updatedByUser')->get();
        
        return InvoiceResource::collection($invoices);
    }
}
