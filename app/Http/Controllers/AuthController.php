<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){
            $fields=$request->validate([
                'email'=>'required|email',
                'password'=>'required',
            ]);

            $user=User::where('email',$fields['email'])->first();

            if(!$user||!Hash::check($fields['password'],$user->password)){
                return response()->json([
                    'message' => 'Invalid credentials'
            ], 401);
            }
              $token=$user->createToken('api_token')->plainTextToken;
            return response()->json([
            'user'=>$user,
            'token'=>$token   
            ]);
    }
    public function logout(Request $request){
            $request->user()->currentAccessToken()->delete();
            return response()->json([
        'message' => 'Logged out successfully'
    ]);
    }
}
