<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($fields)) {
            throw ValidationException::withMessages([
                'email' => 'Your provided credentials could not be verified.'
            ]);
        }
        $user = auth()->user();
        $user->tokens()->delete();
        $token = $user->createToken('api_token')->plainTextToken;

        return response(['user' => $user, 'token' => $token]);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response(['succes' => true]);
    }
}
