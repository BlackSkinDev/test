<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
class UserController extends Controller
{
    public function register(RegistrationRequest $request){

        $user= User::create([
        	'name'=>$request->name,
        	'email'=>$request->email,
        	'password'=> Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Account Created Successfully',
        ], Response::HTTP_OK);

    }

    public function login(LoginRequest $request){

        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token =JWTAuth::attempt($input)) {
            return response()->json([
                'message' => 'Invalid Email or Password',
            ],Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'token' => $jwt_token,
        ],Response::HTTP_OK);
    }

    public function profile(){
        $user = JWTAuth::user();
        return response()->json(['user' => new UserResource($user)],Response::HTTP_OK);
    }
}
