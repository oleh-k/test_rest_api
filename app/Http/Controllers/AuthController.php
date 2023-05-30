<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'email:rfc', 'unique:users,email'],
            'password' => ['required', 'min:3', 'max:64', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        $token = $user->createToken('myapp')->plainTextToken;

        $response = [
            'token' => $token,
            'user' => $user
        ];

        return response($response, 200);


    }

    public function logout(Request $request)
    {
        auth('sanctum')->user()->tokens()->delete();

        return response(['status' => 'success'], 200);
    }
}
