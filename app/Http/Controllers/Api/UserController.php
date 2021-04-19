<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;
use Tymon\JWTAuth\JWTAuth as JWTAuthJWTAuth;

class UserController extends Controller
{
    public function register(RegistrationRequest $request){

        User::create([
        	'name'=>$request->name,
        	'email'=>$request->email,
        	'password'=> bcrypt($request->password)
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
}
