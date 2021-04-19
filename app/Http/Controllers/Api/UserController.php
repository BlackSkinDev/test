<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Response;

use Illuminate\Http\Request;

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
}
