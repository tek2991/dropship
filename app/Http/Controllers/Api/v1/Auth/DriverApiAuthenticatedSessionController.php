<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\v1\Auth\DriverLoginRequest;




class DriverApiAuthenticatedSessionController extends Controller
{
    /**
     * Driver Login
     * 
     * API endpoint for driver login. If everything is okay, you'll get a 200 Status with JSON response containing the token and user object.
     * 
     * <aside class="notice">The <b>token</b> need to need to be sent as an <b>Authorization</b> header with the value <b>"Bearer {YOUR_AUTH_KEY}"</b> for all subsequest request.</aside>
     * 
     * @response status=200 scenario=Success {"status": true, "message": "Login Successful", "data":{"token": "9|MXCoGNxqdUtiays8MLg3isoLBwZztMB4eEan4PfM", "user":{"id": 23, "name": "Driver_1231479550", "email": "driver_1231479550@dropship.test", "email_verified_at": "2022-04-14T09:16:27.000000Z", "created_at": "2022-04-14T09:16:27.000000Z", "updated_at": "2022-04-14T09:16:27.000000Z", "gender": null, "dob": null, "address": "NA", "phone": "1231479550", "alternate_phone": "NA", "is_active": "1"}}}
     * 
     * @response status=200 scenario="Incorrect credentials" {"status": false, "message": "Login Failed", "data":{}}
     */
    public function store(DriverLoginRequest $request)
    {
        try {
            $request->authenticate();
            $user = User::firstWhere('phone', $request->phone);
            $token = $user->createToken('driver')->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => 'Login Successful',
                'data' => [
                    'token' => $token,
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            // 🧐 
            return response()->json([
                'status' => false,
                'message' => 'Login Failed',
                // 'errors' => $e->getMessage(),
                'data' => (object)[],
            ], 200);
        }
    }


    /**
     * Logout
     * 
     * API endpoint for logout. If everything is okay, you'll get a 200 Status with success message in JSON format.
     * 
     * Otherwise, the request will fail with a 401 error, and a JSON response with the error detail.
     * 
     * <aside class="notice">Logout method is common for all user levels.</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {"status": true, "message": "Logout successfully", "data": {}}
     */
    public function destroy()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logout successfully',
            'data' => (object)[],
        ]);
    }
}
