<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "unique:users,email"
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        if ($validator->passes()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $token = $user->createToken($user->email . '_Token')->plainTextToken;
            return response()->json([
                'username' => $user->name,
                'token' => $token,
                'message' => 'Registered succesfully...',
            ], 200);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required",
            "password" => "required"
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status' => 400,
                'error' => $errors
            ], 400);
        }
        if ($validator->passes()) {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid Credential',
                ], 401);
            } else {
                if($user->as_role == 1){
                    $token = $user->createToken($user->email . '_AdminToken',['server:admin'])->plainTextToken;
                }else{
                     $token = $user->createToken($user->email . '_Token',[''])->plainTextToken;
                }
               
                return response()->json([
                    'status' => 200,
                    'username' => $user->name,
                    'token' => $token,
                    'role' => $user->as_role,
                    'message' => 'Logged in successfully',
                ], 200);
            }
        }
    }

    public function logout()
    {
        $tokenDelete =  Auth::user()->tokens()->delete();
        if ($tokenDelete) {
            return response()->json([
                'status' => 200,
                'message' => 'Loggged out successfully',
            ], 200);
        }
    }
    public function checkAuthenticated()
    {
        if (auth()->user()) {
            return response()->json([
                'status' => 200,
                'message' => '',
            ], 200);
        } 
    }
}
