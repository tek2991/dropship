<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\v1\Auth\DriverLoginRequest;




class DriverApiAuthenticatedSessionController extends Controller
{
    
     
    public function store(DriverLoginRequest $request)
    {
        $request->authenticate();

        $user = User::firstWhere('phone', $request->phone);
        $token = $user->createToken('driver')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }


    public function destroy()
    {
        Auth::user()->tokens()->delete();
        return response()->json(['message' => 'Logout successfully']);
    }
}
