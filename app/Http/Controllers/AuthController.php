<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Invalid login details'
                ]);
            }

            $user = User::where('email', $request['email'])->first();

            if ( ! Hash::check($request['password'], $user['password'], [])) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Password is incorrect'
                ]);
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'user' => $user,
                'token_type' => 'Bearer',
            ]);
        } catch (ModelNotFoundException $error) {
            return response()->json([
                'status_code' => 401,
                'message' => 'User does not exist with this details',
                'error' => $error,
            ]);
        }
    }
}
