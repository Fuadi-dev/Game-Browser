<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function adminAuth(Request $request){
        // $token = $request->bearerToken();
        // if(!$token){
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Token not found',
        //     ], 401);
        // }
        // //find token in database
        // $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        // if(!$tokenModel){
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Token not found',
        //     ], 401);
        // }

        // //get the user associated with the token
        // $user = $tokenModel->tokenable;
        // if(!$user || $user->role === 'player'){
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Unauthorized',
        //     ], 403);
        // }

        // //start session if not started
        // if (!$request->session()->isStarted()) {
        //     $request->session()->start();
        // }
        // Auth::guard('web')->login($user);
        // // Regenerate session ID
        // $request->session()->regenerate(); // Regenerate session ID

        // $cookie = cookie(
        //     config('session.cookie'),
        //     $request->session()->getId(),
        //     config('session.lifetime'),
        //     config('session.path'),
        //     config('session.domain'),
        //     config('session.secure'),
        //     config('session.http_only'),
        //     false,
        //     config('session.same_site')
        // );

        return response()->json([
            'status' => 'success',
            'message' => 'Admin authenticated in successfully',
            // 'redirect' => 'http://127.0.0.1:8000/login',
        ], 200);
    }

        //login post api
        public function login(Request $request){
            $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);

            //if user not found
            $user = User::where('email', $request->email)->first();
            if(!$user || !Hash::check($request->password, $user->password)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Password or email is incorrect',
                ], 401);
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully',
                'user' => $user,
                'token' => $token,
            ], 201);
        }
        //register post api
        public function register(Request $request){
           try{
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user,
                'token' => $token,
            ], 201);
           }catch(ValidationException $e){
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
            }
            // if(strlen($request->password) < 8) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Password must be at least 8 characters',
            //     ], 400);
            // }elseif(User::where('email', $request->email)->first()){
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'User already exists',
            //     ], 400);
            // }elseif($request->password != $request->confirm_password){
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Password and confirm password do not match',
            //     ], 400);
            // }

            // $user = User::create([
            //     'name' => $request->name,
            //     'email' => $request->email,
            //     'password' => Hash::make($request->password),
            // ]);
            // $token = $user->createToken('auth_token')->plainTextToken;
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'User registered successfully and  logged in',
            //     'token' => $token,
            //     'user' => $user,
            // ], 201);
        }

        public function logout(Request $request){
            $request->user()->currentAccessToken()->delete();
            Auth::guard('web')->logout();
            return response()->json([
                'status' => 'success',
                'message' => 'User logged out successfully',
                'redirect' => 'http://localhost:3000/#login'
            ], 200);
        }
}
