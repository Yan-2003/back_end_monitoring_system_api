<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    //

    public function login(AdminRequest $request)
    {
        //
        
        $user = Admin::where('username', $request->username)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => 'The provided userame or password are incorrect.'
            ]);
        }

        $response = [
            'user' => $user,
            'token' => $user->createToken($request->username)->plainTextToken
        ];
    
        return $response;
    }

    public function store(AdminRequest $request)
    {
        //
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $user = Admin::create($validated);

        return $user;
    }


}
