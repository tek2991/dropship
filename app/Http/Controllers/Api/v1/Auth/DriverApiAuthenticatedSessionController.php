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
     * Otherwise, the request will fail with a 422 error, and a JSON response with error details.
     * 
     * <aside class="notice">The token need to need to be passed in the Authorization header of all subsequest request.</aside>
     * 
     * @response status=200 scenario=Success {
     *  "token": "1|cmNFo7NCyMz0L4gbWPuTn5yxN246TVfKw56dOOxd",
     *    "user": {
     *        "id": 4,
     *        "name": "Driver_8011302757",
     *        "email": "driver_8011302757@dropship.test",
     *        "email_verified_at": "2022-03-14T04:59:57.000000Z",
     *        "created_at": "2022-03-14T04:59:57.000000Z",
     *        "updated_at": "2022-03-14T04:59:57.000000Z",
     *        "gender": null,
     *        "dob": null,
     *        "address": "NA",
     *        "phone": "8011302757",
     *        "alternate_phone": "NA",
     *        "is_active": 1
     *    }
     *  }
     * @response status=422 scenario="Incorrect credentials" {
     *  "message": "These credentials do not match our records.",
     *      "errors": {
     *          "phone": [
     *              "These credentials do not match our records."
     *          ]
     *     }
     *   }
     */
    public function store(DriverLoginRequest $request)
    {
        $request->authenticate();

        $user = User::firstWhere('phone', $request->phone);
        $token = $user->createToken('driver')->plainTextToken;

        return response()->json([
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }


    /**
     * Logout
     * 
     * API endpoint for logout. If everything is okay, you'll get a 200 Status with success message in JSON format.
     * 
     * Otherwise, the request will fail with a 401 error, and a JSON response with the error detail.
     * 
     * <aside class="notice">Logout API is common for all user levels.</aside>
     * 
     * @authenticated
     * 
     * @response status=200 scenario=Success {
     *      "message": "Logout successfully"
     *  }
     * @response status=422 scenario="Incorrect credentials" {
     *      "message": "Unauthenticated."
     *  }
     */
    public function destroy()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logout successfully']);
    }
}
