<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
     /**
     * login the specified resource.
     */
    public function login(UserRequest $request)
    {
        //
        
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => 'The provided email or password are incorrect.'
            ]);
        }

        $response = [
            'user' => $user,
            'token' => $user->createToken($request->email)->plainTextToken
        ];
    
        return $response;
    }

    /**
     * Logout the specified resource.
     */
    public function logout(Request $request)
    {
        //
        $request->user()->tokens()->delete();

        $response = [
            'message' => 'Logout'
        ];

        return $response;

    }

    public function isauthorizeUser()
    {

        return [
            "message"  => 'authorized'
        ];

    }
}
