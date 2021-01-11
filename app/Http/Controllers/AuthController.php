<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {

        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        $login = Auth::attempt($credentials);

        if(!$login) {

            return response()->json([
                'status_code' => 401,
                'message' => 'Invalid login details'
            ]);
        }

        return response()->json([
            'status_code' => 201,
            'message' => 'User logged in'
        ]);

    }
}
