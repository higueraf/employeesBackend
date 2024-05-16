<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required"
        ]);
        if ($validator->fails()) {
            return response()->json([
                "isSuccess" => false,
                "message" => "Error data request",
                "data" => $validator->errors()->all()
            ], 422);
        }
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        return response()->json([
            "isSuccess" => true,
            "message" => "User registered successfully",
            "data" => null,
        ]);
    }

    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                "isSuccess" => false,
                "message" => "Invalid login details"
            ], 401);
        }
        $user = auth()->user();
        $customClaims = [
            'username' => $user->name,
            'email' => $user->email,
        ];
        $token = auth()->claims($customClaims)->attempt($request->only('email', 'password'));
        return response()->json([
            "isSuccess" => true,
            "message" => "User logged in",
            "data" => [
                "token" => $token,
                "username" => $user->name,
                "email" => $user->email,
            ],
            "expires_in" => auth()->factory()->getTTL() * 60
        ]);
    }
        
        
    public function profile(){

        $userData = request()->user();
        return response()->json([
            "isSuccess" => true,
            "message" => "Profile data",
            "user" => $userData,
            "user_id" => request()->user()->id,
            "email" => request()->user()->email
        ]);
    }

    public function refreshToken(){
        $token = auth()->refresh();
        return response()->json([
            "isSuccess" => true,
            "message" => "Refresh token",
            "token" => $token,
            "expires_in" => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout(){
        auth()->logout();
        return response()->json([
            "isSuccess" => true,
            "message" => "User logged out"
        ]);
    }
    
}
